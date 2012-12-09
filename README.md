# LogicielTrez #

LogicielTrez is a web app for managing the (analytical) accounting of an association.

## Installation ##

You'll need a web server:
* PHP 5.4 minimum (5.4.6 and higher recommended)
* PHP Intl extension
* PHP APC or another accelerator
* a MySql database (not tested but others should be ok as we use Doctrine DBAL)

Upload files, then run ```composer update``` (if you don't have composer take a look [here](http://getcomposer.org))

Copy ```app/config/parameters.yml.dist``` to ```app/config/parameters.yml```, then edit with your values.

Update your database schema with the command ```app/console doctrine:schema:update --force```
You have some mock data in ```doc/data.sql```

## Credits ##

This product use licensed work, please see :
 * Symfony 2.1 framework
 * jQuery and jQuery-UI
 * Twitter Bootstrap
 * some [icons](http://www.smashingmagazine.com/2012/11/11/dutch-icon-set-smashing-edition/) made by Dutch Icon