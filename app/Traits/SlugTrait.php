<?php

namespace App\Traits;

use App\Model\Slug;
use Eloquent;

trait SlugTrait
{
    public function createSlug($slugKey)
    {
        $slug = new Slug;
        $slug->slug = $slugKey;
        $slug->save();

        $this->slugable()->save($slug);
    }

    public function slugable()
    {
        return $this->morphOne(Slug::class, 'slugable');
    }

    public function getSlug()
    {
        return $this->slugable ? $this->slugable->slug : '';
    }
}
