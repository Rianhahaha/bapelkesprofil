<?php

namespace Drupal\nivoslider\Plugin\Field\FieldFormatter;


use Drupal\Core\Field\FieldItemListInterface;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;


/**
 * Plugin implementation of our "Nivo Slider" formatter.
 *
 * @FieldFormatter(
 *   id = "nivoslider_formatter",
 *   module = "nivoslider",
 *   label = @Translation("Nivo Slider Formatter"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class NivoSliderFormatter extends ImageFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
	
	$settings = $this->getSettings();
	$image_style = $settings['image_style'] ? $settings['image_style'] : NULL;

    if (empty($images = $this->getEntitiesToView($items, $langcode))) {
      // Early opt-out if the field is empty.
      return $elements;
    }
    $imaget ='';
    foreach ($images as $delta => $image) {
	   
	  if ($image_style) {
        $style = $this->imageStyleStorage->load($image_style);

        /** @var \Drupal\image\ImageStyleInterface $style */
        $file_url = $style->buildUrl($image->getFileUri());
      }
      else {
	   $file_url = $this->fileUrlGenerator->generateAbsoluteString(
          $image->getFileUri()
        );
	  }		
		
	  $imaget .= '<img src="'.$file_url.'" data-thumb=src="'.$file_url.'">';
		 
    }
	
	$elements[$delta] = [
        // We wrap the fieldnote content up in a div tag.
        '#type' => 'html_tag',
        '#tag' => 'div',
        // This text is auto-XSS escaped.  See docs for the html_tag element.
        '#value' => $imaget,
        // Let's give the note a nice sticky-note CSS appearance.
        '#attributes' => [
          'class' => 'nivoSlider',
        ],
	    '#attached' => [
        'library' => [
          'nivoslider/nivoslider'
        ],
      ],

      ];
	
	return $elements;
  }

}
