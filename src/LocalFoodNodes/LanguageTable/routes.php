<?php

Route::group(['prefix' => '/language-table'], function () {
    Route::post('/diff', '\LocalFoodNodes\LanguageTable\Controller@diff');
});
