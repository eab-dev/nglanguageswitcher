# Netgen Language Switcher extension installation instructions

## Requirements

   * eZ Publish 4.7+ (purely because it uses `ezdemo` design & template, but it should work on previous versions too)

## Installation

### Unpack/unzip

Unpack the downloaded package into the `extension` directory of your eZ Publish installation.

### Activate extension

Activate the extension by using the admin interface ( Setup -> Extensions ) or by
prepending `nglanguageswitcher` to `ActiveExtensions[]` in `settings/override/site.ini.append.php`:

    [ExtensionSettings]
    ActiveExtensions[]=nglanguageswitcher

### Regenerate autoload array

Run the following from your eZ Publish root folder

    php bin/php/ezpgenerateautoloads.php --extension

Or go to Setup -> Extensions and click the "Regenerate autoload arrays" button

### Configure the extension

Copy `nglanguageswitcher.ini` to your extension and setup links to homepages of your siteaccesses

    [LanguageSwitcher]
    SiteAccessUrlMapping[eng]=http://ezpublish4.local
    SiteAccessUrlMapping[cro]=http://ezpublish4.local/cro

### Modify your existing language switcher template

If you have overriden `page_header_languages.tpl` in your custom design, make sure to modify it, so it
is now based on the template provided with this extension, instead of the original one

### Clear caches

Clear all caches (from admin 'Setup' tab or from command line).
