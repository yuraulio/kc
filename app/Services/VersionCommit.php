<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;

class VersionCommit
{
    public function __construct(){

    }

    public static function display(){
        if (Storage::disk('storage')->exists('version-commit.txt')) {
            echo Storage::disk('storage')->get('version-commit.txt');
        } else {
            echo "";
        }
    }
}
