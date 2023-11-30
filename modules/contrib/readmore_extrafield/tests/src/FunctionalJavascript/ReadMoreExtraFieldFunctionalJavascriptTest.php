<?php

namespace Drupal\Tests\readmore_extrafield\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Tests the readmore_extrafield javascript functionalities.
 *
 * @group readmore_extrafield
 */
class ReadMoreExtraFieldFunctionalJavascriptTest extends WebDriverTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'node',
    'field',
    'field_ui',
    'test_page_test',
    'readmore_extrafield',
    'layout_discovery',
    'layout_builder',
  ];

  /**
   * A user with admin permissions.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $adminUser;

  /**
   * A user with authenticated permissions.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $user;

  /**
   * A node.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;


  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->createContentType(['type' => 'article']);
    $this->node = $this->drupalCreateNode([
      'title' => 'VerySpecificTestTitle',
      'type' => 'article',
      'body' => 'Body field value.',
    ]);

    $this->config('system.site')->set('page.front', '/test-page')->save();

    $this->user = $this->drupalCreateUser([]);
    $this->adminUser = $this->drupalCreateUser([]);
    $this->adminUser->addRole($this->createAdminRole('admin', 'admin'));
    $this->adminUser->save();
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Test field displayed correctly.
   */
  public function testFieldOptionsEmptyFallback() {
    $session = $this->assertSession();
    /** @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface $display_repository */
    $display_repository = \Drupal::service('entity_display.repository');
    $display_repository->getViewDisplay('node', 'article')
      ->setComponent('extra_field_readmore_extrafield', [
        'type' => 'extra_field_readmore_extrafield',
        'settings' => [],
      ])
      ->enable()
      ->save();

    $this->drupalGet('/node/' . $this->node->id());

    // See if Read more button is correctly rendered on node display:
    $session->elementExists('css', 'a.read-more.button');
    $session->elementExists('css', 'div.readmore-extrafield > div.field__item > a.read-more.button');
    $session->elementTextEquals('css', 'a.read-more.button', 'Read more');
  }

  /**
   * Tests to see if the field behaves correctly inside layout builder.
   */
  public function testFieldInLayoutBuilder() {
    $session = $this->assertSession();
    $page = $this->getSession()->getPage();
    $driver = $this->getSession()->getDriver();
    /** @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface $display_repository */
    $display_repository = \Drupal::service('entity_display.repository');
    $display_repository->getViewDisplay('node', 'article')
      ->setComponent('extra_field_readmore_extrafield', [
        'type' => 'extra_field_readmore_extrafield',
        'settings' => [],
      ])
      ->enable()
      ->save();
    // Test if the field is present on the layout builder ui:
    $this->drupalGet('/admin/structure/types/manage/article/display');
    $page->checkField('edit-layout-enabled');
    $page->pressButton('edit-submit');
    $this->drupalGet('/admin/structure/types/manage/article/display/default/layout');
    $session->pageTextContainsOnce('Placeholder for the "Read more" field');

    $this->drupalGet('/layout_builder/choose/block/defaults/node.article.default/0/content');
    $session->pageTextContains('Read more');
    $this->drupalGet('/layout_builder/add/block/defaults/node.article.default/0/content/extra_field_block%3Anode%3Aarticle%3Aextra_field_readmore_extrafield');
    $session->elementExists('css', '#edit-settings-label');
    // Save field:
    $page->pressButton('edit-actions-submit');
    // Save layout:
    $page->pressButton('edit-submit');

    // Go to node and check if both "Read more" fields exist:
    $this->drupalGet('/node/' . $this->node->id());
    $session->elementsCount('css', 'a.read-more.button', 2);
    $session->elementsCount('css', 'div.readmore-extrafield > div.field__item > a.read-more.button', 2);
  }

}
