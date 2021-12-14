<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
namespace App\Http\Controllers;

use Storage;
use App\Model\User;
use App\Model\Dropbox;
use App\Model\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of the categories
     *
     * @param \App\Model\Category  $model
     * @return \Illuminate\View\View
     */
    public function index(Category $model)
    {


        return view('global_settings.categories.index', ['categories' => $model->orderBy('priority','asc')->get()]);
    }

    /**
     * Show the form for creating a new category
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $li = Storage::disk('dropbox');

        $data['folders'] = [];
        if($li) {

            $folders = $li->listContents();
            //dd($folders);
            //$data['folders'][0] = 'Select Dropbox Folder';
            foreach ($folders as $key => $row) {
                if($row['type'] == 'dir') :
                    $data['folders'][$row['basename']] = $row['basename'];
                endif;
            }
        }

        return view('global_settings.categories.create', $data);
    }

    /**
     * Store a newly created category in storage
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @param  \App\Model\Category  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request, Category $model)
    {
        if($request->show_homepage == 'on'){
            $show_homepage = 1;
        }else{
            $show_homepage = 0;
        }

        $countCategory = count(Category::all()) + 1;

        $request->request->add(['show_homepage' => $show_homepage]);
        $request->request->add(['priority' => $countCategory]);

        $model = $model->create($request->all());
        $model->createSlug($request->slug);
        if($request->folder_name != null){
            //foreach($request->folder_name as $folder_name){
                $exist_dropbox = Dropbox::where('folder_name', $request->folder_name)->first();
                //dd($exist_dropbox);
                if($exist_dropbox){
                    //dd($model->with('dropbox')->get());
                    $model->dropbox()->attach([$exist_dropbox->id]);
                }else{
                    return redirect()->route('global.index')->withErrors('Update Dropbox Please!!');
                }


            //}
        }


        return redirect()->route('global.index')->withStatus(__('Category successfully created.'));
    }

    /**
     * Show the form for editing the specified category
     *
     * @param  \App\Model\Category  $category
     * @return \Illuminate\View\View
     */
    public function edit(Category $category)
    {
        $data['folders'] = [];
        //dd($category->dropbox()->get());

        $data['slug'] = $category->slugable;
        $li = Storage::disk('dropbox');

        if($li) {

            $folders = $li->listContents();
            //dd($folders);
            //$data['folders'][0] = 'Select Dropbox Folder';
            foreach ($folders as $key => $row) {
                //dd($row);
                if($row['type'] == 'dir') :
                    $data['folders'][$row['basename']] = $row['basename'];
                endif;
            }
            //dd($data['folders']);

            //dd($category->with('dropbox')->get());
            $already_assign = $category->dropbox;
            //dd($already_assign);

        }

        return view('global_settings.categories.edit', compact('category', 'data', 'already_assign'));
    }

    /**
     * Update the specified category in storage
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @param  \App\Model\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request, Category $category)
    {
        if($request->show_homepage == 'on'){
            $show_homepage = 1;
        }else{
            $show_homepage = 0;
        }

        $request->request->add(['show_homepage' => $show_homepage]);
        $category->update($request->all());

        $ids = [];

        //foreach($request->folder_name as $folder_name){

            if($request->folder_name != null ){
                $exist_dropbox = Dropbox::where('folder_name', $request->folder_name)->first();
                if($exist_dropbox){
                    array_push($ids, $exist_dropbox->id);
                    //$model->dropbox()->attach($exist_dropbox->id,['category_id' => $model->id]);
                }else{
                    return redirect()->route('global.index')->withError('Update Dropbox First.');
                }

                $category->dropbox()->sync($ids);
            }


        //}

        return redirect()->route('global.index')->withStatus(__('Category successfully updated.'));
    }

    /**
     * Remove the specified category from storage
     *
     * @param  \App\Model\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        if (!$category->topic->isEmpty()) {
            return redirect()->route('global.index')->withErrors(__('This category has items attached and can\'t be deleted.'));
        }

        $category->delete();

        return redirect()->route('global.index')->withStatus(__('Category successfully deleted.'));
    }

    public function orderCategory(Request $request){
        foreach($request->order as $key => $order){

            if(!$category = Category::find($key)){
                continue;
            }

            $category->priority = $order;
            $category->save();

        }

        return response()->json([
            'success' => true 
        ]);

    }

}
