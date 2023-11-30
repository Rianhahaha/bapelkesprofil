Drupal.behaviors.clnMicroModal = {

  attach: function (context, settings) {

    const mm = context.classList && context.classList.contains('modal')
      ? [context] : context.querySelectorAll('.modal');

    if (mm.length === 0) { return; }

    MicroModal.init({
      onClose: function (modal) {
        resetIframe(modal)
      },
    });

    // Need this to stop the video on iframe close.
    function resetIframe(element) {
      var iframe = element.querySelector('iframe');
      if ( iframe !== null ) {
        var iframeSrc = iframe.src;
        iframe.src = iframeSrc;
      }
    }

  },

};
