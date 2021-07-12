<?php

namespace App\Http\Controllers;

use App\Model\Menu;
use App\Model\Type;
use App\Model\Delivery;
use App\Model\Category;
use Illuminate\Http\Request;
use App\Http\Requests\MenuRequest;

class MenuController extends Controller
{


    public function index(Menu $model)
    {
        $this->authorize('manage-items', User::class);

        //Group by key
        $result = array();
        foreach ($model->all() as $element) {
            $result[$element['name']][] = $element;
        }

        return view('admin.menu.index', ['menu' => $result]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categories'] = Category::has('events')->get();
        $data['types'] = Type::all();
        $data['deliveries'] = Delivery::all();

        return view('admin.menu.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request, Menu $model)
    {
        foreach($request->menu as $menu)
        {
            $menu1 = explode("-", $menu);
            $model1 = app("App\\Model"."\\" . $menu1[0]);
            $id = $menu1[1];

            $a = $model1::find($id);

            $menu = new Menu(['name' => $request->name]);

            $menu->menuable_id = $id;
            $menu->menuable_type = "App\\Model\\". $menu1[0];

            $menu->save();

        }
        return redirect()->route('menu.index')->withStatus(__('Menu successfully created.'));
    }

    public function store_item(Request $request){

        $menu1 = explode("-", $request->menu);
        //dd($menu);
        $type = app("App\\Model"."\\" . $menu1[0]);
        $id = $menu1[1];

        $find_item = $type::find($id);

        $menu = new Menu(['name' => $request->name]);

        $menu->menuable_id = $id;
        $menu->menuable_type = "App\\Model"."\\" . $menu1[0];

        $menu->save();

        $data['find_item'] = $find_item;
        $data['menu'] = $menu;

        return response()->json([
            'success' => __('Item successfully assigned.'),
            'data' => $data,
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        $name = $menu['name'];

        $menus = Menu::where('name', $name)->get();
        $result = array();
        foreach ($menus as $key => $element) {
            $result[$element['name']][] = $element;

            $model = app($element['menuable_type']);
            $element->data = $model::find($element['menuable_id']);

        }

        return view('admin.menu.edit', compact('result', 'name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $menu = Menu::find($request->id);
        $menu->delete();

        return redirect()->route('menu.index')->withStatus(__('Menu item successfully removed.'));
    }

    public function remove_item(Request $request)
    {
        $menu = Menu::find($request->item_id);
        $menu->delete();

        return response()->json([
            'success' => __('Item successfully removed.'),
            'data' => $request->item_id,
        ]);
    }

    public function fetchAllMenu(Request $request)
    {
        //dd($request->all());
        $assigned_menu = Menu::where('name', $request->name)->get();
        //dd($assigned_menu);

        $categories = Category::has('events')->get();
        //dd($categories);
        $data = [];

        foreach($categories as $key => $cat){
            $found = false;
            //dd($cat->getTable());
            foreach($assigned_menu as $ass_cat){

                if($cat['id'] == $ass_cat['menuable_id'] && $ass_cat['menuable_type'] == 'App\Model\Category'){
                    $found = true;
                }

            }
            if(!$found){

                $data['categories'][$key] = $cat;


            }
        }

        $types = Type::all();

        foreach($types as $key => $cat){
            $found = false;
            //dd($cat->getTable());
            foreach($assigned_menu as $ass_cat){

                if($cat['id'] == $ass_cat['menuable_id'] && $ass_cat['menuable_type'] == 'App\Model\Type'){
                    $found = true;
                }

            }
            if(!$found){

                $data['types'][$key] = $cat;


            }
        }

        $deliveries = Delivery::all();

        foreach($deliveries as $key => $cat){
            $found = false;
            //dd($cat->getTable());
            foreach($assigned_menu as $ass_cat){

                if($cat['id'] == $ass_cat['menuable_id'] && $ass_cat['menuable_type'] == 'App\Model\Delivery'){
                    $found = true;
                }

            }
            if(!$found){

                $data['deliveries'][$key] = $cat;


            }
        }

        return response()->json([
            'success' => __('Menu successfully fetched.'),
            'data' => $data,
        ]);
    }
}
