<?php

namespace Drupal\diba_carousel\Plugin\Block;

use Drupal\Component\Utility\Html;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\SubformStateInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Image\ImageFactory;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Component\Utility\Unicode;
use Drupal\image\Entity\ImageStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Diba carousel Block.
 *
 * @Block(
 *   id = "diba_carousel",
 *   admin_label = @Translation("Diba carousel"),
 *   category = @Translation("Content")
 * )
 */
class DibaCarousel extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * The image factory.
   *
   * @var \Drupal\Core\Image\ImageFactory
   */
  protected $imageFactory;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity repository.
   *
   * @var \Drupal\Core\Entity\EntityRepositoryInterface
   */
  protected $entityRepository;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    return [
      '#theme' => 'block__diba_carousel',
      'items'  => $this->getItems($config),
      'id'     => Html::getUniqueId('diba_carousel'),
      'config' => $config,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(
      parent::getCacheContexts(),
      ['user', 'languages:language_interface', 'url']
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(
      parent::getCacheTags(),
      ['node_list', 'config:block.block.diba_carousel']
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'label_display'              => FALSE,
      'entity_selected'            => 'node',
      'content_types'              => [],
      'publishing_options'         => ['status' => 1],
      'skip_content_without_image' => FALSE,
      'image'                      => 'field_image',
      'title'                      => 'title',
      'image_style'                => '',
      'image_multi_strategy'       => 'first',
      'url'                        => 'canonical',
      'url_image'                  => '',
      'description'                => 'body',
      'description_allow_html'     => FALSE,
      'description_see_more_link'  => FALSE,
      'description_truncate'       => 300,
      'order_field'                => 'created',
      'order_direction'            => 'DESC',
      'limit'                      => 5,
      'filter_by_field'            => '',
      'filter_by_field_operator'   => '=',
      'filter_by_field_value'      => '',
      'carousel_style'             => 'default',
      'show_indicators'            => TRUE,
      'show_controls'              => TRUE,
      'more_link'                  => '',
      'more_link_text'             => 'See more',
      'items_by_slide'             => 1,
      'data_interval'              => 5000,
      'image_class'                => 'img-fluid',
      'row_class'                  => '',
      'col_class'                  => '',
      'title_class'                => '',
      'description_class'          => '',
      'wrapper_class'              => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();
    $defaults = $this->defaultConfiguration();
    // If this method receives a subform state instead of the full form state.
    // @See https://www.drupal.org/node/2798261
    if ($form_state instanceof SubformStateInterface) {
      $ajax_values = $form_state->getCompleteFormState()->getValues();
    }
    else {
      $ajax_values = $form_state->getValues();
    }

    // Ensure strongly that entity type is not null.
    if (isset($ajax_values['settings']['diba_carousel_settings']['content_selection']['entity_selected'])) {
      $config['entity_selected'] = $ajax_values['settings']['diba_carousel_settings']['content_selection']['entity_selected'];
    }
    if (empty($config['entity_selected'])) {
      $config['entity_selected'] = $defaults['entity_selected'];
    }

    // If all is empty ensures some field is rendered in slides.
    if (empty($config['image']) && empty($config['title']) && empty($config['description'])) {
      $config['title'] = $defaults['title'];
    }

    // Settings structure.
    $form['diba_carousel_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Diba carousel configuration'),
      '#description' => $this->t('Configure the diba carousel slider.'),
      '#attributes' => ['id' => 'diba-carousel-wrapper'],
    ];
    $form['diba_carousel_settings']['content_selection'] = [
      '#type' => 'details',
      '#title' => $this->t('Content selection and ordering'),
      '#description' => $this->t('Filter, limit and sort the contents shown on slides.'),
      '#open' => TRUE,
      '#attributes' => ['id' => 'content-selection-wrapper'],
    ];
    $form['diba_carousel_settings']['slide_fields'] = [
      '#type' => 'details',
      '#title' => $this->t('Slide fields'),
      '#description' => $this->t('Assign the fields used as image, title, description and link on slides.'),
      '#open' => TRUE,
      '#attributes' => ['id' => 'slide-fields-wrapper'],
    ];
    $form['diba_carousel_settings']['styling'] = [
      '#type' => 'details',
      '#title' => $this->t('Carousel styling'),
      '#description' => $this->t('Carousel style, indicators and controls.'),
      '#open' => TRUE,
      '#attributes' => ['id' => 'styling-wrapper'],
    ];
    $form['diba_carousel_settings']['styling']['classes'] = [
      '#type' => 'details',
      '#title' => $this->t('Class attributes'),
      '#description' => $this->t('HTML output class attributes.'),
      '#open' => FALSE,
      '#attributes' => ['id' => 'classes-wrapper'],
    ];

    // Content selection.
    $form['diba_carousel_settings']['content_selection']['entity_selected'] = [
      '#type' => 'select',
      '#title' => $this->t('Entity type'),
      '#default_value' => $config['entity_selected'],
      '#options' => $this->getEntityTypes(),
      '#description' => $this->t('Check the entity that you want to use in the carousel. Default: node.'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => [$this, 'ajaxFormSettingsCallback'],
        'wrapper'  => 'diba-carousel-wrapper',
        'event'    => 'change',
      ],
    ];

    $bundles = $this->getEntityTypeBundles($config['entity_selected']);
    if (!empty($bundles)) {
      $form['diba_carousel_settings']['content_selection']['content_types'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('Entity type bundles'),
        '#default_value' => !empty($config['content_types']) ? $config['content_types'] : [],
        '#options' => $bundles,
        '#description' => $this->t('Check the node content types that you want to filter in the carousel. If you uncheck all options all types will be displayed.'),
        '#attributes' => ['class' => ['container-inline']],
        '#validated' => TRUE,
      ];
    }

    $options = [
      'status' => $this->t('Published'),
      'promote' => $this->t('Promoted to front page'),
      'sticky' => $this->t('Sticky at top of lists'),
    ];
    if ('node' === $config['entity_selected']) {
      // Add custom publishing options (custom_pub module integration).
      if ($this->moduleHandler->moduleExists('custom_pub')) {
        $publish_types = $this->entityTypeManager
          ->getStorage('custom_publishing_option')
          ->loadMultiple();
        foreach ($publish_types as $publish_type) {
          $options[$publish_type->id()] = $publish_type->label();
        }
      }
    }
    $form['diba_carousel_settings']['content_selection']['publishing_options'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Publishing options'),
      '#default_value' => !empty($config['publishing_options']) ? $config['publishing_options'] : [],
      '#options' => $options,
      '#description' => $this->t('Publishing options to filter content in the carousel.'),
      '#attributes' => ['class' => ['container-inline']],
      '#validated' => TRUE,
    ];

    $form['diba_carousel_settings']['content_selection']['skip_content_without_image'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Skip content without image'),
      '#default_value' => $config['skip_content_without_image'],
      '#description' => $this->t('Ensure that all carousel content has image.'),
    ];

    $form['diba_carousel_settings']['content_selection']['filter_by_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Filter by field'),
      '#options' => $this->getFields($config['entity_selected'], FALSE),
      '#default_value' => $config['filter_by_field'],
      '#description' => $this->t('Select the field you want to use as filter.'),
      '#empty_option' => $this->t('- None -'),
    ];

    $form['diba_carousel_settings']['content_selection']['filter_by_field_operator'] = [
      '#type' => 'select',
      '#title' => $this->t('Filter by field operator'),
      '#options' => [
        'Strings' => [
          '=' => $this->t('Equal'),
          '<>' => $this->t('Not equal'),
          'CONTAINS' => $this->t('Contains'),
          '>' => $this->t('Greater'),
          '>=' => $this->t('Greater or equal'),
          '<' => $this->t('Lower'),
          '<=' => $this->t('Lower or equal'),
        ],
        'Dates' => [
          'date_g' => $this->t('Greater'),
          'date_ge' => $this->t('Greater or equal'),
          'date_l' => $this->t('Lower'),
          'date_le' => $this->t('Lower or equal'),
        ],
      ],
      '#default_value' => $config['filter_by_field_operator'],
      '#description' => $this->t('Select the comparasion operator to use in filter field. Date operators cast string values to date.'),
      '#states' => [
        'visible' => [
          ':input[name="settings[diba_carousel_settings][content_selection][filter_by_field]"]' => ['!value' => ''],
        ],
      ],
    ];

    $form['diba_carousel_settings']['content_selection']['filter_by_field_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Filter by field value'),
      '#default_value' => $config['filter_by_field_value'],
      '#description' => $this->t('Select the value you want to use as field filter. If you filter by a field that contains taxonomy terms or relational content you should use the tid or entity id as a value. If you use "Dates" operators, values are cast to dates and can use values like: "-1 day", "yesterday" or "+1 week". You can also use tokens to use url params as a filter value; For GET params like example.com?arg=23 use [query:arg]; For url arguments like example.com/arg0/arg1 use [argument:1] where 1 is the argument number starting by 0.'),
      '#states' => [
        'visible' => [
          ':input[name="settings[diba_carousel_settings][content_selection][filter_by_field]"]' => ['!value' => ''],
        ],
      ],
    ];

    $option_types = ['integer', 'created', 'changed', 'datetime', 'string'];
    $form['diba_carousel_settings']['content_selection']['order_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Order by'),
      '#options' => $this->getFieldsByType($option_types, $config['entity_selected']),
      '#default_value' => $config['order_field'],
      '#empty_option' => $this->t('- None -'),
      '#validated' => TRUE,
    ];

    $form['diba_carousel_settings']['content_selection']['order_direction'] = [
      '#type' => 'select',
      '#title' => $this->t('Order direction'),
      '#options' => [
        'ASC' => $this->t('Ascending'),
        'DESC' => $this->t('Descending'),
        'RANDOM' => $this->t('Random'),
      ],
      '#default_value' => $config['order_direction'],
      '#states' => [
        'visible' => [
          ':input[name="settings[diba_carousel_settings][content_selection][order_field]"]' => ['!value' => ''],
        ],
      ],
    ];

    $form['diba_carousel_settings']['content_selection']['limit'] = [
      '#type' => 'number',
      '#title' => $this->t('Maximum number of elements'),
      '#default_value' => $config['limit'],
      '#description' => $this->t('The maximum number of elements to show in the carousel.'),
    ];

    // Slide fields.
    $form['diba_carousel_settings']['slide_fields']['image'] = [
      '#type' => 'select',
      '#title' => $this->t('Image field'),
      '#options' => $this->getFieldsByType(['image'], $config['entity_selected']),
      '#default_value' => $config['image'],
      '#empty_option' => $this->t('- None -'),
      '#validated' => TRUE,
    ];

    $form['diba_carousel_settings']['slide_fields']['image_multi_strategy'] = [
      '#type' => 'select',
      '#title' => $this->t('Multiple images strategy'),
      '#options' => [
        'first' => $this->t('Get first image'),
        'last'  => $this->t('Get last image'),
        'rand'  => $this->t('Get random image'),
        'all'   => $this->t('Get all images and split in different slides'),
      ],
      '#default_value' => $config['image_multi_strategy'],
      '#description' => $this->t('Strategy when we have multiple images in one entity'),
      '#states' => [
        'visible' => [
          ':input[name="settings[diba_carousel_settings][slide_fields][image]"]' => ['!value' => ''],
        ],
      ],
    ];

    $form['diba_carousel_settings']['slide_fields']['image_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Image style'),
      '#options' => $this->getImageStyles(),
      '#default_value' => $config['image_style'],
      '#empty_option' => $this->t('- None -'),
      '#description' => $this->t('Use an image style for scale, resize or crop images.'),
      '#states' => [
        'visible' => [
          ':input[name="settings[diba_carousel_settings][slide_fields][image]"]' => ['!value' => ''],
        ],
      ],
    ];

    $url_options = [
      'canonical'  => $this->t('Link to entity content'),
      'image_file' => $this->t('Link to image file'),
    ];
    $url_options = array_merge($url_options, $this->getFieldsByType(['link'], $config['entity_selected']));
    $form['diba_carousel_settings']['slide_fields']['url_image'] = [
      '#type' => 'select',
      '#title' => $this->t('Image link'),
      '#options' => $url_options,
      '#default_value' => $config['url_image'],
      '#empty_option' => $this->t('- None -'),
      '#validated' => TRUE,
    ];

    $form['diba_carousel_settings']['slide_fields']['title'] = [
      '#type' => 'select',
      '#title' => $this->t('Title field'),
      '#options' => $this->getFieldsByType(['string'], $config['entity_selected']),
      '#default_value' => $config['title'],
      '#empty_option' => $this->t('- None -'),
      '#validated' => TRUE,
    ];

    $form['diba_carousel_settings']['slide_fields']['url'] = [
      '#type' => 'select',
      '#title' => $this->t('Title link'),
      '#options' => $url_options,
      '#default_value' => $config['url'],
      '#empty_option' => $this->t('- None -'),
      '#validated' => TRUE,
    ];

    $option_types = [
      'text',
      'text_long',
      'text_with_summary',
      'string',
      'string_long',
      'entity_reference',
    ];
    $form['diba_carousel_settings']['slide_fields']['description'] = [
      '#type' => 'select',
      '#title' => $this->t('Description field'),
      '#options' => $this->getFieldsByType($option_types, $config['entity_selected']),
      '#default_value' => $config['description'],
      '#empty_option' => $this->t('- None -'),
      '#validated' => TRUE,
    ];

    $form['diba_carousel_settings']['slide_fields']['description_allow_html'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow html description'),
      '#default_value' => $config['description_allow_html'],
      '#description' => $this->t('If you use html description and truncate, the carousel will attempt to close the open tags.'),
      '#states' => [
        'visible' => [
          ':input[name="settings[diba_carousel_settings][slide_fields][description]"]' => ['!value' => ''],
        ],
      ],
    ];

    $form['diba_carousel_settings']['slide_fields']['description_see_more_link'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Add see more link'),
      '#default_value' => $config['description_see_more_link'],
      '#description' => $this->t('Add "See more" link at the end of description linking canonical entity.'),
      '#states' => [
        'visible' => [
          ':input[name="settings[diba_carousel_settings][slide_fields][description]"]' => ['!value' => ''],
        ],
      ],
    ];

    $form['diba_carousel_settings']['slide_fields']['description_truncate'] = [
      '#type' => 'number',
      '#title' => $this->t('Maximum number of characters in description field'),
      '#default_value' => $config['description_truncate'],
      '#description' => $this->t('Truncates the description to a maximum number of characters. Truncation attempts to truncate on a word boundary. Use 0 for unlimited.'),
      '#states' => [
        'visible' => [
          ':input[name="settings[diba_carousel_settings][slide_fields][description]"]' => ['!value' => ''],
        ],
      ],
    ];

    $form['diba_carousel_settings']['slide_fields']['more_link'] = [
      '#type' => 'url',
      '#title' => $this->t('More link'),
      '#default_value' => $config['more_link'],
      '#description' => $this->t('This will add a more link to the bottom of the carousel.'),
    ];

    $form['diba_carousel_settings']['slide_fields']['more_link_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('More link text'),
      '#default_value' => $config['more_link_text'],
      '#description' => $this->t('More link text.'),
      '#states' => [
        'visible' => [
          ':input[name="settings[diba_carousel_settings][slide_fields][more_link]"]' => ['filled' => TRUE],
        ],
      ],
    ];

    // Slide styling.
    $form['diba_carousel_settings']['styling']['carousel_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Carousel layout style'),
      '#options' => [
        'default' => $this->t('Bootstrap default'),
        'diba' => $this->t('Diba left captations'),
      ],
      '#default_value' => $config['carousel_style'],
      '#description' => $this->t('The carousel style controls de visualization and carousel templating.'),
      '#required' => TRUE,
    ];

    $form['diba_carousel_settings']['styling']['show_indicators'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show indicators'),
      '#default_value' => $config['show_indicators'],
      '#description' => $this->t('Show carousel indicators as squares or bullets.'),
    ];

    $form['diba_carousel_settings']['styling']['show_controls'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show controls'),
      '#default_value' => $config['show_controls'],
      '#description' => $this->t('Show previous/next controls.'),
    ];

    $form['diba_carousel_settings']['styling']['items_by_slide'] = [
      '#type' => 'select',
      '#title' => $this->t('Items by slide'),
      '#default_value' => (int) $config['items_by_slide'],
      '#options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6, 12 => 12],
      '#description' => $this->t('The number of items by slide.'),
      '#required' => TRUE,
    ];

    $form['diba_carousel_settings']['styling']['data_interval'] = [
      '#type' => 'number',
      '#title' => $this->t('Slides interval'),
      '#default_value' => (int) $config['data_interval'],
      '#min' => 0,
      '#description' => $this->t('The amount of time (in ms) to delay between automatically cycling an item. If 0, carousel will not automatically cycle.'),
    ];

    // HTML Classes.
    $form['diba_carousel_settings']['styling']['classes']['wrapper_class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Carousel wrapper class'),
      '#default_value' => $config['wrapper_class'],
      '#description' => $this->t('Wrapper class atttibutes like "d-none d-sm-block" to hide carousel in small displays.'),
    ];
    $form['diba_carousel_settings']['styling']['classes']['image_class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Image class'),
      '#default_value' => $config['image_class'],
      '#description' => $this->t('Image class attributes like "rounded". By default: img-fluid'),
    ];
    $form['diba_carousel_settings']['styling']['classes']['row_class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Row class'),
      '#default_value' => $config['row_class'],
      '#description' => $this->t('Row class attributes like "no-gutters".'),
    ];
    $form['diba_carousel_settings']['styling']['classes']['col_class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Col class'),
      '#default_value' => $config['col_class'],
      '#description' => $this->t('Col class atttibutes.'),
    ];
    $form['diba_carousel_settings']['styling']['classes']['title_class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title class'),
      '#default_value' => $config['title_class'],
      '#description' => $this->t('Title class atttibutes.'),
    ];
    $form['diba_carousel_settings']['styling']['classes']['description_class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description class'),
      '#default_value' => $config['description_class'],
      '#description' => $this->t('Description class atttibutes like "d-none d-md-block" to hide description in small displays.'),
    ];

    return $form;
  }

  /**
   * Ajax callback to update diba carousel settings.
   */
  public function ajaxFormSettingsCallback(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);
    return $form['settings']['diba_carousel_settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Set the new configuration.
    $config = $form_state->getValues();
    if (isset($config['diba_carousel_settings'])) {
      $this->setConfigurationValue('entity_selected', $config['diba_carousel_settings']['content_selection']['entity_selected']);
      $this->setConfigurationValue('content_types', $config['diba_carousel_settings']['content_selection']['content_types']);
      $this->setConfigurationValue('publishing_options', $config['diba_carousel_settings']['content_selection']['publishing_options']);
      $this->setConfigurationValue('skip_content_without_image', $config['diba_carousel_settings']['content_selection']['skip_content_without_image']);
      $this->setConfigurationValue('order_field', $config['diba_carousel_settings']['content_selection']['order_field']);
      $this->setConfigurationValue('order_direction', $config['diba_carousel_settings']['content_selection']['order_direction']);
      $this->setConfigurationValue('limit', $config['diba_carousel_settings']['content_selection']['limit']);
      $this->setConfigurationValue('filter_by_field', $config['diba_carousel_settings']['content_selection']['filter_by_field']);
      $this->setConfigurationValue('filter_by_field_operator', $config['diba_carousel_settings']['content_selection']['filter_by_field_operator']);
      $this->setConfigurationValue('filter_by_field_value', $config['diba_carousel_settings']['content_selection']['filter_by_field_value']);

      $this->setConfigurationValue('image', $config['diba_carousel_settings']['slide_fields']['image']);
      $this->setConfigurationValue('image_multi_strategy', $config['diba_carousel_settings']['slide_fields']['image_multi_strategy']);
      $this->setConfigurationValue('image_style', $config['diba_carousel_settings']['slide_fields']['image_style']);
      $this->setConfigurationValue('title', $config['diba_carousel_settings']['slide_fields']['title']);
      $this->setConfigurationValue('url', $config['diba_carousel_settings']['slide_fields']['url']);
      $this->setConfigurationValue('url_image', $config['diba_carousel_settings']['slide_fields']['url_image']);
      $this->setConfigurationValue('description', $config['diba_carousel_settings']['slide_fields']['description']);
      $this->setConfigurationValue('description_allow_html', $config['diba_carousel_settings']['slide_fields']['description_allow_html']);
      $this->setConfigurationValue('description_see_more_link', $config['diba_carousel_settings']['slide_fields']['description_see_more_link']);
      $this->setConfigurationValue('description_truncate', $config['diba_carousel_settings']['slide_fields']['description_truncate']);
      $this->setConfigurationValue('more_link', $config['diba_carousel_settings']['slide_fields']['more_link']);
      $this->setConfigurationValue('more_link_text', $config['diba_carousel_settings']['slide_fields']['more_link_text']);

      $this->setConfigurationValue('carousel_style', $config['diba_carousel_settings']['styling']['carousel_style']);
      $this->setConfigurationValue('show_indicators', $config['diba_carousel_settings']['styling']['show_indicators']);
      $this->setConfigurationValue('show_controls', $config['diba_carousel_settings']['styling']['show_controls']);
      $this->setConfigurationValue('items_by_slide', $config['diba_carousel_settings']['styling']['items_by_slide']);
      $this->setConfigurationValue('data_interval', $config['diba_carousel_settings']['styling']['data_interval']);

      $this->setConfigurationValue('wrapper_class', $config['diba_carousel_settings']['styling']['classes']['wrapper_class']);
      $this->setConfigurationValue('image_class', $config['diba_carousel_settings']['styling']['classes']['image_class']);
      $this->setConfigurationValue('row_class', $config['diba_carousel_settings']['styling']['classes']['row_class']);
      $this->setConfigurationValue('col_class', $config['diba_carousel_settings']['styling']['classes']['col_class']);
      $this->setConfigurationValue('title_class', $config['diba_carousel_settings']['styling']['classes']['title_class']);
      $this->setConfigurationValue('description_class', $config['diba_carousel_settings']['styling']['classes']['description_class']);

      // Force diba_carousel block cache reload and refresh block output.
      Cache::invalidateTags(['config:block.block.diba_carousel']);
    }
  }

  /**
   * Get entity types list.
   */
  private function getEntityTypes() {
    $entities = [];
    $entity_definitions = $this->entityTypeManager->getDefinitions();
    if (!empty($entity_definitions)) {
      foreach ($entity_definitions as $definition) {
        // Ensure that the entity type is fieldable.
        if ($definition instanceof ContentEntityType && $definition->get('field_ui_base_route')) {
          $entities[$definition->id()] = $definition->id();
        }
      }
    }

    return $entities;
  }

  /**
   * Get fields grouped by node type list.
   */
  private function getFields($entity_type = 'node', $grouped = TRUE) {
    $fields = $this->entityFieldManager->getFieldStorageDefinitions($entity_type);
    $options = [];
    foreach ($fields as $field) {
      $label = $field->getLabel();
      $name  = $field->getName();
      $type  = $field->getType();

      if ($grouped) {
        $options[$type][$name] = $label . ' (' . $name . ')';
      }
      else {
        $options[$name] = $label . ' (' . $name . ')';
      }
    }

    return $options;
  }

  /**
   * Get image styles list.
   */
  private function getImageStyles() {
    $styles = $this->entityTypeManager->getStorage('image_style')->loadMultiple();
    $options = [];
    foreach ($styles as $key => $style) {
      $options[$key] = $key;
    }

    return $options;
  }

  /**
   * Get fields filtered by type.
   */
  private function getFieldsByType($types, $entity = 'node') {
    $fields = $this->getFields($entity);

    $options = [];
    foreach ($types as $type) {
      if (isset($fields[$type])) {
        $options = array_merge($options, $fields[$type]);
      }
    }

    return $options;
  }

  /**
   * Get all entity type bundles.
   */
  private function getEntityTypeBundles($entity) {
    $options = [];

    $entity_type = $this->entityTypeManager->getDefinition($entity)->getBundleEntityType();
    if (!empty($entity_type)) {
      $entity_type_bundles = $this->entityTypeManager->getStorage($entity_type)->loadMultiple();
      if (!empty($entity_type_bundles)) {
        foreach ($entity_type_bundles as $entity_type_bundle) {
          $options[$entity_type_bundle->id()] = $entity_type_bundle->label();
        }
      }
    }

    return $options;
  }

  /**
   * Use the block settings to query entities.
   */
  private function getQueriedEntities($config) {
    $entities = [];

    $entity_type = !empty($config['entity_selected']) ? $config['entity_selected'] : 'node';
    // Get the storage and init the query builder.
    $storage = $this->entityTypeManager->getStorage($entity_type);
    $query = $storage->getQuery();

    // Filter by content types in nodes.
    if (!empty($config['content_types'])) {
      // Validate that the entity type bundle exists to prevent crash the site
      // when user deletes a content types.
      $bundles = $this->getValidBundles($config['content_types'], $config['entity_selected']);
      $entity_keys = $this->entityTypeManager->getDefinition($entity_type)->get('entity_keys');
      if (!empty($bundles) && isset($entity_keys['bundle'])) {
        $query->condition($entity_keys['bundle'], array_values($bundles), 'IN');
      }
    }

    // Skip content without image.
    if (isset($config['skip_content_without_image']) && TRUE === $config['skip_content_without_image']) {
      if (!empty($config['image'])) {
        $query->condition($config['image'], NULL, 'IS NOT NULL');
      }
    }

    // Filter by publishing options.
    if (!empty($config['publishing_options'])) {
      foreach ($config['publishing_options'] as $key => $value) {
        if (!empty($value)) {
          // Add query condition and filter by publishing option.
          $query->condition($key, 1);
        }
      }
    }

    // Filter by field value.
    if (!empty($config['filter_by_field']) && isset($config['filter_by_field_value'])) {
      $operator = !empty($config['filter_by_field_operator']) ? $config['filter_by_field_operator'] : '=';

      // Use query params as a filter value.
      preg_match('#\[(.*?)\]#', $config['filter_by_field_value'], $match);
      if (!empty($match[1])) {
        $params = explode(':', $match[1]);
        $token_type  = $params[0];
        $token_value = isset($params[1]) ? $params[1] : 0;

        if ('query' === $token_type) {
          $value = \Drupal::request()->query->get($token_value);
          // Replace token [query:param] with query value.
          $config['filter_by_field_value'] = str_replace($match[0], $value, $config['filter_by_field_value']);
        }
        elseif ('argument' === $token_type) {
          $current_path = substr(\Drupal::service('path.current')->getPath(), 1);
          $path_args = explode('/', $current_path);
          // Replace token [argument:num] with url value.
          if (isset($path_args[$token_value])) {
            $config['filter_by_field_value'] = str_replace($match[0], $path_args[$token_value], $config['filter_by_field_value']);
          }
        }
      }

      // Cast string dates to time.
      if (strpos($operator, 'date_') !== FALSE) {
        $config['filter_by_field_value'] = strtotime($config['filter_by_field_value']);
        $replace = [
          'date_g'  => '>',
          'date_ge' => '>=',
          'date_l'  => '<',
          'date_le' => '<=',
        ];
        $operator = str_replace(array_keys($replace), array_values($replace), $operator);
      }

      $query->condition($config['filter_by_field'], $config['filter_by_field_value'], $operator);
    }

    // We don't need an order field to order by rand.
    if (isset($config['order_direction']) && 'RANDOM' === $config['order_direction']) {
      $query->addTag('random_order');
    }
    elseif (!empty($config['order_field'])) {
      if ('ASC' === $config['order_direction'] || 'DESC' === $config['order_direction']) {
        $query->sort($config['order_field'], $config['order_direction']);
      }
      else {
        $query->sort($config['order_field']);
      }
    }

    // Limit the query.
    if (!empty($config['limit'])) {
      $query->range(0, $config['limit']);
    }

    // Execute the query and get the IDs.
    $entity_ids = $query->accessCheck()->execute();

    if (!empty($entity_ids)) {
      $entities = $storage->loadMultiple($entity_ids);
    }

    return $entities;
  }

  /**
   * Get valid $bundles to be used in a query.
   */
  private function getValidBundles($bundles, $entity) {
    $entityBundles = $this->getEntityTypeBundles($entity);
    $valid_bundles = [];
    foreach ($bundles as $key => $value) {
      if (!empty($value) && isset($entityBundles[$key])) {
        $valid_bundles[] = $key;
      }
    }

    return $valid_bundles;
  }

  /**
   * Return slide fields from entity.
   */
  private function composeSlide($config, $entity, $image_num = 0) {
    // Slide title.
    $title = '';
    if (!empty($config['title'])) {
      $title = strip_tags($entity->{$config['title']}->value);
    }

    // Slide captation.
    $description = '';
    if (!empty($config['description']) && isset($entity->{$config['description']})) {
      // Referenced entities field as description, use referenced entity label.
      if (!empty($entity->{$config['description']}->target_id)) {
        $referencedEntities = $entity->get($config['description'])->referencedEntities();
        $labels = [];
        foreach ($referencedEntities as $referencedEntity) {
          $labels[] = $referencedEntity->label();
        }
        $description = implode(', ', $labels);
      }
      // Value field as description.
      elseif (!empty($entity->{$config['description']}->value)) {
        $description = $entity->{$config['description']}->value;
      }

      if ($description) {
        // If not allow HTML strip tags and convert some html entities.
        if (!$config['description_allow_html']) {
          $description = str_replace('&nbsp;', ' ', strip_tags($description));
        }

        // Truncate captation in a safe mode.
        if (!empty($config['description_truncate']) && $config['description_truncate'] > 0) {
          $description = Unicode::truncate($description, $config['description_truncate'], TRUE, TRUE);
          if ($config['description_allow_html']) {
            $description = Html::normalize($description);
          }
        }
        // Trim spaces, tabs and other special chars.
        $description = trim($description);
        $description = filter_var($description, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);

        // Invalid utf-8 chars breaks big_pipe so force UTF-8.
        $description = Unicode::convertToUtf8($description, 'UTF-8');

        // Add see more link to entity.
        if ($config['description_see_more_link']) {
          // Ensure that entity has a canonical link.
          if ($see_more_target = $entity->toUrl('canonical')) {
            $description .= new FormattableMarkup(' <a href="@link" class="see-more-description">@label.</a>', [
              '@link'  => $see_more_target->toString(),
              '@label' => $this->t('See more'),
            ]);
          }
        }
      }
    }

    // Slide image.
    $image_width = $image_height = $image_uri = $image_original = '';
    if (!empty($config['image']) && isset($entity->{$config['image']})) {
      $image_obj = $entity->{$config['image']}->get($image_num);
      if (!empty($image_obj) && !empty($image_obj->entity)) {
        $image_uri = $image_obj->entity->getFileUri();
      }
      else {
        // Image not found, try the default image.
        $default_image = $entity->{$config['image']}->getSetting('default_image');
        if (!empty($default_image) && isset($default_image['uuid'])) {
          $default_entity = $this->entityRepository->loadEntityByUuid('file', $default_image['uuid']);
          if (!empty($default_entity)) {
            $image_uri = $default_entity->getFileUri();
          }
        }
      }

      $image_original = $image_uri;

      if (!empty($image_uri)) {
        if (!empty($config['image_style'])) {
          // Use an image style instead of the original file.
          $style = ImageStyle::load($config['image_style']);
          $image_derivative = $style->buildUri($image_uri);

          // Create derivative if necessary.
          if (!file_exists($image_derivative)) {
            $style->createDerivative($image_uri, $image_derivative);
          }
          $image_uri = $image_derivative;
        }

        $image = $this->imageFactory->get($image_uri);

        // Check if the image is valid.
        if ($image->isValid()) {
          $image_width = $image->getWidth();
          $image_height = $image->getHeight();
        }
      }
    }

    return [
      'image'        => $image_uri,
      'image_width'  => $image_width,
      'image_height' => $image_height,
      'title'        => $title,
      'url'          => $this->getSlideUrl($config, 'url', $image_original, $entity),
      'url_image'    => $this->getSlideUrl($config, 'url_image', $image_original, $entity),
      'description'  => $description,
    ];
  }

  /**
   * Get the carousel items.
   */
  private function getItems($config) {
    $items = [];

    $entities = $this->getQueriedEntities($config);
    if (!empty($entities)) {
      $langcode = $this->languageManager->getCurrentLanguage()->getId();
      foreach ($entities as $entity) {
        if ($entity->access('view')) {
          if ($entity->hasTranslation($langcode)) {
            $entity = $entity->getTranslation($langcode);
          }

          // Build one or more slides depending on the image strategy in case
          // of multivalued fields.
          $count_images = !empty($entity->{$config['image']}) ? count($entity->{$config['image']}) : 0;
          $config['image_multi_strategy'] = !empty($config['image_multi_strategy']) ? $config['image_multi_strategy'] : 'first';

          if ($count_images > 0 && 'first' !== $config['image_multi_strategy']) {
            if ('all' === $config['image_multi_strategy']) {
              // One new slides for every image.
              for ($i = 0; $i < $count_images; $i++) {
                $items[] = $this->composeSlide($config, $entity, $i);
              }
            }
            elseif ('last' === $config['image_multi_strategy']) {
              // One slide with last image.
              $items[] = $this->composeSlide($config, $entity, $count_images - 1);
            }
            elseif ('rand' === $config['image_multi_strategy']) {
              // One random image.
              $items[] = $this->composeSlide($config, $entity, random_int(0, $count_images - 1));
            }
          }
          else {
            // By default one slide with first image.
            $items[] = $this->composeSlide($config, $entity, 0);
          }
        }
      }
    }

    return $items;
  }

  /**
   * Get the slide url.
   */
  private function getSlideUrl($config, $config_key, $image_original, $entity) {
    $url = '';
    if (!empty($config[$config_key])) {
      if ('canonical' === $config[$config_key] || 'nid' === $config[$config_key]) {
        $url = $entity->toUrl('canonical');
      }
      elseif ('image_file' === $config[$config_key]) {
        $url = \Drupal::service('file_url_generator')->generateAbsoluteString($image_original);
      }
      elseif (isset($entity->{$config[$config_key]}) && !$entity->{$config[$config_key]}->isEmpty()) {
        $url = $entity->{$config[$config_key]}->first()->getUrl();
      }
    }

    return $url;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('module_handler'),
      $container->get('entity_field.manager'),
      $container->get('image.factory'),
      $container->get('entity_type.manager'),
      $container->get('entity.repository'),
      $container->get('language_manager')
    );
  }

  /**
   * Diba carousel constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The field manager.
   * @param \Drupal\Core\Image\ImageFactory $image_factory
   *   The image factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity manager.
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ModuleHandlerInterface $module_handler, EntityFieldManagerInterface $entity_field_manager, ImageFactory $image_factory, EntityTypeManagerInterface $entity_type_manager, EntityRepositoryInterface $entity_repository, LanguageManagerInterface $language_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->moduleHandler = $module_handler;
    $this->entityFieldManager = $entity_field_manager;
    $this->imageFactory = $image_factory;
    $this->entityTypeManager = $entity_type_manager;
    $this->entityRepository = $entity_repository;
    $this->languageManager = $language_manager;
  }

}
