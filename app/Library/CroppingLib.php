<?php

namespace Library;

use Config;

class CroppingLib
{
    public function projectVersions()
    {
        $versions = [];
        $tmp = Config::get('dpimage_versions.image_versions');

        if ($tmp && $tmp['settings']) {
            foreach ($tmp['settings'] as $slug => $row) {
                $versions[$slug] = [
                    "pos" => $row["pos"],
                    "slug" => $row["slug"],
                    "title" => $row["title"],
                    "attributes" => $row["attributes"],
                    "description" => $row["description"],
                    "applicable" => $row["applicable"],
                ];
            }
        }

        /*
        $versions = [
            "version-1" => [
                "pos" => 0,
                "ar" => 1,
                "relax" => 0.005,
                "slug" => "version-1",
                "title" => 'Square',
                "description" => 'Applies to : homepage latest post center, cyprus and police featured, photo gallery thumbs',
                "applicable" => 'latest-posts-center, cyprus-featured, police-featured, photo-gallery-thumb',
            ],
            "version-2" => [
                "pos" => 1,
                "ar" => 1.421,
                "relax" => 0.005,
                "slug" => "version-2",
                "title" => 'Post list image',
                "description" => 'Applies to: post list images, tvone suggestions, international sports and inshape featured',
                "applicable" => 'post-general-small, post-general-medium, latest-posts, tvone-suggests, international-featured, sports-featured, inshape-featured',
            ],
            "version-3" => [
                "pos" => 2,
                "ar" => 1.84,
                "relax" => 0.005,
                "slug" => "version-3",
                "title" => 'Post main image',
                "description" => 'Applies to: Post main pic',
                "applicable" => 'main',
            ],
            "version-4" => [
                "pos" => 3,
                "ar" => 1.7714,
                "relax" => 0.005,
                "slug" => "version-4",
                "title" => 'Videos and photos',
                "description" => 'Applies to: Video featured, photo gallery list items',
                "applicable" => 'video-featured, photo-gallery',
            ],
            "version-5" => [
                "pos" => 4,
                "ar" => 1.611,
                "relax" => 0.005,
                "slug" => "version-5",
                "title" => 'Videos and photos',
                "description" => 'Applies to: Carousel photos, video list items',
                "applicable" => 'top-carousel, video-list',
            ],
            "version-6" => [
                "pos" => 5,
                "ar" => 0.4909,
                "relax" => 0.005,
                "slug" => "version-6",
                "title" => 'Portrait',
                "description" => 'Applies to: All portrait images',
                "applicable" => 'latest-posts-side',
            ],
            "version-7" => [
                "pos" => 6,
                "ar" => 2.02,
                "relax" => 0.005,
                "slug" => "version-7",
                "title" => 'Carousel thumbs',
                "description" => 'Applies to: Carousel thumbs',
                "applicable" => 'top-carousel-side',
            ],

            "version-8" => [
                "pos" => 7,
                "ar" => 7.5,
                "relax" => 0.005,
                "slug" => "version-8",
                "title" => 'Juris Banner',
                "description" => 'Applies to: Jurisdictions Banner',
                "applicable" => 'juris',
            ],
        ];
        */

        return $versions;
    }

    public function routesToProjectVersions()
    {
        $presets = [];
        $tmp = Config::get('dpimage_versions.image_versions');

        if ($tmp && $tmp['settings']) {
            foreach ($tmp['settings'] as $slug => $row) {
                $presets[$slug] = [
                    "version" => $slug,
                    "cache" => $row["cache"],
                    "attributes" => $row["attributes"],
                ];
            }
        }

        /*
        $presets = [
            "main" => [
                'version' => 'version-3',
                'cache' => 'main/',
                'attributes' => ['w' => 920, 'h' => 500, 'fit' => 'crop'],
            ],
            "post-general-small" => [
                'version' => 'version-2',
                'cache' => 'post-general-small/',
                'attributes' => ['w' => 216, 'h' => 152, 'fit' => 'crop'],
            ],
            "post-general-medium" => [
                'version' => 'version-2',
                'cache' => 'post-general-medium/',
                'attributes' => ['w' => 270, 'h' => 190, 'fit' => 'crop'],
            ],
            "top-carousel" => [
                'version' => 'version-5',
                'cache' => 'top-carousel/',
                'attributes' => ['w' => 870, 'h' => 540, 'fit' => 'crop'],
            ],
            "top-carousel-side" => [
                'version' => 'version-1',
                'cache' => 'top-carousel-side/',
                'attributes' => ['w' => 140, 'h' => 140, 'fit' => 'crop'],
            ],
            "new-top-carousel-side" => [
                'version' => 'version-7',
                'cache' => 'new-top-carousel-side/',
                'attributes' => ['w' => 295, 'h' => 146, 'fit' => 'crop'],
            ],
            "latest-posts-center" => [
                'version' => 'version-1',
                'cache' => 'latest-posts-center/',
                'attributes' => ['w' => 600, 'h' => 600, 'fit' => 'crop'],
            ],
            "latest-posts-side" => [
                'version' => 'version-6',
                'cache' => 'latest-posts-side/',
                'attributes' => ['w' => 270, 'h' => 550, 'fit' => 'crop'],
            ],
            "latest-posts" => [
                'version' => 'version-2',
                'cache' => 'latest-posts/',
                'attributes' => ['w' => 290, 'h' => 204, 'fit' => 'crop'],
            ],
            "sector-icons" => [
                'version' => 'version-2',
                'cache' => 'sector-icons/',
                'attributes' => ['w' => 371, 'h' => 217, 'fit' => 'crop'],
            ],
            "news-list" => [
                'version' => 'version-2',
                'cache' => 'news-list/',
                'attributes' => ['w' => 370, 'h' => 260, 'fit' => 'crop'],
            ],
            "team-featured" => [
                'version' => 'version-6',
                'cache' => 'team-featured/',
                'attributes' => ['w' => 600, 'h' => 738, 'fit' => 'crop'],
            ],
            "video-list" => [
                'version' => 'version-5',
                'cache' => 'video-list/',
                'attributes' => ['w' => 432, 'h' => 268, 'fit' => 'crop'],
            ],
            "video-featured" => [
                'version' => 'version-4',
                'cache' => 'video-featured/',
                'attributes' => ['w' => 920, 'h' => 520, 'fit' => 'crop'],
            ],
            "photo-gallery" => [
                'version' => 'version-4',
                'cache' => 'photo-gallery/',
                'attributes' => ['w' => 1240, 'h' => 700, 'fit' => 'max'],
            ],
            "photo-gallery-thumb" => [
                'version' => 'version-1',
                'cache' => 'photo-gallery-thumb/',
                'attributes' => ['w' => 100, 'h' => 100, 'fit' => 'crop'],
            ],
            "juris" => [
                'version' => 'version-8',
                'cache' => 'juris-banners/',
                'attributes' => ['w' => 1500, 'h' => 200, 'fit' => 'crop'],
            ],
        ];
        */

        return $presets;
    }
}
