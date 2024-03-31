<?php

namespace App\Helpers;

use App\Model\Admin\MediaFile;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Logged User Helper.
 *
 * Class UserHelper
 */
class UserHelper
{
    /**
     * Get User profile image by $user.
     * @param $user
     * @return string
     */
    public static function getUserProfileImage($user, $options)
    {
        return self::prepareFileImage($user, $options);
    }

    /**
     * Prepare user image.
     * @param $user
     * @param array $options
     * @param bool $returnImage
     * @return string
     */
    private static function prepareFileImage($user, $options = [], $returnImage = true)
    {
        $options = array_merge([
            'id' => null,
            'width' => 20,
            'height' => 20,
            'class' => '',
        ], $options);
        $default = cdn('/theme/assets/images/icons/user-circle-placeholder.svg');
        $imageUrl = cdn('/theme/assets/images/icons/user-circle-placeholder.svg');;

        if(isset($user->profile_image)){
            $imageUrl = $user->profile_image->url;
        }

        return '<img loading="lazy" ' . ($options['id'] ? ' id="' . $options['id'] . '"' : '') .
            '" width="' . $options['width'] . '" height="' . $options['height'] . '" class="' . $options['class'] . '"' .
            'title="' . $user['firstname'] . '' . $user['lastname'] . '" alt="' . $user['firstname'] . '' . $user['lastname'] . '" ' .
            'src="' . $imageUrl . '" onerror="this.src=\'' . $default . '\'"/>';

        if(!isset($imageUrl)){
            if (!empty($user['image'])) {
                $imageUrl = get_profile_image($user['image']);
                if(isset($user->profile_image)){
                    if(isset($user->profile_image->images)){
                        foreach($user->profile_image->images as $image){
                            if($image->version == 'instructors-small'){
                                $imageUrl = $image->full_path;
                            }
                        }
                    }
                }

                if ($imageUrl) {
                    if ($returnImage) {
                        return '<img loading="lazy" ' . ($options['id'] ? ' id="' . $options['id'] . '"' : '') .
                            '" width="' . $options['width'] . '" height="' . $options['height'] . '" class="' . $options['class'] . '"' .
                            'title="' . $user['firstname'] . '' . $user['lastname'] . '" alt="' . $user['firstname'] . '' . $user['lastname'] . '" ' .
                            'src="' . $imageUrl . '" onerror="this.src=\'' . $default . '\'"/>';
                    }

                    return $imageUrl;
                }
            }
        }
        if (!empty($user['email'])) {
            $image = md5(strtolower($user['email']));

            $imageUrl = 'https://www.gravatar.com/avatar/' . $image . '?s=' . ($options['width'] < 64 ? 64 : $options['width']) . '&d=' .
                urlencode(cdn('/theme/assets/images/icons/user-circle-placeholder.png'));

            if ($returnImage) {
                return '<img loading="lazy" ' . ($options['id'] ? ' id="' . $options['id'] . '"' : '') .
                    '" width="' . $options['width'] . '" height="' . $options['height'] . '" class="' . $options['class'] . '"' .
                    'title="' . $user['firstname'] . '' . $user['lastname'] . '" alt="' . $user['firstname'] . '' . $user['lastname'] . '" ' .
                    'src="' . $imageUrl . '" onerror="this.src=\'' . $default . '\'"/>';
            }

            return $imageUrl;
        }

        if ($returnImage) {
            return '<img' . ($options['id'] ? ' id="' . $options['id'] . '"' : '') .
                '" width="' . $options['width'] . '" height="' . $options['height'] . '" class="' . $options['class'] . '"' .
                'src="' . $default . '" />';
        }

        return $default;
    }
}
