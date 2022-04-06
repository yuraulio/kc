<?php

namespace App\Library;

use App\Model\Admin\Setting;
use Exception;
use Illuminate\Support\Facades\Log;

class Cache
{
    public static function getCmsMode() {
        try {
            return cache()->remember("cmsMode", 3600, function () {
                return Setting::whereSetting("cms_mode")->firstOrFail()->value;
            });
        } catch (Exception $e) {
            Log::error("Failed to get csmMode from cache.");
            Log::error($e);
            return Setting::OLD_PAGES;
        }
    }
}