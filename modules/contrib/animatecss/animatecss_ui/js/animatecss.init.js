/**
 * @file
 * Contains definition of the behaviour Animate.css.
 */

(function ($, Drupal, drupalSettings, once) {
  "use strict";

  const compat = drupalSettings.animatecss.compat;

  Drupal.behaviors.animateCSS = {
    attach: function (context, settings) {

      const elements = drupalSettings.animatecss.elements;

      $.each(elements, function (index, element) {
        let options = {
          selector: element.selector,
          animation:  element.animation,
          delay: element.delay,
          time: element.time,
          speed: element.speed,
          duration: element.duration,
          repeat: element.repeat,
        };

        if (Array.isArray(options.selector)) {
          $.each(options.selector, function (index, selector) {
            if (once('animatecss', selector).length) {
              options.selector = selector;
              let animateCSS = new Drupal.animateCSS(options);
            }
          });
        }
        else {
          if (once('animatecss', options.selector).length) {
            let animateCSS = new Drupal.animateCSS(options);
          }
        }
      });

    }
  };

  Drupal.animateCSS = function (options) {
    // Build Animate.css classes from global AdminCSS settings.
    let Prefix  = compat ? '' : 'animate__';
    let Classes = `${Prefix}animated`;

    if (options.animation) {
      Classes += ` ${Prefix}${options.animation}`;

      if (options.delay && options.delay != 'custom') {
        Classes += ` ${Prefix}${options.delay}`;
      }
      if (options.speed && options.speed != 'custom' && options.speed != 'medium') {
        Classes += ` ${Prefix}${options.speed}`;
      }
      if (options.repeat && options.repeat != 'repeat-1') {
        Classes += ` ${Prefix}${options.repeat}`;
      }

      // Add Animate.css classes.
      $(options.selector).addClass(Classes);

      // Add Animate.css custom properties.
      if (options.delay == 'custom' && !compat) {
        $(options.selector).css('--animate-delay', options.time);
      }
      if (options.speed == 'custom' && !compat) {
        $(options.selector).css('--animate-duration', options.duration);
      }
    }
  };

})(jQuery, Drupal, drupalSettings, once);
