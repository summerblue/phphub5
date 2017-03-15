<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Image;

trait TopicImageHelper
{
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function collectImages()
    {
        // For update topic logic
        $this->images()->delete();

        $links = get_image_links($this->body);

        foreach ($links as $link) {
            $data = [
                'topic_id' => $this->id,
                'link' => $link,
            ];
            Image::updateOrCreate($data, $data);
        }
    }
}
