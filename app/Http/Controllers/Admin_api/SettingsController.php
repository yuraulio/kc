<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Model\Admin\Page;
use App\Model\Admin\Setting;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Return settings
     *
     * @return JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', Setting::class, Auth::user());

        return Setting::all();
    }

    /**
     * Update settings
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        try {
            $setting = Setting::find($id);

            $this->authorize('update', $setting, Auth::user());

            $setting->value = $request->value;
            $setting->save();

            if ($setting->setting == "cms_mode") {
                cache()->forget("cmsMode");
            }

            return $setting;
        } catch (Exception $e) {
            Log::error("Failed to edit setting. ", [$e]);
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
