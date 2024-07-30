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
    public static function getUserProfileImage($user, $options): string
    {
        return self::prepareFileImage($user, $options);
    }

    /**
     * Prepare user image.
     * @param $user
     * @param array $options
     * @return string
     */
    private static function prepareFileImage($user, array $options = [])
    {
        $options = array_merge([
            'id' => null,
            'width' => 20,
            'height' => 20,
            'class' => '',
        ], $options);
        $default = cdn('/theme/assets/images/icons/user-circle-placeholder.svg');
        $imageUrl = cdn('/theme/assets/images/icons/user-circle-placeholder.svg');

        if (isset($user->profile_image)) {
            $imageUrl = $user->profile_image->url;
        }

        return '<img loading="lazy"' .
            ($options['id'] ? ' id="' . $options['id'] . '"' : '') .
            ' width="' . $options['width'] . '" height="' . $options['height'] . '" class="' . $options['class'] . '"' .
            ' title="' . $user['firstname'] . ' ' . $user['lastname'] . '" alt="' . $user['firstname'] . ' ' . $user['lastname'] . '" ' .
            ' src="' . $imageUrl . '" onerror="this.src=\'' . $default . '\'"/>';
    }
}
