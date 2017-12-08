<?php

namespace LocalFoodNodes\LanguageTable;

class Diff
{
    private $translations;

    /**
     * Contructor.
     *
     * @param array $translations
     */
    public function __construct($translations)
    {
        $this->translations = $translations;
    }

    /**
     * Compare languages.
     *
     * @param string $original
     * @param string $compare
     * @return array
     */
    public function compare($original, $compare)
    {
        $missing = [];

        foreach ($this->translations as $file => $keys) {
            foreach ($keys as $key => $langs) {
                if (!isset($langs[$compare]) || $langs[$compare] == '') {
                    if (!isset($missing[$file])) {
                        $missing[$file] = [];
                    }

                    if (!isset($missing[$file][$key])) {
                        $missing[$file][$key] = [];
                    }

                    $missing[$file][$key] = $langs[$original];
                }

                if (isset($missing[$file][$key][$compare]) && $missing[$file][$key][$compare] === $missing[$file][$key][$original]) {
                    $missing[$file][$key] = $langs[$original];
                }
            }
        }

        return $missing;
    }

    /**
     * Write diff file to storage folder.
     *
     * @param array $diff translation ArrayIterator
     * @param string $original original language
     * @param string $compare compared language
     * @return string output file path
     */
    public function writeDiffFile($diff, $original, $compare)
    {
        $outputDir = storage_path('translations');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $outputFile = storage_path('translations/diff-' . $original . '-' . $compare . '.php');
        $output = fopen($outputFile, 'w') or die('Unable to open file!');

        fwrite($output, '<?php');
        fwrite($output, PHP_EOL);
        fwrite($output, '$diff = [');
        fwrite($output, PHP_EOL);

        foreach ($diff as $file => $keys) {
            fwrite($output, PHP_EOL);
            fwrite($output, "\t// $file");
            fwrite($output, PHP_EOL);
            foreach ($keys as $key => $string) {
                fwrite($output, "\t'$key' => '$string',");
                fwrite($output, PHP_EOL);
            }
        }

        fwrite($output, '];');
        fwrite($output, PHP_EOL);
        fclose($output);

        return $output;
    }
}
