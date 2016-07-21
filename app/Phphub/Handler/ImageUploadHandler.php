<?php

namespace Phphub\Handler;

use App\Models\User;
use App\Models\Topic;
use Image;
use Auth;

class ImageUploadHandler
{
    public function uploadAvatar($file, User $user)
    {
        $allowed_extensions = ["png", "jpg", "gif"];
        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
            return ['error' => 'You may only upload png, jpg or gif.'];
        }

        $fileName        = $file->getClientOriginalName();
        $extension       = $file->getClientOriginalExtension() ?: 'png';
        $folderName      = 'uploads/avatars';
        $destinationPath = public_path() . '/' . $folderName;
        $avatar_name     = $user->id . '_' . time() . '.' . $extension;

        $file->move($destinationPath, $avatar_name);

        // If is not gif file, we will try to reduse the file size
        if ($file->getClientOriginalExtension() != 'gif') {
            // open an image file
            $img = Image::make($destinationPath . '/' . $avatar_name);
            // prevent possible upsizing
            $img->resize(380, 380, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            // finally we save the image as a new file
            $img->save();
        }
        return ['error' => '', 'filename' => $avatar_name];
    }

    public function uploadImage($file)
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

        if ($file->getClientOriginalExtension() != 'gif') {
            $img = Image::make($destinationPath . '/' . $safeName);
            $img->resize(1440, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save();
        }

        return ['error' => '', 'filename' => get_user_static_domain() . $folderName .'/'. $safeName];
    }
}
