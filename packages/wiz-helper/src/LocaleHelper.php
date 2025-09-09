<?php

namespace Wiz\Helper;

use function Laravel\Prompts\search;

class LocaleHelper
{
    const DEFAULT_LANGUAGE_NAME = 'en-US';

    public static function getListOfLanguagesAsSelectOptions($withFlag = true, $width = 22, $height = 16, $rounded = false): array
    {
        $ls = self::getListLanguages();

        $options = [];
        foreach ($ls as $key => $value) {

            if ($withFlag) {
                $styles = [];
                if ($width) {
                    $styles[] = 'width: ' . $width . 'px';
                }
                if ($height) {
                    $styles[] = 'height: ' . $height . 'px';
                }
                $rounded_class = '';
                if ($rounded) {
                    $rounded_class = ' ff-round rounded-full ';
                }
                $stringHtml = '<i class="wiz-flag wiz-flag-' . $value->country . ' mr-2 ff-md ' . $rounded_class . '" style="' . implode(';', $styles) . '"></i>  ';
            } else {
                $stringHtml = '';
            }

            $options[$value->code] = '<span class="wiz-language-item"><span> ' . $value->name . '</span>' . $stringHtml . '</span>';


        }
        return $options;
    }

    public static function getListLanguages(): \Illuminate\Support\Collection
    {
        return collect(json_decode(file_get_contents(dirname(__DIR__, 1) . '/resources/languages.json')))->sortBy('code');
    }

    public static function getListLanguageOptions($withCountry = true, $withFlag = true, $keyByName = false): array
    {
        $lsLanguageOptions = [];
        foreach (self::getListLanguages() as $key => $language) {
            $label = '';
            if ($withFlag) {
                //$label->append('(' . $language->country->flag . ')');
                $label .= ' ' . $language->country->flag . ' ';
            }
            $label .= $language->name;
            if ($withCountry) {
                $label .= ' (' . $language->country->name . ')';
            }
            if ($keyByName) {
                $lsLanguageOptions[$language->name] = $label;
            } else {
                $lsLanguageOptions[$key] = $label;
            }

        }
        return $lsLanguageOptions;
    }

    public static function getListLanguageOptionsKeyByName($withCountry = true, $withFlag = true): array
    {
        return self::getListLanguageOptions($withCountry, $withFlag, true);
    }

    public static function getCountryCodeFromLanguageCode($languageCode = 'en-US'): ?string
    {
        return explode('-', $languageCode)[1] ?? null;
    }

    public static function storeRecentLanguage($language): void
    {
        if (!empty($language)) {
            cookie()->queue(cookie('recentLanguage', $language, 24 * 60 * 30)->withHttpOnly());
        }
    }

    public static function getRecentLanguage(): array|string|null
    {
        return request()->cookie('recentLanguage',self::DEFAULT_LANGUAGE_NAME);
    }
}
