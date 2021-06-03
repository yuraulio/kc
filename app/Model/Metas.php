<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metas extends Model
{
    use HasFactory;


    public function metable()
    {
        return $this->morphTo();
    }

    public function getMetas(){
        //to do
        //dd($this);
        $metas = 
            '<meta property="fb:app_id" content="961275423898153">' . 
            '<meta property="og:title" content="' . $this->meta_title .'">'.
            '<meta property="og:type" content="website">' .
            '<meta property="og:url" content="' . url('/') . $this->metable->slugable->slug .'">' .
            '<meta property="og:image" content="' . 'image' .'">'.
            '<meta property="og:site_name" content="' . $this->meta_title .'">'.
            '<meta property="og:description" content="' . $this->meta_description .'">'.
            '<meta name="twitter:card" content="summary">'.
            '<meta name="twitter:title" content="' . $this->meta_title .'">'.
            '<meta name="twitter:description" content="' . $this->meta_description .'">'.
            '<meta name="twitter:image" content="' . 'image' .'">';

        
        return $metas;


        // return metas
    }

}
