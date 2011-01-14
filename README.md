#Formatic

version 1.1.1

* Author: [Mark Croxton](http://hallmark-design.co.uk/)


## Description

Formatic is a form automation library for CodeIgniter that can generate re-usuable form controls
and associated validation logic. It allows you to render, validate and repopulate highly complex forms.

## Formatic plugins
Formatic is extensible via plugins:

Field options: Populates a form control with existing data (for example, from a model function)
Field types: Renders HTML and includes required assets (CSS and JS) for a custom form control
Field callbacks: Apply a custom validation routine to a form control
Display widget: Intelligent view partial for the form control value (e.g. render a Googlemap)

Form controls and validation rules are described using configuration files, and groups of fields can be
tagged for reuse throughout an application. Plugins have global configuration settings which can be
overridden at the field level. All form controls make use of individual templates (view partials) or inherit
from a default, so you can achieve precisely the markup desired for your form.

Included plugins:

* Googlemap
* TinyMCE
* Related
* Related Model
* Alternate multiselect
* Chained multiselect
* Compact multiselect
* Captcha
* Recaptcha
* jSlider
* Number format
* Datepicker
* Check date

In addition to the above, file uploads and file validation are available out of the box.

## Installation
1. Copy the 3 library files, config files, example controller, example model, view files, helpers and language file to their respective folders inside ./application
2. Copy the formatic folder to your ./application directory
3. Copy the _assets and captcha folders to your public web root
4. Add the library to the $autoload['libraries'] array in ./application/config/autoload.php
5. Install and configure an asset manager: [Carabiner](https://github.com/tonydewan/Carabiner) or [Stuff](https://github.com/dhorrigan/codeigniter-stuff) are supported. Working config files are included for both asset managers, assuming you haven't changed the paths.

## Configuration
1. Open up config/formatic.php and configure system paths, image and file upload paths and API keys for the Googlemaps and Recaptcha plugins, if you intend to use them (and note that these are used in the example form).
2. Open up views/formatic_view_example.php and ensure you are using the correct asset rendering code for your chosen asset manager.

## Usage
Point your browser to /index.php/formatic_example

More to follow.
