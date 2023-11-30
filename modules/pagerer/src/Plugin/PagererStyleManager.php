<?php

namespace Drupal\pagerer\Plugin;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\pagerer\Plugin\Annotation\PagererStyle as PagererStyleAnnotation;

/**
 * Plugin manager for Pagerer style plugins.
 */
class PagererStyleManager extends DefaultPluginManager implements PagererStyleManagerInterface {

  public function __construct(
    \Traversable $namespaces,
    CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler,
    protected ConfigFactoryInterface $configFactory
  ) {
    parent::__construct('Plugin/pagerer', $namespaces, $module_handler, PagererStyleInterface::class, PagererStyleAnnotation::class);
    $this->alterInfo('pagerer_style_plugin_info');
    $this->setCacheBackend($cache_backend, 'pagerer_style_plugins');
  }

  /**
   * {@inheritdoc}
   */
  public function createInstance($plugin_id, array $configuration = []) {
    try {
      $default_configuration = $this->configFactory->get('pagerer.style.' . $plugin_id)->get('default_config');
      $configuration = NestedArray::mergeDeep($default_configuration ?? [], $configuration);
      return parent::createInstance($plugin_id, $configuration);
    }
    catch (PluginNotFoundException $e) {
      $configuration = $this->configFactory->get('pagerer.style.basic')->get('default_config');
      return parent::createInstance('basic', $configuration);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginOptions(string $style_type): array {
    $options = [];
    foreach ($this->getDefinitions() as $plugin) {
      if ($plugin['style_type'] == $style_type) {
        $options[$plugin['id']] = $plugin['short_title'];
      }
    }
    asort($options);
    return $options;
  }

}
