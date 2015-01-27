Taxonomy module for Yii2
========================

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require infoweb-internet-solutions/yii2-taxonomy "*"
```

or add

```
"infoweb-internet-solutions/yii2-taxonomy": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply modify your backend configuration as follows:

```php
'modules' => [
    ...
    'taxonomy' => [
        'class' => 'infoweb\taxonomy\Module',
    ],
    ...
    'cms' => [
        sideBarItems => [
            'modules' => [
                [
                    'label'     => 'Taxonomy',
                    'i18nGroup' => 'infoweb/taxonomy',
                    'url'       => '/taxonomy/term',
                    'authItem'  => 'showTaxonomyModule',
                ],
            ],
        ],
    ],
],
```

Import the translations and use category 'infoweb/taxonomy':
```
yii i18n/import @infoweb/taxonomy/messages
```

To use the module, execute yii migration
```
yii migrate/up --migrationPath=@vendor/infoweb-internet-solutions/yii2-taxonomy/migrations
```
