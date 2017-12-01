# Language Table
Language table is a Laravel package that parses your language files and displays all keys as a table. It checks that all keys are set for all langages and shows an error for any inconsistencies.

## Install
* Install the package using `composer require localfoodnodes/language-table`
* Add the service provider in your config/app.php
`LocalFoodNodes\LanguageTable\ServiceProvider::class`
* Add route to your routes file.
`Route::get('/your/route', '\LocalFoodNodes\LanguageTable\Controller@index');`
