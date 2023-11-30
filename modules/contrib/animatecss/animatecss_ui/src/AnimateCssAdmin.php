<?php

namespace Drupal\animatecss_ui;

use Drupal\animatecss_ui\Form\AnimateCssFilter;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Displays Animate CSS selector.
 *
 * @internal
 */
class AnimateCssAdmin extends FormBase {

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $currentRequest;

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Animate manager.
   *
   * @var \Drupal\animatecss_ui\AnimateCssManagerInterface
   */
  protected $animateManager;

  /**
   * Constructs a new Animate object.
   *
   * @param \Drupal\animatecss_ui\AnimateCssManagerInterface $animate_manager
   *   The Animate selector manager.
   * @param \Symfony\Component\HttpFoundation\Request $current_request
   *   The current request.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   */
  public function __construct(AnimateCssManagerInterface $animate_manager, Request $current_request, FormBuilderInterface $form_builder) {
    $this->animateManager = $animate_manager;
    $this->currentRequest = $current_request;
    $this->formBuilder    = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('animatecss.animate_manager'),
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('form_builder'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'animate_admin_form';
  }

  /**
   * {@inheritdoc}
   *
   * @param array $form
   *   A nested array form elements comprising the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param string $animate
   *   (optional) CSS Selector to be added to Animate.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $animate = '') {
    $keys = $this->currentRequest->query->get('search');

    /** @var \Drupal\animatecss_ui\Form\AnimateCssFilter $form */
    $form['animatecss_admin_filter_form'] = $this->formBuilder->getForm(AnimateCssFilter::class, $keys);

    $rows   = [];
    $header = [
      [
        'data'  => $this->t('Selector'),
        'field' => 'a.selector',
        'sort'  => 'desc',
      ],
      [
        'data'  => $this->t('Label'),
        'field' => 'a.label',
      ],
      $this->t('Operations'),
    ];
    $result = $this->animateManager->findAll($keys, $header);
    foreach ($result as $animate) {
      $row = [];
      $row[] = $animate->selector;
      $row[] = $animate->label;
      $links = [];
      $links['edit'] = [
        'title' => $this->t('Edit'),
        'url'   => Url::fromRoute('animatecss.edit', ['animate' => $animate->aid]),
      ];
      $links['delete'] = [
        'title' => $this->t('Delete'),
        'url'   => Url::fromRoute('animatecss.delete', ['animate' => $animate->aid]),
      ];
      $row[] = [
        'data' => [
          '#type'  => 'operations',
          '#links' => $links,
        ],
      ];
      $rows[] = $row;
    }

    $form['animatecss_admin_table'] = [
      '#type'   => 'table',
      '#header' => $header,
      '#rows'   => $rows,
      '#empty'  => $this->t('No animate CSS selector available. <a href=":link">Add animate</a> .', [
        ':link' => Url::fromRoute('animatecss.add')
          ->toString(),
      ]),
    ];

    $form['pager'] = ['#type' => 'pager'];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // @todo Add operations to animate CSS selector list
    /*$search = trim($form_state->getValue('filter'));
    $this->animateManager->findAll($search);
    $form_state->setRedirect('animatecss.admin');*/
  }

}
