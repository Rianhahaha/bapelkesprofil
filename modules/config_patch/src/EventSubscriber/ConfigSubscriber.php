<?php

namespace Drupal\config_patch\EventSubscriber;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Config\ConfigEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscribe to configuration change events.
 *
 * @package Drupal\config_patch\EventSubscriber
 */
class ConfigSubscriber implements EventSubscriberInterface {

  /**
   * Invalidation service.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagInvalidator;

  /**
   * ConfigSubscriber constructor.
   *
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cacheTagsInvalidator
   *   Invalidation service.
   */
  public function __construct(CacheTagsInvalidatorInterface $cacheTagsInvalidator) {
    $this->cacheTagInvalidator = $cacheTagsInvalidator;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ConfigEvents::DELETE => 'onConfigChange',
      ConfigEvents::SAVE => 'onConfigChange',
      ConfigEvents::RENAME => 'onConfigChange',
    ];
  }

  /**
   * Config change callback.
   *
   * @param \Drupal\Component\EventDispatcher\Event $event
   *   The caught event.
   */
  public function onConfigChange(Event $event) {
    $this->cacheTagInvalidator->invalidateTags(['config_patch']);
  }

}
