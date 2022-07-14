<?php

if (!function_exists('dvoyager_asset')) {
    function dvoyager_asset($path)
    {
        return route('dvoyager.dvoyager_assets').'?path='.urlencode($path);
    }
}