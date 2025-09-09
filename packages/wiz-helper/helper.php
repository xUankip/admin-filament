<?php

if (!function_exists('_lang')) {
    /**
     * @param $key
     * @param string $default
     * @param array $replace
     * @param null $locale
     * @return mixed|string|null
     */
    function _lang($key, string $default = '', array $replace = [], $locale = null): mixed
    {
        if (is_null($key)) {
            return null;
        }

        $string = trans($key, $replace, $locale);
        if (empty($string) || ($string == $key && !blank($default))) {
            if ($default) {
                if (!empty($replace)) {
                    foreach ($replace as $placeholder => $value) {
                        $default = str_replace(':' . $placeholder, $value, $default);
                    }
                }
                $string = $default;
            } else {
                $str = str_replace(['_', '-'], ' ', $key);

                // Capitalize the first letter of each word
                $string = ucwords(strtolower($str));
            }

        }
        return $string;
    }
}


if (!function_exists('_lang_without_sync')) {
    function _lang_without_sync($key, $default = '', $replace = [], $locale = null)
    {
        return _lang($key, $default, $replace, $locale);
    }
}

if (!function_exists('_get_max_upload_support')) {
    /**
     * @param float|int $max is Kilobyte
     * @return string|int|float
     */
    function _get_max_upload_support(float|int $max = 2 * 1024): string|int|float
    {

        $serverMaxSize = return_kilobytes(ini_get('upload_max_filesize'));
        if ($max > $serverMaxSize) {
            return $serverMaxSize;
        }
        return $max;
    }
}
if (!function_exists('return_kilobytes')) {
    /**
     * @param float|int $max is Kilobyte
     * @return string|int|float
     */
    function return_kilobytes($val): int|string
    {
        $val  = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        return match ($last) {
            'g' => (float)$val * 1024 * 1024,
            'm' => (float)$val * 1024,
            default => (float)$val,
        };
    }
}


if (!function_exists('_get_count_code_from_language')) {
    function _get_count_code_from_language($language): int|string|null
    {
        return \Wiz\Helper\LocaleHelper::getCountryCodeFromLanguageCode($language);
    }
}


if (!function_exists('_button_style_attr')) {
    function _button_style_attr($morClass = ''): int|string|null
    {
        return
            ' style="--c-400: var(--primary-400); --c-500: var(--primary-500); --c-600: var(--primary-600); position: relative; overflow: hidden;"
                                class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition
                                        duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary
                                        fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600
                                        text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus-visible:ring-custom-500/50
                                        dark:focus-visible:ring-custom-400/50
                                        fi-ac-btn-action ' . $morClass . '"';

    }

}
if (!function_exists('_input_class_attr')) {
    function _input_class_attr(): int|string|null
    {
        return ' fi-input block w-full border border-gray-200 rounded-lg py-1.5 text-base text-gray-950 transition duration-75
            placeholder:text-gray-400  disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)]
            disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 dark:text-white
            focus:ring-1 dark:bg-white/5 ring-gray-950/10
            dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)]
            dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] bg-white/0 ps-3 pe-3 focus:ring-primary-600';

    }
}

if (!function_exists('_avatar_ai')) {
    function _avatar_ai($chatBotAi): string
    {
        $stringName = '';
        if (empty($chatBotAi)) {
            $stringName = rand(0, 1);
        }
        if (!is_string($chatBotAi) && !empty($chatBotAi->name)) {
            if (!empty($chatBotAi?->file?->url)) {
                return $chatBotAi?->file?->url;
            }
            $stringName = $chatBotAi->name;
        } else {
            $stringName = $chatBotAi;
        }
        $numericPart = (int)preg_replace('/[^0-9]/', '', md5($stringName));
        $n           = $numericPart % 15;
        $gender      = $numericPart % 2;
        if ($gender == 1) {
            $link = asset('img/gender/female-' . $n . '.png');
        } else {
            $link = asset('img/gender/male-' . $n . '.png');
        }
        return $link;

    }

}

/**
 * @param $inputDate
 * @return string
 * // Example usage:
 * $inputDate = "2024-01-15 09:37:22";
 * $result = calculateTimeAgo($inputDate);
 * echo $result;
 */
if (!function_exists('_time_ago')) {

    function _time_ago($inputDate): string
    {
        if (empty($inputDate)) {
            return "Not found";
        }
        $currentTime    = time();
        $inputTimestamp = strtotime($inputDate);
        $timeDifference = $currentTime - $inputTimestamp;

        if ($timeDifference < 60) {
            return trans_choice('time.second', $timeDifference, ['count' => $timeDifference]) . ' ' . trans('time.ago');
        } elseif ($timeDifference < 3600) {
            $minutes = floor($timeDifference / 60);
            return trans_choice('time.minute', $minutes, ['count' => $minutes]) . ' ' . trans('time.ago');
        } elseif ($timeDifference < 86400) {
            $hours = floor($timeDifference / 3600);
            return trans_choice('time.hour', $hours, ['count' => $hours]) . ' ' . trans('time.ago');
        } elseif ($timeDifference < 2592000) {
            $days = floor($timeDifference / 86400);
            return trans_choice('time.day', $days, ['count' => $days]) . ' ' . trans('time.ago');
        } elseif ($timeDifference < 31536000) {
            $months = floor($timeDifference / 2592000);
            return trans_choice('time.month', $months, ['count' => $months]) . ' ' . trans('time.ago');
        } else {
            $years = floor($timeDifference / 31536000);
            return trans_choice('time.year', $years, ['count' => $years]) . ' ' . trans('time.ago');
        }
    }
}

if (!function_exists('zi_image')) {

    function zi_image($path, $default = ''): string
    {
        if ($path) {
            return route('zi_link.thumb', ['file' => $path]);
        }
        return $default;
    }
}
if (!function_exists('zi_icon_title')) {

    function zi_icon_title(): string
    {
        return asset('front/green/img/theme-img/title_icon.svg');
    }
}

if (!function_exists('zi_format_currency')) {

    function zi_format_currency($amount, $symbol = '₫'): string
    {
        return number_format($amount, 0, ',', '.') . ' <sup>' . $symbol . '</sup>';
    }
}

if (!function_exists('zi_format_currency_vnd')) {

    function zi_format_currency_vnd($amount): string
    {
        return number_format($amount, 0, ',', '.');
    }
}


if (!function_exists('zi_config_get_embed_code')) {

    function zi_config_get_embed_code(): string
    {
        $embed  = \App\Models\System\ZiConfig::getConfigAndParserValue(\App\Enums\ConfigEnum::EMBED_CODE_SETTING);
        $string = $embed['google'] ?? '';
        $string .= $embed['facebook'] ?? '';
        if (!empty($embed['others'])) {
            foreach ($embed['others'] as $key => $string) {
                $string .= $string;
            }
        }
        return $string;
    }
}





