<?php

use Desoft\DVoyager\Enums\Constants;
use Desoft\DVoyager\Models\DVoyagerTrace;

if (!function_exists('dvoyager_asset')) {
    function dvoyager_asset($path)
    {
        return route('dvoyager.dvoyager_assets').'?path='.urlencode($path);
    }
}

if (! function_exists('act_date')) {

    function act_date() {

        $format = config('dvoyager.act_date_format', 'd/m/Y');
        
    	$act_date = DVoyagerTrace::whereNotIn('action',[Constants::USER_AUTH_TRACE['USER_LOGOUT'], Constants::USER_AUTH_TRACE['USER_LOGIN']])->latest()->first();

        if($act_date == null)
        {
            return __('dvoyager::generic.no_act_date');
        }

    	$db_date = $act_date->created_at;

        return date_format($db_date, $format);

    }
}

if(!function_exists('snakeToCamel'))
{
    function snakeToCamel($input)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }
}