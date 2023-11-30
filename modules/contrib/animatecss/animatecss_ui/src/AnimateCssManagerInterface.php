<?php

namespace Drupal\animatecss_ui;

/**
 * Provides an interface defining an animate manager.
 */
interface AnimateCssManagerInterface {

  /**
   * Returns if this Animate css selector is added.
   *
   * @param string $animate
   *   The Animate css selector to check.
   *
   * @return bool
   *   TRUE if the Animate css selector is added, FALSE otherwise.
   */
  public function isAnimate($animate);

  /**
   * Finds all added Animate css selector.
   *
   * @param string $keys
   *   The Animate search key to filter selector.
   * @param array $header
   *   The Animate header to sort selector and label.
   *
   * @return \Drupal\Core\Database\StatementInterface
   *   The result of the database query.
   */
  public function findAll($keys, $header);

  /**
   * Add an Animate css selector.
   *
   * @param int $animate_id
   *   The Animate id for edit.
   * @param string $animate
   *   The Animate selector to add.
   * @param string $label
   *   The label of animate selector.
   * @param string $comment
   *   The comment for animate options.
   * @param string $options
   *   The Animate selector options.
   */
  public function addAnimate($animate_id, $animate, $label, $comment, $options);

  /**
   * Remove an Animate css selector.
   *
   * @param int $animate_id
   *   The Animate id to remove.
   */
  public function removeAnimate($animate_id);

  /**
   * Finds an added Animate css selector by its ID.
   *
   * @param int $animate_id
   *   The ID for an added Animate selector.
   *
   * @return string|false
   *   Either the added Animate selector or FALSE if none exist with that ID.
   */
  public function findById($animate_id);

}
