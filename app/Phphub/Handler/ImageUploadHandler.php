<?php

namespace Phphub\Handler;

use App\Models\User;
use App\Models\Topic;
use Image;
use Auth;

class ImageUploadHandler
{
    protected $file;

    public function uploadAvatar($file, User $user)
    {
        $this->file = $file;

        $allowed_extensions = ["png", "jpg", "gif"];
        if (!$this->checkAllowedExtensions($allowed_extensions)) {
            return ['error' => 'You may only upload png, jpg or gif.'];
        }

        $avatar_name = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension() ?: 'png';
        $local_image = $this->saveImageToLocal('avatar', 380, $avatar_name);

        return ['error' => '', 'filename' => $avatar_name];
    }

    public function uploadImage($file)
    {
        $this->file = $file;

        $allowed_extensions = ["png", "jpg", "gif"];
        if (!$this->checkAllowedExtensions($allowed_extensions)) {
            return ['error' => 'You may only upload png, jpg or gif.'];
        }

        $local_image = $this->saveImageToLocal('topic', 1440);
        return ['error' => '', 'filename' => get_user_static_domain() . $local_image];
    }

    protected function checkAllowedExtensions($allowed_extensions)
    {
        if ($this->file->getClientOriginalExtension() && !in_array($this->file->getClientOriginalExtension(), $allowed_extensions)) {
            return false;
        }

        return true;
    }

    protected function saveImageToLocal($type, $resize, $filename = '')
    {
        $folderName = ($type == 'avatar')
            ? 'uploads/avatars'
            : 'uploads/images/' . date("Ym", time()) .'/'.date("d", time()) .'/'. Auth::user()->id;

        $destinationPath = public_path() . '/' . $folderName;
        $extension = $this->file->getClientOriginalExtension() ?: 'png';
        $safeName  = $filename ? :str_random(10) . '.' . $extension;
        $this->file->move($destinationPath, $safeName);

        if ($this->file->getClientOriginalExtension() != 'gif') {
            $img = Image::make($destinationPath . '/' . $safeName);
            $img->resize($resize, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save();
        }
        return $folderName .'/'. $safeName;
    }
}
