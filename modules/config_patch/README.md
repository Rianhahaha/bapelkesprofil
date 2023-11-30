# CONFIG PATCH

## INTRODUCTION

This module provides a framework for providing patch files for config (the 
differences between active configuration and sync configuration). For a site 
that's built out from source files, this allows sitebuilders to make changes to 
configuration in the UI and push a button to create a patch which may then be 
committed back to source. The framework is extensible, so additional output 
plugins may provide integrations with e.g., cloud source control providers.

Since patches reference file paths, the path of these files is configurable. In 
other words, if your synchronized config is stored in `drupal/config` in your 
source repo, you can configure `drupal/config` as your base config path in 
config_patch.

When installed, config_patch provides an additional tab `Patch` on the admin 
config synchronization pages listing config that is out of sync and providing a 
button to output the patch in various forms. It also adds a button to the admin 
toolbar that shows the number of configuration items that are out of sync.

Available output plugins:

- Text: provides text output of a unified diff patch
- [Gitlab](https://drupal.org/project/config_patch_gitlab): provides submission 
to Gitlab repositories using MR-by-email functionality.

Similar modules:

- [Config Partial Export](https://www.drupal.org/project/config_partial_export): 
export a tarball of config ymls that differ between sync and active

## REQUIREMENTS

This module requires no modules outside of Drupal core.

## INSTALLATION

Install as you would normally install a contributed Drupal module. Visit 
https://www.drupal.org/node/1897420 for further information.

## CONFIGURATION

Configure this module at /admin/config/development/config_patch
