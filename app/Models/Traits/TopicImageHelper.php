<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Image;

trait TopicImageHelper
{
    public function cover()
    {
        return $this->images->first();
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function collectImages()
    {
        // For update topic logic
        $this->images()->delete();

        $links = get_image_links($this->body);

        if (count($links)) {
            $link = array_shift($links);
            $data = [
                'topic_id' => $this->id,
                'link' => $link,
            ];
            Image::updateOrCreate($data, $data);
        }
    }
}
