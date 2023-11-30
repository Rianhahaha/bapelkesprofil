<?php

namespace Drupal\animatecss_ui\Form;

use Drupal\animatecss_ui\AnimateCssManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Animatecss add and edit animate form.
 *
 * @internal
 */
class AnimateCssForm extends FormBase {

  /**
   * Animate manager.
   *
   * @var \Drupal\animatecss_ui\AnimateCssManagerInterface
   */
  protected $animateManager;

  /**
   * A config object for the system performance configuration.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Constructs a new Animate object.
   *
   * @param \Drupal\animatecss_ui\AnimateCssManagerInterface $animate_manager
   *   The Animate selector manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   A config factory for retrieving required config objects.
   */
  public function __construct(AnimateCssManagerInterface $animate_manager, ConfigFactoryInterface $config_factory) {
    $this->animateManager = $animate_manager;
    $this->config = $config_factory->get('animatecss.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('animatecss.animate_manager'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'animatecss_form';
  }

  /**
   * {@inheritdoc}
   *
   * @param array $form
   *   A nested array form elements comprising the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param string $animate
   *   (optional) Animate id to be passed on to
   *   \Drupal::formBuilder()->getForm() for use as the default value of the
   *   Animate ID form data.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $animate = '') {
    $animate_id = 0;

    if (is_numeric($animate) && is_array($this->animateManager->findById($animate))) {
      $animate_id = $animate;
      $animate    = $this->animateManager->findById($animate_id);
      $selector   = $animate['selector'] ?? '';
      $label      = $animate['label'] ?? '';
      $comment    = $animate['comment'] ?? '';
      $options    = unserialize($animate['options'], ['allowed_classes' => FALSE]) ?? '';
    }

    $form['animate_id'] = [
      '#type'  => 'value',
      '#value' => $animate_id,
    ];

    // Load the AnimateCSS configuration settings.
    $config = $this->config;

    // The default selector to use when detecting multiple texts to animate.
    $form['selector'] = [
      '#title'         => $this->t('Selector'),
      '#type'          => 'textfield',
      '#required'      => TRUE,
      '#size'          => 64,
      '#maxlength'     => 256,
      '#default_value' => $selector,
      '#description'   => $this->t('Enter a valid element or a css selector.'),
    ];

    // The label of this selector.
    $form['label'] = [
      '#title'         => $this->t('Label'),
      '#type'          => 'textfield',
      '#required'      => FALSE,
      '#size'          => 32,
      '#maxlength'     => 32,
      '#default_value' => $label ?? '',
      '#description'   => $this->t('The label for this animate selector like <em>About block title</em>.'),
    ];

    // AnimateCSS utilities,
    // Animate.css comes packed with a few utility classes to simplify its use.
    $form['options'] = [
      '#title' => $this->t('Animate options'),
      '#type'  => 'details',
      '#open'  => TRUE,
    ];

    // The animation to use.
    $form['options']['animation'] = [
      '#title'         => $this->t('Animation'),
      '#type'          => 'select',
      '#options'       => animatecss_ui_animation_options(),
      '#default_value' => $options['animation'] ?? $config->get('options.animation'),
      '#description'   => $this->t('Select the animation name you want to use for CSS selectors globally.'),
    ];

    // Animate.css provides the following delays.
    $form['options']['delay'] = [
      '#title'         => $this->t('Delay'),
      '#type'          => 'select',
      '#options'       => animatecss_ui_delay_options(),
      '#default_value' => $options['delay'] ?? $config->get('options.delay'),
      '#description'   => $this->t('The provided delays are from 1 to 5 seconds. You can customize them by selecting custom.'),
    ];

    // Animate duration used as a prefix on CSS Variables.
    $form['options']['time'] = [
      '#title'         => $this->t('Delay time'),
      '#type'          => 'textfield',
      '#size'          => 6,
      '#maxlength'     => 6,
      '#default_value' => $options['duration'] ?? $config->get('options.duration'),
      '#description'   => $this->t('Set the delay time directly on the elements to delay the start of the animation.'),
      '#states'        => [
        'visible' => [
          'select[name="delay"]' => ['value' => 'custom'],
        ],
      ],
    ];

    // Animate speed time.
    $form['options']['speed'] = [
      '#title'         => $this->t('Speed'),
      '#type'          => 'select',
      '#options'       => animatecss_ui_speed_options(),
      '#default_value' => $options['speed'] ?? $config->get('options.speed'),
      '#description'   => $this->t('You can control the speed of the animation. The medium option speed is 1 second same as a default speed. By selecting customize, You can also set the animations duration through the selecting customize option.'),
    ];

    // Animate duration used as a prefix on CSS Variables.
    $form['options']['duration'] = [
      '#title'         => $this->t('Duration'),
      '#type'          => 'textfield',
      '#size'          => 15,
      '#maxlength'     => 15,
      '#default_value' => $options['duration'] ?? $config->get('options.duration'),
      '#description'   => $this->t('Setting the duration through the --animation-duration property will respect these ratios. So, when you change the global duration, all the animations will respond to that change!'),
      '#states'        => [
        'visible' => [
          'select[name="speed"]' => ['value' => 'custom'],
        ],
      ],
    ];

    // Animate iteration count.
    $form['options']['repeat'] = [
      '#title'         => $this->t('Repeating'),
      '#type'          => 'select',
      '#options'       => animatecss_ui_repeat_options(),
      '#default_value' => $options['repeat'] ?? $config->get('options.repeat'),
      '#description'   => $this->t('You can control the iteration count of the animation.'),
      '#states'        => [
        'visible' => [
          ':input[name="infinite"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Animate.css preview.
    $form['preview'] = [
      '#type'   => 'details',
      '#title'  => $this->t('Animate preview'),
      '#open'   => TRUE,
    ];

    // Animate.css animation preview and replay.
    $form['preview']['sample'] = [
      '#type'   => 'markup',
      '#markup' => '<div class="animate__preview"><p class="animate__sample">An animated element!</p></div>',
    ];
    $form['preview']['replay'] = [
      '#value'      => $this->t('Replay'),
      '#type'       => 'button',
      '#attributes' => ['class' => ['animate__replay']],
    ];

    // Animate.css animation preview.
    $form['preview']['sample'] = [
      '#type'   => 'markup',
      '#markup' => '<div class="animate__preview"><p class="animate__sample animate__animated">An animated element!</p></div>',
    ];

    // The comment for describe animate settings and usage in website.
    $form['comment'] = [
      '#type'          => 'textarea',
      '#title'         => $this->t('Comment'),
      '#default_value' => $comment ?? '',
      '#description'   => $this->t('Describe this animate settings and usage in your website.'),
      '#rows'          => 3,
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type'        => 'submit',
      '#value'       => $this->t('Save'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $aid      = $form_state->getValue('animate_id');
    $is_new   = $aid == 0;
    $selector = trim($form_state->getValue('selector'));

    if ($is_new) {
      if ($this->animateManager->isAnimate($selector)) {
        $form_state->setErrorByName('selector', $this->t('This selector is already exists.'));
      }
    }
    else {
      if ($this->animateManager->findById($aid)) {
        $animate = $this->animateManager->findById($aid);

        if ($selector != $animate['selector'] && $this->animateManager->isAnimate($selector)) {
          $form_state->setErrorByName('selector', $this->t('This selector is already added.'));
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values    = $form_state->getValues();
    $variables = [];

    $aid      = $values['animate_id'];
    $label    = trim($values['label']);
    $selector = trim($values['selector']);
    $comment  = trim($values['comment']);

    if (empty($label)) {
      $label = ucfirst(trim(preg_replace("/[^a-zA-Z0-9]+/", " ", $selector)));
    }

    $variables['animation'] = $values['animation'];
    $variables['delay']     = $values['delay'];
    $variables['time']      = $values['time'];
    $variables['speed']     = $values['speed'];
    $variables['duration']  = $values['duration'];
    $variables['repeat']    = $values['repeat'];

    $options = serialize($variables);

    $this->animateManager->addAnimate($aid, $selector, $label, $comment, $options);
    $this->messenger()
      ->addStatus($this->t('The selector %selector has been added.', ['%selector' => $selector]));

    // Flush caches so the updated config can be checked.
    drupal_flush_all_caches();

    $form_state->setRedirect('animatecss.admin');
  }

}
