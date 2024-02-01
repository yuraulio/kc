<?php

namespace App\Helpers;

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

        if (!empty($user['image'])) {
            $imageUrl = get_profile_image($user['image']);
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
        if (!empty($user['email'])) {
            $image = md5(strtolower($user['email']));

            $imageUrl = 'https://www.gravatar.com/avatar/' . $image . '?s=' . ($options['width'] < 64 ? 64 : $options['width'] ) . '&d=' .
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
