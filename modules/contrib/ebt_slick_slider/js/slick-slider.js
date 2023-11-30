(function ($, Drupal) {

  /**
   * EBT Slick Slider behavior.
   */
  Drupal.behaviors.ebtSlickSlider = {
    attach: function (context, settings) {
      $.each(drupalSettings.ebtSlickSlider, function(i, value){
        // Initialize Slick Slider.
        var blockClass = drupalSettings.ebtSlickSlider[i].blockClass;
        if ($('.' + blockClass).length == 0) {
          return;
        }
        var $blockSlider = $('.' + blockClass + ' .slides');
        if ($blockSlider.hasClass('slick-slider-added')) {
          return;
        }

        var options = {};

        drupalBlockSettings = drupalSettings.ebtSlickSlider[i].options;

        if (drupalBlockSettings.autoWidth != undefined) {
          if (drupalBlockSettings.autoWidth == 1) {
            options['autoWidth'] = true;
          }
          if (drupalBlockSettings.autoWidth == 0) {
            options['autoWidth'] = false;
          }
        }

        if (drupalBlockSettings.autoplay != undefined) {
          if (drupalBlockSettings.autoplay == 1) {
            options['autoplay'] = true;
          }
          if (drupalBlockSettings.autoplay == 0) {
            options['autoplay'] = false;
          }
        }

        if (drupalBlockSettings.autoplaySpeed != undefined && drupalBlockSettings.autoplaySpeed != '') {
          options['autoplaySpeed'] = parseInt(drupalBlockSettings.autoplaySpeed);
        }

        if (drupalBlockSettings.arrows != undefined) {
          if (drupalBlockSettings.arrows == 1) {
            options['arrows'] = true;
          }
          if (drupalBlockSettings.arrows == 0) {
            options['arrows'] = false;
          }
        }

        if (drupalBlockSettings.centerMode != undefined) {
          if (drupalBlockSettings.centerMode == 1) {
            options['centerMode'] = true;
          }
          if (drupalBlockSettings.centerMode == 0) {
            options['centerMode'] = false;
          }
        }

        if (drupalBlockSettings.centerPadding != undefined && drupalBlockSettings.centerPadding != '') {
          options['centerPadding'] = Drupal.checkPlain(drupalBlockSettings.centerPadding);
        }

        if (drupalBlockSettings.dots != undefined) {
          if (drupalBlockSettings.dots == 1) {
            options['dots'] = true;
          }
          if (drupalBlockSettings.dots == 0) {
            options['dots'] = false;
          }
        }

        if (drupalBlockSettings.infinite != undefined) {
          if (drupalBlockSettings.infinite == 1) {
            options['infinite'] = true;
          }
          if (drupalBlockSettings.infinite == 0) {
            options['infinite'] = false;
          }
        }

        if (drupalBlockSettings.initialSlide != undefined && drupalBlockSettings.initialSlide != '') {
          options['initialSlide'] = parseInt(drupalBlockSettings.initialSlide);
        }

        if (drupalBlockSettings.lazyLoad != undefined && drupalBlockSettings.lazyLoad != '') {
          options['lazyLoad'] = Drupal.checkPlain(drupalBlockSettings.lazyLoad);
        }

        if (drupalBlockSettings.mobileFirst != undefined) {
          if (drupalBlockSettings.mobileFirst == 1) {
            options['mobileFirst'] = true;
          }
          if (drupalBlockSettings.mobileFirst == 0) {
            options['mobileFirst'] = false;
          }
        }

        if (drupalBlockSettings.slidesToShow != undefined && drupalBlockSettings.slidesToShow != '') {
          options['slidesToShow'] = parseInt(drupalBlockSettings.slidesToShow);
        }

        if (drupalBlockSettings.slidesToScroll != undefined && drupalBlockSettings.slidesToScroll != '') {
          options['slidesToScroll'] = parseInt(drupalBlockSettings.slidesToScroll);
        }

        if (drupalBlockSettings.speed != undefined && drupalBlockSettings.speed != '') {
          options['speed'] = parseInt(drupalBlockSettings.speed);
        }

        if (drupalBlockSettings.variableWidth != undefined) {
          if (drupalBlockSettings.variableWidth == 1) {
            options['variableWidth'] = true;
          }
          if (drupalBlockSettings.variableWidth == 0) {
            options['variableWidth'] = false;
          }
        }

        // Mobile.
        if (drupalBlockSettings.responsive != undefined && drupalBlockSettings.responsive.mobile != undefined) {
          if (drupalBlockSettings.responsive.mobile.breakpoint != undefined && drupalBlockSettings.responsive.mobile.breakpoint != '') {
            var mobileBreakpoint = parseInt(drupalBlockSettings.responsive.mobile.breakpoint);
          }

          if (drupalBlockSettings.responsive.mobile.slidesToShow != undefined && drupalBlockSettings.responsive.mobile.slidesToShow != '') {
            var mobileSlidesToShow = parseInt(drupalBlockSettings.responsive.mobile.slidesToShow);
          }

          if (drupalBlockSettings.responsive.mobile.slidesToScroll != undefined && drupalBlockSettings.responsive.mobile.slidesToScroll != '') {
            var mobileSlidesToScroll = parseInt(drupalBlockSettings.responsive.mobile.slidesToScroll);
          }

          if (drupalBlockSettings.responsive.mobile.centerMode != undefined) {
            if (drupalBlockSettings.responsive.mobile.centerMode == 1) {
              var mobileCenterMode = true;
            }
            if (drupalBlockSettings.responsive.mobile.centerMode == 0) {
              var mobileCenterMode = false;
            }
          }

          if (drupalBlockSettings.responsive.mobile.centerPadding != undefined && drupalBlockSettings.responsive.mobile.centerPadding != '') {
            var mobileEdgePadding = Drupal.checkPlain(drupalBlockSettings.responsive.mobile.centerPadding);
          }
        }

        // Tablet.
        if (drupalBlockSettings.responsive != undefined && drupalBlockSettings.responsive.tablet != undefined) {
          if (drupalBlockSettings.responsive.tablet.breakpoint != undefined && drupalBlockSettings.responsive.tablet.breakpoint != '') {
            var tabletBreakpoint = parseInt(drupalBlockSettings.responsive.tablet.breakpoint);
          }

          if (drupalBlockSettings.responsive.tablet.slidesToShow != undefined && drupalBlockSettings.responsive.tablet.slidesToShow != '') {
            var tabletSlidesToShow = parseInt(drupalBlockSettings.responsive.tablet.slidesToShow);
          }

          if (drupalBlockSettings.responsive.tablet.slidesToScroll != undefined && drupalBlockSettings.responsive.tablet.slidesToScroll != '') {
            var tabletSlidesToScroll = parseInt(drupalBlockSettings.responsive.tablet.slidesToScroll);
          }

          if (drupalBlockSettings.responsive.tablet.centerMode != undefined) {
            if (drupalBlockSettings.responsive.tablet.centerMode == 1) {
              var tabletCenterMode = true;
            }
            if (drupalBlockSettings.responsive.tablet.centerMode == 0) {
              var tabletCenterMode = false;
            }
          }

          if (drupalBlockSettings.responsive.tablet.centerPadding != undefined && drupalBlockSettings.responsive.tablet.centerPadding != '') {
            var tabletEdgePadding = Drupal.checkPlain(drupalBlockSettings.responsive.tablet.centerPadding);
          }
        }

        // Desktop.
        if (drupalBlockSettings.responsive != undefined && drupalBlockSettings.responsive.desktop != undefined) {
          if (drupalBlockSettings.responsive.desktop.breakpoint != undefined && drupalBlockSettings.responsive.desktop.breakpoint != '') {
            var desktopBreakpoint = parseInt(drupalBlockSettings.responsive.desktop.breakpoint);
          }

          if (drupalBlockSettings.responsive.desktop.slidesToShow != undefined && drupalBlockSettings.responsive.desktop.slidesToShow != '') {
            var desktopSlidesToShow = parseInt(drupalBlockSettings.responsive.desktop.slidesToShow);
          }

          if (drupalBlockSettings.responsive.desktop.slidesToScroll != undefined && drupalBlockSettings.responsive.desktop.slidesToScroll != '') {
            var desktopSlidesToScroll = parseInt(drupalBlockSettings.responsive.desktop.slidesToScroll);
          }

          if (drupalBlockSettings.responsive.desktop.centerMode != undefined) {
            if (drupalBlockSettings.responsive.desktop.centerMode == 1) {
              var desktopCenterMode = true;
            }
            if (drupalBlockSettings.responsive.desktop.centerMode == 0) {
              var desktopCenterMode = false;
            }
          }

          if (drupalBlockSettings.responsive.desktop.centerPadding != undefined && drupalBlockSettings.responsive.desktop.centerPadding != '') {
            var desktopEdgePadding = Drupal.checkPlain(drupalBlockSettings.responsive.desktop.centerPadding);
          }
        }

        // Responsive settings.
        var responsive = [];

        if (typeof mobileBreakpoint !== 'undefined') {
          var mobileBreakpointSettings = {
            breakpoint: mobileBreakpoint,
            settings: {},
          }

          if (typeof mobileSlidesToShow !== 'undefined') {
            mobileBreakpointSettings.settings['slidesToShow'] = parseInt(mobileSlidesToShow);
          }

          if (typeof mobileSlidesToScroll !== 'undefined') {
            mobileBreakpointSettings.settings['slidesToScroll'] = parseInt(mobileSlidesToScroll);
          }

          if (typeof mobileCenterMode !== 'undefined' && mobileCenterMode != 0) {
            mobileBreakpointSettings.settings['centerMode'] = parseInt(mobileCenterMode);
          }

          if (typeof mobileСenterPadding !== 'undefined') {
            mobileBreakpointSettings.settings['centerPadding'] = Drupal.checkPlain(mobileСenterPadding);
          }

          responsive.push(mobileBreakpointSettings);
        }

        if (typeof tabletBreakpoint !== 'undefined') {
          var tabletBreakpointSettings = {
            breakpoint: tabletBreakpoint,
            settings: {},
          }

          if (typeof tabletSlidesToShow !== 'undefined') {
            tabletBreakpointSettings.settings['slidesToShow'] = parseInt(tabletSlidesToShow);
          }

          if (typeof tabletSlidesToScroll !== 'undefined') {
            tabletBreakpointSettings.settings['slidesToScroll'] = parseInt(tabletSlidesToScroll);
          }

          if (typeof tabletCenterMode !== 'undefined' && tabletCenterMode != 0) {
            tabletBreakpointSettings.settings['centerMode'] = Drupal.checkPlain(tabletCenterMode);
          }

          if (typeof tabletCenterPadding !== 'undefined') {
            tabletBreakpointSettings.settings['centerPadding'] = Drupal.checkPlain(tabletCenterPadding);
          }

          responsive.push(tabletBreakpointSettings);
        }

        if (typeof desktopBreakpoint !== 'undefined') {
          var desktopBreakpointSettings = {
            breakpoint: desktopBreakpoint,
            settings: {},
          }

          if (typeof desktopSlidesToShow !== 'undefined') {
            desktopBreakpointSettings.settings['slidesToShow'] = parseInt(desktopSlidesToShow);
          }

          if (typeof desktopSlidesToScroll !== 'undefined') {
            desktopBreakpointSettings.settings['slidesToScroll'] = parseInt(desktopSlidesToScroll);
          }

          if (typeof desktopCenterMode !== 'undefined' && desktopCenterMode != 0) {
            desktopBreakpointSettings.settings['centerMode'] = Drupal.checkPlain(desktopCenterMode);
          }

          if (typeof desktopCenterPadding !== 'undefined') {
            desktopBreakpointSettings.settings['centerPadding'] = Drupal.checkPlain(desktopCenterPadding);
          }

          responsive.push(desktopBreakpointSettings);
        }

        options['responsive'] = responsive;

        if (drupalBlockSettings.additional.accessibility != undefined) {
          if (drupalBlockSettings.additional.accessibility == 1) {
            options['accessibility'] = true;
          }
          if (drupalBlockSettings.additional.accessibility == 0) {
            options['accessibility'] = false;
          }
        }

        if (drupalBlockSettings.additional.adaptiveHeight != undefined) {
          if (drupalBlockSettings.additional.adaptiveHeight == 1) {
            options['adaptiveHeight'] = true;
          }
          if (drupalBlockSettings.additional.adaptiveHeight == 0) {
            options['adaptiveHeight'] = false;
          }
        }

        if (drupalBlockSettings.additional.draggable != undefined) {
          if (drupalBlockSettings.additional.draggable == 1) {
            options['draggable'] = true;
          }
          if (drupalBlockSettings.additional.draggable == 0) {
            options['draggable'] = false;
          }
        }

        if (drupalBlockSettings.additional.cssEase != undefined && drupalBlockSettings.additional.cssEase != '') {
          if (drupalBlockSettings.additional.cssEase == 1) {
            options['cssEase'] = true;
          }
          if (drupalBlockSettings.additional.cssEase == 0) {
            options['cssEase'] = false;
          }
        }

        if (drupalBlockSettings.additional.fade != undefined) {
          if (drupalBlockSettings.additional.fade == 1) {
            options['fade'] = true;
          }
          if (drupalBlockSettings.additional.fade == 0) {
            options['fade'] = false;
          }
        }

        if (drupalBlockSettings.additional.focusOnSelect != undefined) {
          if (drupalBlockSettings.additional.additional == 1) {
            options['additional'] = true;
          }
          if (drupalBlockSettings.additional.additional == 0) {
            options['additional'] = false;
          }
        }

        if (drupalBlockSettings.additional.easing != undefined && drupalBlockSettings.additional.easing != '') {
          options['easing'] = Drupal.checkPlain(drupalBlockSettings.additional.easing);
        }

        if (drupalBlockSettings.additional.edgeFriction != undefined && drupalBlockSettings.additional.edgeFriction != '') {
          options['edgeFriction'] = Drupal.checkPlain(drupalBlockSettings.additional.edgeFriction);
        }

        if (drupalBlockSettings.additional.pauseOnFocus != undefined) {
          if (drupalBlockSettings.additional.pauseOnFocus == 1) {
            options['pauseOnFocus'] = true;
          }
          if (drupalBlockSettings.additional.pauseOnFocus == 0) {
            options['pauseOnFocus'] = false;
          }
        }

        if (drupalBlockSettings.additional.pauseOnHover != undefined) {
          if (drupalBlockSettings.additional.pauseOnHover == 1) {
            options['pauseOnHover'] = true;
          }
          if (drupalBlockSettings.additional.pauseOnHover == 0) {
            options['pauseOnHover'] = false;
          }
        }

        if (drupalBlockSettings.additional.pauseOnDotsHover != undefined) {
          if (drupalBlockSettings.additional.pauseOnDotsHover == 1) {
            options['pauseOnDotsHover'] = true;
          }
          if (drupalBlockSettings.additional.pauseOnDotsHover == 0) {
            options['pauseOnDotsHover'] = false;
          }
        }

        if (drupalBlockSettings.additional.respondTo != undefined && drupalBlockSettings.additional.respondTo != '') {
          options['respondTo'] = Drupal.checkPlain(drupalBlockSettings.additional.respondTo);
        }

        if (drupalBlockSettings.additional.rows != undefined && drupalBlockSettings.additional.rows != '') {
          options['rows'] = parseInt(drupalBlockSettings.additional.rows);
        }

        if (drupalBlockSettings.additional.slidesPerRow != undefined && drupalBlockSettings.additional.slidesPerRow != '') {
          options['slidesPerRow'] = parseInt(drupalBlockSettings.additional.slidesPerRow);
        }

        if (drupalBlockSettings.additional.swipe != undefined) {
          if (drupalBlockSettings.additional.swipe == 1) {
            options['swipe'] = true;
          }
          if (drupalBlockSettings.additional.swipe == 0) {
            options['swipe'] = false;
          }
        }

        if (drupalBlockSettings.additional.swipeToSlide != undefined) {
          if (drupalBlockSettings.additional.swipeToSlide == 1) {
            options['swipeToSlide'] = true;
          }
          if (drupalBlockSettings.additional.swipeToSlide == 0) {
            options['swipeToSlide'] = false;
          }
        }

        if (drupalBlockSettings.additional.touchMove != undefined) {
          if (drupalBlockSettings.additional.touchMove == 1) {
            options['touchMove'] = true;
          }
          if (drupalBlockSettings.additional.touchMove == 0) {
            options['touchMove'] = false;
          }
        }

        if (drupalBlockSettings.additional.touchThreshold != undefined && drupalBlockSettings.additional.touchThreshold != '') {
          options['touchThreshold'] = drupalBlockSettings.additional.touchThreshold;
        }

        if (drupalBlockSettings.additional.useCSS != undefined) {
          if (drupalBlockSettings.additional.useCSS == 1) {
            options['useCSS'] = true;
          }
          if (drupalBlockSettings.additional.useCSS == 0) {
            options['useCSS'] = false;
          }
        }

        if (drupalBlockSettings.additional.useTransform != undefined) {
          if (drupalBlockSettings.additional.useTransform == 1) {
            options['useTransform'] = true;
          }
          if (drupalBlockSettings.additional.useTransform == 0) {
            options['useTransform'] = false;
          }
        }

        if (drupalBlockSettings.additional.vertical != undefined) {
          if (drupalBlockSettings.additional.vertical == 1) {
            options['vertical'] = true;
          }
          if (drupalBlockSettings.additional.vertical == 0) {
            options['vertical'] = false;
          }
        }

        if (drupalBlockSettings.additional.verticalSwiping != undefined) {
          if (drupalBlockSettings.additional.verticalSwiping == 1) {
            options['verticalSwiping'] = true;
          }
          if (drupalBlockSettings.additional.verticalSwiping == 0) {
            options['verticalSwiping'] = false;
          }
        }

        if (drupalBlockSettings.additional.rtl != undefined) {
          if (drupalBlockSettings.additional.rtl == 1) {
            options['rtl'] = true;
          }
          if (drupalBlockSettings.additional.rtl == 0) {
            options['rtl'] = false;
          }
        }

        if (drupalBlockSettings.additional.waitForAnimate != undefined) {
          if (drupalBlockSettings.additional.waitForAnimate == 1) {
            options['waitForAnimate'] = true;
          }
          if (drupalBlockSettings.additional.waitForAnimate == 0) {
            options['waitForAnimate'] = false;
          }
        }

        if (drupalBlockSettings.additional.zIndex != undefined && drupalBlockSettings.additional.zIndex != '') {
          options['zIndex'] = Drupal.checkPlain(drupalBlockSettings.additional.zIndex);
        }
        console.log(options);
        $blockSlider.slick(options);
        $blockSlider.addClass('slick-slider-added');
      });
    }
  };

})(jQuery, Drupal);
