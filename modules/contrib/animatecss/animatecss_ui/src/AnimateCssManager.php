<?php

namespace Drupal\animatecss_ui;

use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Query\PagerSelectExtender;
use Drupal\Core\Database\Query\TableSortExtender;

/**
 * Animate manager.
 */
class AnimateCssManager implements AnimateCssManagerInterface {

  /**
   * The database connection used to check the selector against.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a AnimateCssManager object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection which will be used to check the selector
   *   against.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public function isAnimate($animate) {
    return (bool) $this->connection->query("SELECT * FROM {animatecss} WHERE [selector] = :selector", [':selector' => $animate])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function findAll($keys = '', $header = []) {
    $query = $this->connection
      ->select('animatecss', 'a')
      ->extend(PagerSelectExtender::class)
      ->extend(TableSortExtender::class)
      ->orderByHeader($header)
      ->limit(50)
      ->fields('a');

    if (!empty(trim($keys))) {
      // Escape for LIKE matching.
      $keys = $this->connection->escapeLike($keys);
      // Replace wildcards with MySQL/PostgreSQL wildcards.
      $keys = preg_replace('!\*+!', '%', $keys);
      // Add selector and the label field columns.
      $group = $query->orConditionGroup()
        ->condition('selector', '%' . $keys . '%', 'LIKE')
        ->condition('label', '%' . $keys . '%', 'LIKE');
      // Run the query to find matching targets.
      $query->condition($group);
    }

    return $query->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function addAnimate($animate_id, $animate, $label, $comment, $options) {
    $this->connection->merge('animatecss')
      ->key([
        'aid'      => $animate_id,
      ])
      ->fields([
        'selector' => $animate,
        'label'    => $label,
        'comment'  => $comment,
        'options'  => $options,
      ])
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function removeAnimate($animate_id) {
    $this->connection->delete('animatecss')
      ->condition('aid', $animate_id)
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function findById($animate_id) {
    return $this->connection->query("SELECT [selector], [label], [comment], [options] FROM {animatecss} WHERE [aid] = :aid", [':aid' => $animate_id])
      ->fetchAssoc();
  }

}
