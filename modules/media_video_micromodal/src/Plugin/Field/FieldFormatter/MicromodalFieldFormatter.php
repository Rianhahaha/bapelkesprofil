<?php

namespace Drupal\media_video_micromodal\Plugin\Field\FieldFormatter;

use Drupal\Core\Render\Renderer;
use Drupal\Core\Utility\Token;
use Drupal\Component\Utility\Html;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\Field\Plugin\Field\FieldType\StringItem;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\field\Entity\FieldConfig;
use Drupal\Core\State\StateInterface;
use Drupal\image\Plugin\Field\FieldType\ImageItem;
use Drupal\media\IFrameUrlHelper;
use Drupal\Core\Routing\RequestContext;
use Drupal\Core\PrivateKey;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'micromodal_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "micromodal_field_formatter",
 *   label = @Translation("Micromodal field formatter"),
 *   field_types = {
 *     "string",
 *     "image",
 *     "entity_reference"
 *   }
 * )
 */
class MicromodalFieldFormatter extends FormatterBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private EntityTypeManagerInterface $entityTypeManager;

  /**
   * The state.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  private StateInterface $state;

  /**
   * Token utility.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected Token $token;

  /**
   * Module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected ModuleHandlerInterface $moduleHandler;

  /**
   * Renderer service.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  private Renderer $renderer;

  /**
   * Constructs a MicromodalFieldFormatter instance.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings settings.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   * @param \Drupal\Core\Utility\Token $token
   *   The token service.
   * @param \Drupal\Core\State\StateInterface $state
   *   State.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   Renderer Service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, EntityTypeManagerInterface $entity_type_manager, ModuleHandlerInterface $module_handler, Token $token, StateInterface $state, Renderer $renderer) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->entityTypeManager = $entity_type_manager;
    $this->state = $state;
    $this->token = $token;
    $this->moduleHandler = $module_handler;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager'),
      $container->get('module_handler'),
      $container->get('token'),
      $container->get('state'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings(): array {
    // Implement default settings.
    return [
      'string_classes' => '',
      'caption_swap' => '',
      'link_text' => '',
      'thumbnail_image_style' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state): array {

    // Initialize settings variable.
    $settings = [];

    // For media "name" field, allow for additional classes.
    if ($this->fieldDefinition->getType() == 'string') {

      $settings = [
        'string_classes' => [
          '#title' => $this->t('Additional Classes'),
          '#type' => 'textfield',
          '#size' => 60,
          '#maxlength' => 255,
          '#default_value' => Html::escape($this->getSetting('string_classes')),
          '#description' => $this->t('Add additional classes to the text link, separate by spaces.'),
        ],
        'caption_swap' => [
          '#title' => $this->t('Caption Swap'),
          '#type' => 'checkbox',
          '#default_value' => $this->getSetting('caption_swap'),
          '#description' => $this->t('Use the media caption instead of the name for the link.'),
        ],
        'link_text' => [
          '#title' => $this->t('Link Text'),
          '#type' => 'textfield',
          '#default_value' => Html::escape($this->getSetting('link_text')),
          '#description' => $this->t('Use custom text for this link.'),
        ],
      ];

      if ($this->moduleHandler->moduleExists('token')) {
        $settings['link_text']['#description'] .= $this->t('<br/><strong>Note</strong>: This field supports tokens.');
        $settings['token_replacement'] = [
          '#theme' => 'token_tree_link',
          '#token_types' => [
            $this->fieldDefinition->getTargetEntityTypeId(),
          ],
        ];
      }

    }

    // For oembed generated thumbnails - allow for choices of image styles.
    elseif ($this->fieldDefinition->getType() === 'image') {

      $settings = [
        'thumbnail_image_style' => [
          '#title' => $this->t('Video Thumbnail Image Style'),
          '#type' => 'select',
          '#options' => image_style_options(FALSE),
          '#empty_option' => '<' . $this->t('no preview') . '>',
          '#default_value' => $this->getSetting('thumbnail_image_style'),
          '#description' => $this->t('Thumbnail for the video, click the thumbnail for the modal window.'),
        ],

      ];

    }

    // For custom uploaded thumbnails - allow for choices of image styles.
    elseif ($this->fieldDefinition->getType() === 'entity_reference') {

      $settings = [
        'thumbnail_image_style' => [
          '#title' => $this->t('Video Thumbnail Image Style'),
          '#type' => 'select',
          '#options' => image_style_options(FALSE),
          '#empty_option' => '<' . $this->t('no preview') . '>',
          '#default_value' => $this->getSetting('thumbnail_image_style'),
          '#description' => $this->t('Thumbnail for the video, click the custom uploaded thumbnail for the modal window.'),
        ],
      ];

    }

    // Implement settings form.
    return $settings + parent::settingsForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(): array {

    $summary = [];

    // Implement settings summary.
    if (!empty($this->getSetting('string_classes'))) {
      $summary[] = $this->t('Additional Classes: @classes', ['@classes' => $this->getSetting('string_classes')]);
    }
    if (!empty($this->getSetting('caption_swap'))) {
      $summary[] = $this->t('Caption Swap: ON/YES');
    }
    if (!empty($this->getSetting('link_text'))) {
      $summary[] = $this->t('Link text: @link_text', ['@link_text' => $this->getSetting('link_text')]);
    }
    if (!empty($this->getSetting('thumbnail_image_style'))) {
      $summary[] = $this->t('Image Style: @thumbnail_image_style', ['@thumbnail_image_style' => $this->getSetting('thumbnail_image_style')]);
    }

    return $summary;

  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {

    $elements = [];

    foreach ($items as $delta => $item) {

      // Use this for all image thumbnails.
      if ($item instanceof EntityReferenceItem) {
        $formatter_type = 'image';
        $image_fieldname = $item->getFieldDefinition()->getName();
      }
      // Use this for the media name.
      elseif ($item instanceof StringItem) {
        $formatter_type = 'string';
      }

      // Load the media item.
      $media_id = $item->getEntity()->id();
      $media = $this->entityTypeManager->getStorage('media')->load($media_id);

      // If the media loaded successfully, continue with the formatting.
      if (!empty($media) && !empty($formatter_type)) {

        if (array_key_exists('field_media_oembed_video', $media->getFields())) {

          // Grab the remote video URL.
          $video_url = $media->getFields()['field_media_oembed_video']->getValue()[0]['value'];

          // Use these to generate the URL for local oembed iframe.
          $request = new RequestContext($video_url);

          $private_key = new PrivateKey($this->state);
          $url_helper = new IFrameUrlHelper($request, $private_key);

          // These are needed to create the hash successfully.
          $max_width = 0;
          $max_height = 0;

          // Use parts above to generate the iframe url.
          $media_oembed_url = Url::fromRoute('media.oembed_iframe', [], [
            'query' => [
              'url' => $video_url,
              'max_width' => 0,
              'max_height' => 0,
              'hash' => $url_helper->getHash($video_url, $max_width, $max_height),
            ],
          ])->toString();

          // Implementation for the thumbnail field.
          if ($formatter_type === 'image' && !empty($image_fieldname)) {

            // The image field itself, file or entity reference.
            $image_field = $media->getFields()[$image_fieldname];

            // Media or File ID of the thumbnail.
            $target_id = $image_field->getValue()[0]['target_id'];

            // Use the image style setting to style the thumbnail.
            if (!empty($target_id)) {

              // Get the file id - easy if not entity reference.
              if ($item instanceof ImageItem) {
                $thumbnail_file_id = $target_id;
              }
              // Load the media for the thumbnail, if it's media reference.
              else {
                $thumbnail_media = $this->entityTypeManager->getStorage('media')->load($target_id);
                $thumbnail_file_id = $thumbnail_media->get('thumbnail')->target_id;
              }

              // Load the thumbnail file entity.
              $thumbnail_file = $this->entityTypeManager->getStorage('file')->load($thumbnail_file_id);

              // Make sure an image style has been set.
              if (!empty($this->getSetting('thumbnail_image_style'))) {

                $render_thumbnail = [
                  '#theme' => 'image_style',
                  '#style_name' => $this->getSetting('thumbnail_image_style'),
                  '#uri' => $thumbnail_file->uri->value,
                ];

                // Find the fieldname that holds the thumbnail image.
                if (
                  isset($thumbnail_media)
                  && method_exists($thumbnail_media, 'getFieldDefinitions')
                ) {
                  foreach ($thumbnail_media->getFieldDefinitions() as $field) {
                    // Disregard BaseFieldDefinitions.
                    if ($field instanceof FieldConfig) {
                      // Once we've found the image field, grab that name.
                      if ($field->getType() === 'image') {
                        $thumbnail_image_fieldname = $field->getName();
                        break;
                      }
                    }
                  }
                }

              }

              // Add in alt and title tags.
              $attributes = [];

              // If the item is an image - this is easy.
              if ($item instanceof ImageItem) {
                $item_values = $item->getValue();
                if (!empty($item_values['alt'])) {
                  $attributes['alt'] = $item_values['alt'];
                }
                if (!empty($item_values['title'])) {
                  $attributes['title'] = $item_values['title'];
                }
              }

              // Else we hopefully have the image field name on the image media.
              elseif (!empty($thumbnail_image_fieldname)) {

                // Alt text.
                if (!empty($thumbnail_media->$thumbnail_image_fieldname->getValue()[0]['alt'])) {
                  $attributes['alt'] = $thumbnail_media->$thumbnail_image_fieldname->getValue()[0]['alt'];
                }

                // Title text.
                if (!empty($thumbnail_media->$thumbnail_image_fieldname->getValue()[0]['title'])) {
                  $attributes['title'] = $thumbnail_media->$thumbnail_image_fieldname->getValue()[0]['title'];
                }

              }

              // If we have no attributes, lets at least set alt to the name.
              if (empty($attributes)) {
                $attributes['alt'] = $media->label();
              }

              // Attach attributes add them to the thumbnail.
              $render_thumbnail['#attributes'] = $attributes;

              // Render out the thumbnail - this is the linked item.
              $linked_item = \Drupal::service('renderer')->render($render_thumbnail);

            }

          }

          // Implementation for the name field.
          elseif ($formatter_type === 'string') {

            // Initialize array to hold additional classes on the link.
            $additional_classes = [];

            // Check for additional string classes.
            if (!empty($this->getSetting('string_classes'))) {
              $additional_classes[] = Html::escape($this->getSetting('string_classes'));
            }

            // Check for caption swap setting.
            if (!empty($this->getSetting('caption_swap'))) {
              $additional_classes[] = 'caption-swap';
            }

            // Check if the link text has been filled in.
            if (!empty($this->getSetting('link_text'))) {
              $label = Html::escape($this->t('@link_text', ['@link_text' => $this->getSetting('link_text')]));
              if ($this->moduleHandler->moduleExists('token')) {
                $data = [
                  $media->getEntityTypeId() => $media,
                ];
                $label = $this->token->replace($label, $data, ['clear' => TRUE]);
              }
            }
            else {
              $label = $media->getName();
            }
            $label = $this->t('@label', ['@label' => $label]);

            // Add additional classes if there are any.
            if (!empty($additional_classes)) {
              $linked_item_render = [
                '#markup' => '<span class="' . implode(' ', $additional_classes) . '">' . $label . '</span>',
              ];
              $linked_item = \Drupal::service('renderer')->render($linked_item_render);
            }
            else {
              $linked_item = $label;
            }

          }

          // If there is an item add to the elements array.
          if (!empty($linked_item)) {
            // Generate a value for the div id.
            // First, retrieve the field name for the current field.
            $field_name = $this->fieldDefinition->getItemDefinition()->getFieldDefinition()->getName();
            // Create a string for the div id.
            $modal_id = 'modal-media-' . $media_id . '-' . $delta . '-' . $field_name;
            // Ensure uniqueness of the id.
            $modal_id_unique = Html::getUniqueId($modal_id);
            // Send these to the twig template.
            $elements[$delta] = [
              '#theme' => 'media_video_micromodal',
              '#media' => $media,
              '#view_mode' => $this->viewMode,
              '#modal_id' => $modal_id_unique,
              '#linked_item' => $linked_item,
              '#iframe_src' => $media_oembed_url,
            ];
          }

        }

      }

    }

    // Attach libraries.
    if (!empty($elements)) {

      // Main library.
      $elements['#attached'] = [
        'library' => [
          'media_video_micromodal/micromodal',
          'media_video_micromodal/micromodal_libraries',
        ],
      ];

      // Add supporting javascript for the caption swap setting.
      if (!empty($this->getSetting('caption_swap'))) {
        $elements['#attached']['library'][] = 'media_video_micromodal/caption_swap';
      }

    }

    // Return elements.
    return $elements;

  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition): bool {

    return $field_definition->getTargetEntityTypeId() === 'media'
      // @todo This always returns "null", maybe b/c of this bug:
      // https://www.drupal.org/project/drupal/issues/2976795
      // && $field_definition->getTargetBundle() === 'remote_video'
      // returns null on "name" and "thumbnail".
      && ($field_definition->getTargetEntityTypeId() === 'image'
        || $field_definition->getFieldStorageDefinition()->getTargetEntityTypeId() === 'media'
        || $field_definition->getFieldStorageDefinition()->getName() === 'name');
  }

}
