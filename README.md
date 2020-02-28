# Media duplicates

## Introduction

This module allows the comparison and detection of duplicate media entities
within a site. This module does not provide any methods for cleaning up the 
duplicate items.


## Requirements

This module requires no modules outside of Drupal core.


## Installation

* To install this module, `composer require` it, or  place it in your modules
  folder and enable the module.

* Generate initial checksums if you have existing media items. You can use the form linked from the status page or run 
  the `drush media-duplicates:refresh-checksums` command.


## Configuration

There is currently no configuration options for the module.


## Maintainers

Current maintainers:

* Nathan ter Bogt - https://www.drupal.org/u/nterbogt
