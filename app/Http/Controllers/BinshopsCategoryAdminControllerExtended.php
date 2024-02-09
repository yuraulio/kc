<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use BinshopsBlog\Events\CategoryEdited;
use BinshopsBlog\Helpers;
use BinshopsBlog\Middleware\LoadLanguage;
use BinshopsBlog\Middleware\UserCanManageBlogPosts;
use BinshopsBlog\Models\BinshopsCategory;
use BinshopsBlog\Models\BinshopsCategoryTranslation;
use BinshopsBlog\Requests\UpdateBinshopsBlogCategoryRequest;
use Illuminate\Http\Request;

/**
 * Class BinshopsCategoryAdminController.
 */
class BinshopsCategoryAdminControllerExtended extends Controller
{
    /**
     * BinshopsCategoryAdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(UserCanManageBlogPosts::class);
        $this->middleware(LoadLanguage::class);
        $this->middleware(\App\Http\Middleware\BlogCategoryMiddleware::class);
    }

    /**
     * Save submitted changes.
     *
     * @param UpdateBinshopsBlogCategoryRequest $request
     * @param $categoryId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_category(Request $request, $categoryId)
    {
        $request->validate([
            'category_name' => ['required', 'string', 'min:1', 'max:200'],
            'slug' => ['required', 'alpha_dash', 'max:100', 'min:1'],
            'category_description' => ['nullable', 'string', 'min:1', 'max:5000'],
        ]);

        /** @var BinshopsCategory $category */
        $category = BinshopsCategory::findOrFail($categoryId);
        $language_id = $request->get('language_id');
        $translation = BinshopsCategoryTranslation::where(
            [
                ['lang_id', '=', $language_id],
                ['category_id', '=', $categoryId],
            ]
        )->first();
        $category->fill($request->all());
        $translation->fill($request->all());
        $category->save();
        $translation->save();

        Helpers::flash_message('Saved category changes');
        event(new CategoryEdited($category));

        return redirect($translation->edit_url());
    }
}
