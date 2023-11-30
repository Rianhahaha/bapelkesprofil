CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Recommended modules
 * Installation
 * Configuration
 * Troubleshooting
 * FAQ
 * Maintainers

INTRODUCTION
------------

Extra Block Types: Slick Slider module provides ability
to add a block with Slick Slider.
https://kenwheeler.github.io/slick/
EBT Slick Slide also allows to display slideshows and carousels as well.

All Slick Slider options are available in block settings
including responsive settings for mobile/tablet/desktop.
Slick Slider based on paragraphs and Drupal fields,
so you can easily change image size or add Colorbox using Fields UI.

REQUIREMENTS
------------

This module requires the following modules:
 * [EBT Core](https://www.drupal.org/project/ebt_core)
 * [Paragraphs](https://www.drupal.org/project/paragraphs)

EBT Modules use Media module with Media Image type for background images.
Check Media Image type exists before install EBT Core module.

RECOMMENDED MODULES
-------------------

EBT modules provide ability to add different blocks
in Layout Builder in few clicks.
You can install separate block types from this bunch of EBT modules:
 * [EBT Accordion / FAQ](https://www.drupal.org/project/ebt_accordion)
 * [EBT Basic Button](https://www.drupal.org/project/ebt_basic_button)
 * [EBT Bootstrap Button](https://www.drupal.org/project/ebt_bootstrap_button)
 * [EBT Call to Action](https://www.drupal.org/project/ebt_cta)
 * [EBT Carousel](https://www.drupal.org/project/ebt_carousel)
 * [EBT Quote](https://www.drupal.org/project/ebt_quote)
 * [EBT Slick Slider](https://www.drupal.org/project/ebt_slick_slider)
 * [EBT Slideshow](https://www.drupal.org/project/ebt_slideshow)
 * [EBT Tabs](https://www.drupal.org/project/ebt_tabs)
 * [EBT Text](https://www.drupal.org/project/ebt_text)
 * [EBT Timeline](https://www.drupal.org/project/ebt_timeline)
 * [EBT Webform](https://www.drupal.org/project/ebt_webform)
 * [EBT Webform Popup](https://www.drupal.org/project/ebt_webform_popup)

More about EBT blocks read on EBT Core module page:
[EBT Core](https://www.drupal.org/project/ebt_core)

If you want to enhance UI for adding blocks from Layout builder try this module:
[Layout Builder Modal](https://www.drupal.org/project/layout_builder_modal)

INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. Visit
   https://www.drupal.org/node/1897420 for further information.

CONFIGURATION
-------------

EBT Core has configuration form with Primary/Secondary colors
and Mobile/Tablet/Desktop breakpoints in
Administration » Configuration » Content authoring
» Extra Block Types (EBT) settings

These configs will be applied to other EBT blocks by default.

TROUBLESHOOTING
---------------

 * If you see the error during EBT modules installation:
   "The EBT Carousel needs to be created "Image" media type.
   (Currently using Media type Image version Not created)"
   Then you need to create Image media type:
   Structure » Media types » Add media type
 * If you use Field Layout module,
   it will automatically apply Layout Builder for new EBT block types.
   So you will need to disable Layout Builder for displaying block type fields:

   /admin/structure/block/block-content/manage/ebt_slick_slider/display/default

FAQ
---

Q: Can I use only one EBT block type, for example EBT Slideshow,
without other modules?

A: Yes, sure. EBT block types tend to be standalone modules.
You can install only needed block types.

MAINTAINERS
-----------

 * [Ivan Abramenko](https://www.drupal.org/u/levmyshkin)
