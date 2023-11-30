/* *
 * @file
 * Global utilities.
 *
 */
(function ($, Drupal, once) {
  'use strict';

  Drupal.behaviors.support_items = {
    attach: function (context, settings) {
      once('support_items', '.support-area', context).forEach(function (element) {
        let opne_btn = document.querySelector('.support-area .open-btn'),
          page_body = document.querySelector('body'),
          nextSibling = opne_btn.previousElementSibling,
          support_btn = document.querySelectorAll('.support-area .support-dropdown .support-button'),
          theme = 'unset',
          contrast = 'unset';

        // Action to open the support menu.
        opne_btn.addEventListener('click', function () {
          opne_btn.classList.toggle('active');
          nextSibling.classList.toggle('active');
        });

        // Action of dark-mode button (moon icon) and contrast button (half circle icon).
        support_btn.forEach(function (e) {
          e.addEventListener('click', function () {
            e.querySelector('.btn-state').classList.toggle('active');

            if (e.classList.contains('contrast-btn')) {
              page_body.classList.toggle('high-ctr');
              if (document.body.classList.contains('high-ctr')) {
                contrast = 'enabled';
              } else {
                contrast = 'disabled';
              }
              localStorage.setItem('contrast', contrast);
            } else if (e.classList.contains('dark-btn')) {
              page_body.classList.toggle('dark-mode');
              if (document.body.classList.contains('dark-mode')) {
                theme = 'dark';
              } else {
                theme = 'light';
              }
              localStorage.setItem('theme', theme);
            }
          });
        });

        // Check DARK Mode at the local storage and if not set check at operating system level.
        const current_theme = localStorage.getItem('theme');
        let system_dark_scheme = window.matchMedia('(prefers-color-scheme: dark)');

        if (current_theme == 'dark') {
          document.body.classList.add('dark-mode');
          document.querySelector('.dark-btn .btn-state').classList.add('active');
        } else if (current_theme == 'light') {
          document.body.classList.remove('dark-mode');
          document.querySelector('.dark-btn .btn-state').classList.remove('active');
        } else {
          if (system_dark_scheme.matches) {
            document.body.classList.add('dark-mode');
            document.querySelector('.dark-btn .btn-state').classList.add('active');
          } else {
            document.body.classList.remove('dark-mode');
            document.querySelector('.dark-btn .btn-state').classList.remove('active');
          }
        }

        // Check CONTRAST Mode at the local storage and if not set check at operating system level.
        const current_contrast = localStorage.getItem('contrast');
        let system_contrast_scheme = window.matchMedia('(-ms-high-contrast: active)');
        if (current_contrast == 'enabled') {
          document.body.classList.add('high-ctr');
          document.querySelector('.contrast-btn .btn-state').classList.add('active');
        } else if (current_contrast == 'disabled') {
          document.body.classList.remove('high-ctr');
          document.querySelector('.contrast-btn .btn-state').classList.remove('active');
        } else {
          if (system_contrast_scheme.matches) {
            document.body.classList.add('high-ctr');
            document.querySelector('.contrast-btn .btn-state').classList.add('active');
          } else {
            document.body.classList.remove('high-ctr');
            document.querySelector('.contrast-btn .btn-state').classList.remove('active');
          }
        }
      });
    },
  };

  Drupal.behaviors.stickyActions = {
    attach: function (context, settings) {
      let $bulkActions = $('.view-content .views-form [data-drupal-selector=edit-header]')
        .add('.webform-bulk-form > .container-inline');
      // return if not applicable
      if ($bulkActions.length === 0) return;
      let hasVScroll = $(document).height() > $(window).height();
      // Remove sticky when scroll isn't present
      if (!hasVScroll) {
        $bulkActions.removeClass('is-fixed-to-bottom');
      }
      // Add & remove sticky on scroll
      $(window).on("scroll", function () {
        if ($(window).scrollTop() + window.innerHeight > $(document).height() - 100) {
          $bulkActions.removeClass('is-fixed-to-bottom');
        } else {
          $bulkActions.addClass('is-fixed-to-bottom');
        }
      });
    },
  };

  // Add class to body when dialog is opened and remove it when is closed
  Drupal.behaviors.addClassWhenModalOpen = {
    attach: function (context, settings) {
      $(document).on('dialogopen', function () {
        $('html').addClass('dialog-open');
      });
      $(document).on('dialogclose', function () {
        $('html').removeClass('dialog-open');
      });
    }
  };

  // Close modal when click on background
  Drupal.behaviors.closeModalsWhenClickingBackdrop = {
    attach: function (context, settings) {
      $(document.body).once('closeModalsWhenClickingBackdrop').on("click", ".ui-widget-overlay", function () {
        $.each($(".ui-dialog"), function () {
          var $modal = $(this).children(".ui-dialog-content");
          if ($modal.dialog("option", "modal")) {
            $modal.dialog("close");
          }
        });
      });
    },
  };
})(jQuery, Drupal, once);
