# LogicielTrez #

LogicielTrez is a web app for managing the (analytical) accounting of an association.
At the moment it's only in French, and quite close to French business rules (laws, TVA...).

Some of its features:
* can handle a few budgets
* currency and so on are configurable
* login using classic credentials or a nice OpenId authentification (using Gmail)
* users with ACL: you can create users who can only view some parts of some budgets
* billing
* VAT support _(TVA in French)_

## Installation ##

### 0) Requirements

You'll need a web server with:
* Apache
* PHP 5.3 (5.3.8 and higher recommended)
* PHP Intl extension
* PHP APC or another accelerator
* a MySql database (not tested but others should be ok as we use Doctrine DBAL)

### 1) Retrieve files

Upload files, then run ```composer update``` (if you don't have composer take a look [here](http://getcomposer.org) - you can just download the .phar archive)

### 2) Configuration, create an User account

Copy ```app/config/parameters.yml.dist``` to ```app/config/parameters.yml```, then edit with your values.

### 3) Database

Update your database schema with the command ```app/console doctrine:schema:update --force```
Load fixtures (some configuration values), ```php app/console doctrine:fixtures:load```

### 4) Create another User account, delete old one

You can now login to the application, on ```web/app.php``` (or ```web/app_dev.php``` on localhost) using the credentials you set in ```parameters.yml```. It's best to go directly to the Users page to set another account, and then delete the first one from your config file.

## Credits ##

This product use licensed work, please see:
 * Symfony 2.1 framework
 * jQuery and jQuery-UI
 * Chosen jQuery plugin
 * Twitter Bootstrap
 * some [icons](http://www.smashingmagazine.com/2012/11/11/dutch-icon-set-smashing-edition/) made by Dutch Icon (notably the favicon)
 * WhiteOctober [Breadcrumbs bundle](https://github.com/whiteoctober/BreadcrumbsBundle)
 * Gitorious [LightOpenID](https://gitorious.org/lightopenid) library
 * formapro [FpOpenIdBundle](https://github.com/formapro/FpOpenIdBundle)
