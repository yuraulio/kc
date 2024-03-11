<?php

namespace App\Helpers;

use App\Model\Admin\MediaFile;
use Illuminate\Http\Request;

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
        // We check if this image is in CMS File indexed
        $image = $user->image;
        if($image){
            $file = MediaFile::where('name', $image->original_name)->first();
            if(!$file){
                // The file not exists. Let's create one
                $path = str_replace('storage/uploads','public/uploads',storage_path($image->path.$image->original_name));

                if(file_exists($path)){

                    $file = new \Illuminate\Http\UploadedFile($path, $image->original_name);

                    $request = new Request();
                    $request->merge(['alt_text' => $file->getFilename()]);
                    $request->merge(['link' => $file->getFilename()]);
                    $request->merge(['directory' => 17]);
                    $request->merge(['jpg' => $file->getExtension() == 'jpg' ? true : false]);
                    $request->files->add(['file' => $file]);

                    app('App\Http\Controllers\Admin_api\MediaController')->uploadImage($request);
                }
            }
        }

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
