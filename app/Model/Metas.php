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

    public function getMetas($isHomePage = false){
        //to do
        //dd($this);

        $pageLink = !$isHomePage ? url('/') . '/'  . $this->metable->slugable->slug :  url('/'); 
        $title =  $this->meta_title ?  $this->meta_title : $this->metable->name;

        $metas = 
            '<title>'. $title .'</title>
            <meta name="author" content="Knowcrunch">
            <meta name="description" content="' . $this->meta_description .'">
            <meta name="keywords" content="' . $this->meta_keywords .'">
            <meta property="fb:app_id" content="961275423898153">' . 
            '<meta property="og:title" content="' . $this->meta_title .'">'.
            '<meta property="og:type" content="website">' .
            '<meta property="og:url" content="' . $pageLink  .'">' .
            '<meta property="og:image" content="' . url('/')  . get_image($this->metable->mediable,'social-media-sharing') .'">'.
            '<meta property="og:site_name" content="' . $this->meta_title .'">'.
            '<meta property="og:description" content="' . $this->meta_description .'">'.
            '<meta name="twitter:card" content="summary_large_image">'.
            '<meta name="twitter:title" content="' . $this->meta_title .'">'.
            '<meta name="twitter:description" content="' . $this->meta_description .'">'.
            '<meta name="twitter:image" content="' . 'image' .'">';

        
        return $metas;

    }

}
