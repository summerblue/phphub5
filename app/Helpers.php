<?php
// 如：db:seed 或者 清空数据库命令的地方调用
function insanity_check()
{
    if (App::environment('production')) {
        exit('别傻了? 这是线上环境呀。');
    }
}

function cdn($filepath)
{
    if (config('app.url_static')) {
        return config('app.url_static') . $filepath;
    } else {
        return config('app.url') . $filepath;
    }
}

function get_cdn_domain()
{
    return config('app.url_static') ?: config('app.url');
}

function get_user_static_domain()
{
    return config('app.user_static') ?: config('app.url');
}

function lang($text, $parameters = [])
{
    return str_replace('phphub.', '', trans('phphub.'.$text, $parameters));
}

function admin_link($title, $path, $id = '')
{
    return '<a href="'.admin_url($path, $id).'" target="_blank">' . $title . '</a>';
}

function admin_url($path, $id = '')
{
    return env('APP_URL') . "/admin/$path" . ($id ? '/'.$id : '');
}

function admin_enum_style_output($value, $reverse = false)
{
    if ($reverse) {
        $class = ($value === true || $value == 'yes') ? 'danger' : 'success';
    } else {
        $class = ($value === true || $value == 'yes') ? 'success' : 'danger';
    }

    return '<span class="label bg-'.$class.'">'.$value.'</span>';
}

function navViewActive($anchor)
{
    return Route::currentRouteName() == $anchor ? 'active' : '';
}

function model_link($title, $model, $id)
{
    return '<a href="'.model_url($model, $id).'" target="_blank">' . $title . '</a>';
}

function model_url($model, $id)
{
    return env('APP_URL') . "/$model/$id";
}

function per_page($default = null)
{
    $max_per_page = config('api.max_per_page');
    $per_page = (Input::get('per_page') ?: $default) ?: config('api.default_per_page');

    return (int) ($per_page < $max_per_page ? $per_page : $max_per_page);
}

/**
 * 生成用户客户端 URL Schema 技术的链接.
 */
function schema_url($path, $parameters = [])
{
    $query = empty($parameters) ? '' : '?'.http_build_query($parameters);

    return strtolower(config('app.name')).'://'.trim($path, '/').$query;
}

// formartted Illuminate\Support\MessageBag
function output_msb(\Illuminate\Support\MessageBag $messageBag){
    return implode(", ", $messageBag->all());
}

function get_platform(){
    return Request::header('X-Client-Platform');
}

function is_request_from_api()
{
    return $_SERVER['SERVER_NAME'] == env('API_DOMAIN');
}
