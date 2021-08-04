<?php


Route::get('b', function () {
    return redirect('backend/login');
});

/*
|--------------------------------------------------------------------------------------------------------------------------
| Web reverse proxy
|--------------------------------------------------------------------------------------------------------------------------
*/
$proxy_url    = getenv('PROXY_URL');
$proxy_schema = getenv('PROXY_SCHEMA');
if (!empty($proxy_url)) {
    URL::forceRootUrl($proxy_url);
}

if (!empty($proxy_schema)) {
    URL::forceScheme($proxy_schema);
}

/*
|--------------------------------------------------------------------------------------------------------------------------
| Web backend
|--------------------------------------------------------------------------------------------------------------------------
*/
require_once('web-frontend.php');
require_once('web-backend.php');

Route::get('lang/{lang}', 'LocaleController@lang');