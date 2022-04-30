<?php


if(!function_exists('filterArrayByKeys')) {
    function filterArrayByKeys(array $subject, array $acceptableKeys) {
        return array_filter($subject, function ($k) use ($acceptableKeys) {
            return in_array($k, $acceptableKeys);
        }, ARRAY_FILTER_USE_KEY);
    }
}
