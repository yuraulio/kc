<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Model\Admin\Page;
use CodexShaper\Menu\Models\Menu;
use CodexShaper\Menu\Models\MenuItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Get comments.
     *
     * @return AnonymousResourceCollection
     */
    public function clone($id)
    {
        try {
            // clone menu
            $order = Menu::max('order');
            $menu = Menu::find($id);

            $cloneName = $menu->name . ' clone';
            // check if name exists
            if (Menu::whereName($cloneName)->first()) {
                $cloneName = $cloneName . '-' . Str::random(8);
            }

            $clone = new Menu();
            $clone->name = $cloneName;
            $clone->slug = Str::slug($cloneName);
            $clone->url = $menu->url;
            $clone->order = $order + 1;
            $clone->custom_class = $menu->custom_class;

            $clone->save();

            // clone menu items
            $items = MenuItem::where('menu_id', $id)->get();
            foreach ($items as $item) {
                $parent_id = $item->parent_id;

                $order = ($parent_id)
                ? MenuItem::where('parent_id', $parent_id)->max('order')
                : MenuItem::whereNull('parent_id')->max('order');

                $menuItem = new MenuItem();
                $menuItem->menu_id = $clone->id;
                $menuItem->title = $item->title;
                $menuItem->slug = Str::slug($item->title);
                $menuItem->url = $item->url;
                $menuItem->route = $item->route;
                $menuItem->params = $item->params;
                $menuItem->controller = $item->controller;
                $menuItem->middleware = $item->middleware;
                $menuItem->target = $item->target;
                $menuItem->parent_id = $parent_id;
                $menuItem->order = $order + 1;
                $menuItem->icon = $item->icon;
                $menuItem->custom_class = $item->custom_class;

                $menuItem->save();
            }

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to clone menu. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
