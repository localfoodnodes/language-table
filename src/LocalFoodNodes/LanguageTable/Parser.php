<?php

namespace LocalFoodNodes\LanguageTable;

class Parser
{
    /**
     * Init.
     *
     * @return array
     */
    public function parse()
    {
        // Build translation array
        $translations = $this->buildArray();
        // Make sure all langs exist for all keys
        $translations = $this->verifyAllLangs($translations, $this->getLangs());
        // Sort keys for easy overview
        $translations = $this->sortKeys($translations);
        // Sort langs for all keys
        $translations = $this->sortLangs($translations);

        return $translations;
    }

    /**
     * Build array containing all translations. Formatted:
     *
     *  $translations = [
     *      'file' => [
     *          'english' => [
     *              'en' => 'english',
     *              'sv' => 'engelska',
     *          ],
     *          'swedish' => [
     *              'en' => 'swedish',
     *              'sv' => 'svenska',
     *          ]
     *      ]
     *  ];
     *
     * @return array
     */
    private function buildArray()
    {
        $langs = $this->getLangs();

        $translations = [];
        foreach ($langs as $lang) {
            $langPath = base_path('resources/lang') . '/' . $lang . '/';
            $langFiles = $this->getFiles($langPath);

            foreach ($langFiles as $langFile) {
                $fileKey = str_replace($langPath, '', $langFile);
                $fileKey = str_replace('.php', '', $fileKey);
                if (!isset($translations[$fileKey])) {
                    $translations[$fileKey] = [];
                }

                $fileContent = include($langFile);

                foreach ($fileContent as $key => $value) {
                    if (is_array($value)) {
                        $flatten = $this->flatten($value, $lang, $key);
                        $translations[$fileKey] = array_merge_recursive($translations[$fileKey], $flatten);
                    } else {
                        if (!isset($translations[$fileKey][$key])) {
                            $translations[$fileKey][$key] = [];
                        }

                        $translations[$fileKey][$key][$lang] = $value;
                    }
                }
            }
        }

        return $translations;
    }

    /**
     * Flatten array and combine array keys for nested translations.
     *
     * @param array $array
     * @param string $lang
     * @param string $prefix
     * @return array
     */
    private function flatten($array, $lang, $prefix = '')
    {
        $result = array();

        foreach ($array as $key => $value) {
            $new_key = $prefix . (empty($prefix) ? '' : '.') . $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->flatten($value, $lang, $new_key));
            } else {
                $result[$new_key][$lang] = $value;
            }
        }

        return $result;
    }

    /**
     * Sort keys alphabetically
     * @param  array $translations
     * @return array
     */
    private function sortKeys($translations)
    {
        foreach ($translations as $file => $keys) {
            ksort($keys);
            $translations[$file] = $keys;
        }

        return $translations;
    }

    /**
     * Sort languages for keys. Needed for table columns to show correct data.
     *
     * @param array $translations
     * @return array
     */
    private function sortLangs($translations)
    {
        foreach ($translations as $file => $keys) {
            foreach ($keys as $key => $langs) {
                ksort($langs);
                $translations[$file][$key] = $langs;
            }
        }

        return $translations;
    }

    /**
     * Make sure that all langs exists for all keys. Add if missing.
     *
     * @param array $translations
     * @param array $allLangs
     * @return array
     */
    private function verifyAllLangs($translations, $allLangs)
    {
        foreach ($translations as $file => $keys) {
            foreach ($keys as $key => $langs) {
                if (count($langs) !== count($allLangs)) {
                    foreach ($allLangs as $neededLang) {
                        if (!isset($langs[$neededLang])) {
                            $translations[$file][$key][$neededLang] = ''; // Set empty string for missing language
                        }
                    }
                }
            }
        }

        return $translations;
    }


    /**
     * Get all available languages.
     *
     * @return array
     */
    public function getLangs()
    {
        return array_slice(scandir(base_path('resources/lang')), 2);
    }

    /**
     * Get all translation files.
     *
     * @param string $dir
     * @param array $results
     * @return array
     */
    private function getFiles($dir, &$results = array())
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . '/' . $value);

            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != '.' && $value != '..') {
                $this->getFiles($path, $results);
            }
        }

        return $results;
    }
}
