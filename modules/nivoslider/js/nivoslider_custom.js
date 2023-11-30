/*
@File is designed to add slider
*/
(function ($, Drupal) {
  Drupal.behaviors.nivoslider = {
    attach: function attach(context, settings) {
		

				
		$('.nivoSlider').nivoSlider({
			effect: 'random',
			slices: 15,
			boxCols: 8,
			boxRows: 4,
			animSpeed: 500,
			pauseTime: 3000,
			startSlide: 0,
			directionNav: true,
			controlNav: true,
			controlNavThumbs: false,
			pauseOnHover: true,
			manualAdvance: false,
			prevText: '<',
			nextText: '>',
			randomStart: false,
			beforeChange: function(){},
			afterChange: function(){},
			slideshowEnd: function(){},
			lastSlide: function(){},
			afterLoad: function(){}
		});
    }
  };

})(jQuery, Drupal);
