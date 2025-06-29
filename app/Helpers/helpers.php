<?php

if (!function_exists('t')) {
    /**
     * Translate the given message with optional replacements.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    function t($key, $replace = [], $locale = null)
    {
        // Check if key exists in app translations first
        if (trans()->has('app.' . $key, $locale)) {
            return trans('app.' . $key, $replace, $locale);
        }
        
        // Fall back to regular translation
        return trans($key, $replace, $locale);
    }
}

if (!function_exists('current_locale')) {
    /**
     * Get the current locale.
     *
     * @return string
     */
    function current_locale()
    {
        return app()->getLocale();
    }
}

if (!function_exists('is_locale')) {
    /**
     * Check if the current locale matches the given locale.
     *
     * @param  string  $locale
     * @return bool
     */
    function is_locale($locale)
    {
        return current_locale() === $locale;
    }
}