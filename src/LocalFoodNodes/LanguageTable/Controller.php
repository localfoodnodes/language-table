<?php

namespace LocalFoodNodes\LanguageTable;

use LocalFoodNodes\LanguageTable\Parser;

class Controller extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $parser = new Parser();
        $translations = $parser->parse();
        $languages = $parser->getLangs();

        return view('translation-coverage::table', [
            'languages' => $languages,
            'translations' => $translations,
        ]);
    }
}
