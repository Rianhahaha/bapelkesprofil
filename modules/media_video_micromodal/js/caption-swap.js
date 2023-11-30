(function () {

  'use strict';

  // Grab all off the elements that need the caption swap.
  let captionSwaps = document.getElementsByClassName("caption-swap");

  // Loop through the caption swap elements.
  Array.prototype.forEach.call(captionSwaps, function (el) {

    // Grab the parent "figure" tag, if there is one.
    let figureElement = el.closest('figure');

    // Continue if there is a figure tag.
    if (figureElement != null) {

      // Check for the figcaption tag inside figure.
      if (figureElement.querySelector('figcaption') != null) {

        // Look for contents of the figcaption tag.
        if (figureElement.querySelector('figcaption').innerHTML.length) {

          // Swap the media name with the media caption.
          el.innerHTML = figureElement.querySelector('figcaption').innerHTML;

          // Remove the figcaption tag.
          figureElement.querySelector('figcaption').remove();

        }

      }

    }

  });

})();
