<?php

namespace LocalFoodNodes\LanguageTable;

use LocalFoodNodes\LanguageTable\Parser;

class Controller extends \App\Http\Controllers\Controller
{
    /**
     * Index action.
     *
     * @param  Request $request
     */
    public function index(Request $request)
    {
        $parser = new Parser();
        $translations = $parser->parse();
        $languages = $parser->getLangs();

        return view('translation-coverage::table', [
            'languages' => $languages,
            'translations' => $translations,
        ]);
    }

    /**
     * Diff action.
     *
     * @param  Request $request
     */
    public function diff(Request $request)
    {
        $parser = new Parser();
        $translations = $parser->parse();

        $differ = new Diff($translations);
        $diff = $differ->compare($request->input('original'), $request->input('compare'));
        $differ->writeDiffFile($diff, $request->input('original'), $request->input('compare'));

        return redirect()->back();
    }
}
