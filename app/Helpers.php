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

function admin_enum_style_output($value)
{
    $class = ($value === true || $value == 'yes') ? 'success' : 'danger';
    return '<span class="label bg-'.$class.'">'.$value.'</span>';
}

function upload_topic_image($file)
{
    $allowed_extensions = ["png", "jpg", "gif"];
    if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
        return ['error' => 'You may only upload png, jpg or gif.'];
    }

    $fileName        = $file->getClientOriginalName();
    $extension       = $file->getClientOriginalExtension() ?: 'png';
    $folderName      = 'uploads/images/' . date("Ym", time()) .'/'.date("d", time()) .'/'. Auth::user()->id;
    $destinationPath = public_path() . '/' . $folderName;
    $safeName        = str_random(10).'.'.$extension;
    $file->move($destinationPath, $safeName);

    // If is not gif file, we will try to reduse the file size
    if ($file->getClientOriginalExtension() != 'gif') {
        // open an image file
        $img = Image::make($destinationPath . '/' . $safeName);
        // prevent possible upsizing
        $img->resize(1440, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        // finally we save the image as a new file
        $img->save();
    }

    return ['error' => '', 'filename' => get_user_static_domain() . $folderName .'/'. $safeName];
}

function navViewActive($anchor)
{
    return Route::currentRouteName() == $anchor ? 'active' : '';
}