<?php

namespace Drupal\big_pipe_regression_test;

use Drupal\big_pipe\Render\BigPipeMarkup;
use Drupal\Core\Security\TrustedCallbackInterface;

class BigPipeRegressionTestController implements TrustedCallbackInterface {

  const MARKER_2678662 = '<script>var hitsTheFloor = "</body>";</script>';

  /**
   * @see \Drupal\Tests\big_pipe\FunctionalJavascript\BigPipeRegressionTest::testMultipleBodies_2678662()
   */
  public function regression2678662() {
    return [
      '#markup' => BigPipeMarkup::create(self::MARKER_2678662),
    ];
  }

  /**
   * @see \Drupal\Tests\big_pipe\FunctionalJavascript\BigPipeRegressionTest::testMultipleBodies_2678662()
   */
  public function regression2802923() {
    return [
      '#prefix' => BigPipeMarkup::create('<p>Hi, my train will arrive at '),
      'time' => [
        '#lazy_builder' => [static::class . '::currentTime', []],
        '#create_placeholder' => TRUE,
      ],
      '#suffix' => BigPipeMarkup::create(' — will I still be able to catch the connection to the center?</p>'),
    ];
  }

  /**
   * A page with large content.
   *
   * @see \Drupal\Tests\big_pipe\FunctionalJavascript\BigPipeRegressionTest::testBigPipeLargeContent
   */
  public function largeContent() {
    return [
      'item1' => [
        '#lazy_builder' => [static::class . '::largeContentBuilder', []],
        '#create_placeholder' => TRUE,
      ],
    ];
  }

  /**
   * Renders large content.
   *
   * @see \Drupal\Tests\big_pipe\FunctionalJavascript\BigPipeRegressionTest::testBigPipeLargeContent
   */
  public static function largeContentBuilder() {
    return [
      '#theme' => 'big_pipe_test_large_content',
      '#cache' => ['max-age' => 0],
    ];
  }

  /**
   * #lazy_builder callback; builds <time> markup with current time.
   *
   * @return array
   */
  public static function currentTime() {
    return [
      '#markup' => '<time datetime="' . date('Y-m-d', time()) . '"></time>',
      '#cache' => ['max-age' => 0],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return ['currentTime', 'largeContentBuilder'];
  }

}
