/**
 * @file
 * Contains definition of the behaviour Animate.css.
 */

(function ($, Drupal, drupalSettings, once) {
  "use strict";

  Drupal.behaviors.animateCSS = {
    attach: function (context, settings) {

      const example = drupalSettings.animatecss.sample;
      const Selector = example.selector;

      if (once('animate__sample', Selector).length) {
        let options = {
          selector: Selector,
          animation: example.animation,
          delay: example.delay,
          time: example.time,
          speed: example.speed,
          duration: example.duration,
          repeat: example.repeat,
        };
        let demoAnimateCSS = new Drupal.animateCSSdemo(options);

        // Animate.css preview replay.
        $(once('animate__replay', '.animate__replay', context)).on(
          'click',
          function (event) {
            $(Selector).attr('class', 'animate__sample');

            let options = {
              selector: Selector,
              animation: $('#edit-animation').val(),
              delay: $('#edit-delay').val(),
              time: $('#edit-time').val(),
              speed: $('#edit-speed').val(),
              duration: $('#edit-duration').val(),
              repeat: $('#edit-repeat').val(),
            };

            setTimeout(function () {
              let demoAnimateCSS = new Drupal.animateCSSdemo(options);
            }, 10);

            event.preventDefault();
          }
        );
      }
    }
  };

  Drupal.animateCSSdemo = function (options) {
    // Build Animate.css classes from global AdminCSS settings.
    let Classes = `animate__animated`;

    if (options.animation) {
      Classes += ` animate__${options.animation}`;

      if (options.delay && options.delay != 'custom') {
        Classes += ` animate__${options.delay}`;
      }
      if (options.speed && options.speed != 'custom' && options.speed != 'medium') {
        Classes += ` animate__${options.speed}`;
      }
      if (options.repeat && options.repeat != 'repeat-1') {
        Classes += ` animate__${options.repeat}`;
      }

      // Add Animate.css classes.
      $(options.selector).addClass(Classes);

      // Add Animate.css custom properties.
      if (options.delay == 'custom') {
        $(options.selector).css('--animate-delay', options.time);
      }
      if (options.speed == 'custom') {
        $(options.selector).css('--animate-duration', options.duration);
      }
    }
  };

})(jQuery, Drupal, drupalSettings, once);
