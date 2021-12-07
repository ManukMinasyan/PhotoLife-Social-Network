<?php
/**
 * Created by PhpStorm.
 * User: Manuk
 * Website: https://minasyan.info
 * Date: 17/11/2019
 * Time: 19:33
 */
if (! function_exists('check')) {
    function check()
    {
        $guards = array_keys(config('auth.guards'));
        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                return auth()->guard($guard);
            }
        }
    }
}

if (! function_exists('human_file_size')) {
    function human_file_size($bytes, $decimals = 2)
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)).@$size[$factor];
    }
}

if (! function_exists('get_file_details')) {
    function get_file_details($path)
    {
        return app('upload.manager')->fileDetails($path);
    }
}

if (! function_exists('get_hashtags_from_string')) {
    function get_hashtags_from_string($string)
    {
        $keywords = [];

        /* Match hashtags */
        preg_match_all("/(#\w+)/", (string) $string, $matches);

        /* Add all matches to array */
        foreach ($matches[1] as $match) {
            $keywords[] = $match;
        }

        return (array) $keywords;
    }
}
