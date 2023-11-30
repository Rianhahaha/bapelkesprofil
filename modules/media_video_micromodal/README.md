# Media Video Micromodal

## Creates a formatter for a modal popup for remote videos.

CONTENTS OF THIS FILE
---------------------

* Introduction
* Requirements
* Installation
* Configuration


INTRODUCTION
------------

This module uses the micromodal.js library to generate modal popup for remote
videos from the media module.  Specifically works with core media oembed
remote videos, that have the "field_media_oembed_video" field for the remote
video URL.  You may attach the modal to the thumbnail generated by the oembed
remote video, a custom uploaded thumbnail, the video name or video caption or
custom link text.

Lightweight and non-dependent on jQuery.
* For a full description of the module, visit the project page:
  https://www.drupal.org/project/media_video_micromodal

* To submit bug reports and feature suggestions, or track changes:
  https://www.drupal.org/project/issues/media_video_micromodal


REQUIREMENTS
------------

This module requires the following modules:

* [Drupal Core Media](https://www.drupal.org/project/drupal)


INSTALLATION
------------

* Install as you would normally install a contributed Drupal module. Visit
  https://www.drupal.org/node/1897420 for further information.


CONFIGURATION
-------------

* Manage the display settings for the "remote_video" media bundle.
* Set up your desired configuration based on the use case.

MVM uses display modes on the remote video media type to display the modal in a variety of ways.
See below to find the use case that best suits your needs.

### Thumbnail - Default
To display the video thumbnail linking to the modal:
- Select the oembed auto generated image for the video which should be "Thumbnail".
- Set the "Format" to "Micromodal field formatter".
- Use the settings to select the image style for the thumbnail.

### Thumbnail - Custom
Instead of using the oembed thumbnail you can use a custom thumbnail that is set on the remote video.
- This field can be type "image" or "media" reference.
- Follow the steps for "Thumbnail - Default" to apply the formatting.

### Text - Media Name
To display the media name linking to the modal:
- Display the "Name" field.
- Set the "Format" to "Micromodal field formatter".
- Use the settings to add additional styles to a &lt;span&gt; tag around the name.

### Text - Caption (custom per media)
You can customize the text for each individual media by using the caption field.
- Follow the steps above for "Text - Media Name".
- Check the optional "Caption Swap" setting in the formatter.
- This will display the media caption instead of the media name.

### Text - Link Text (custom per display)
You can also set the link text custom per display mode.
- Follow the steps above for "Text - Media Name".
- In the settings use the "Link Text" field.
- This custom text will be displayed for this display mode.

### WORKING WITH CKEDITOR
It's easy to use the MVM formatter inside CKEditor, but needs some setup to accomplish this.
- Ensure the Media Library module is enabled.
- Make sure the "Embed media" filter has been checked for the text format that
CKEditor is using.
- Under "Filter settings => Embed media" select the view
modes that have been set up to use the Micromodal are selected under "View
modes selectable".
- Insert the video using the "Insert from Media Library"
widget.
- "Edit Media" to select the display and check "Caption" if you are
using the "Caption Swap" feature on that display.

### WORKING WITH VIEWS
You can use this module with views by creating or using an existing view mode
for the content type and assigning the formatter.  Then inside the view use
"Rendered Entity" and use the content type view mode with
the micromodal formatter(s) set.

### About micromodal.js:
Micromodal.js is a lightweight, configurable and a11y-enabled modal library written in pure JavaScript.
https://micromodal.vercel.app
https://github.com/Ghosh/micromodal
License: https://github.com/ghosh/Micromodal/blob/master/LICENSE.md