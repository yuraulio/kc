<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Model\Option;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function index()
    {
        $data['social_media'] = get_social_media();

        return view('admin/social/index', $data);
    }

    public function create(Request $request)
    {
        if ($request->new == 1) {
            $data['new'] = 'yes';
        }

        return view('admin/social/create', $data);
    }

    public function store(Request $request)
    {
        $social = get_social_media();

        $social[strtolower($request->name)]['title'] = $request->name;
        $social[strtolower($request->name)]['url'] = $request->url;
        $social[strtolower($request->name)]['target'] = $request->target;

        $social = json_encode($social);

        Option::where('name', 'social_media')->update(['settings' => $social]);

        return redirect('/admin/social');
    }

    public function edit(Request $request)
    {
        //dd($request->all());
        $social = get_social_media();
        $data['social'] = $social[$request->social];
        //dd($data['social']);

        return view('/admin/social/edit', $data);
    }

    public function update(Request $request)
    {
        $social = get_social_media();

        $social[strtolower($request->title)]['url'] = $request->url;
        $social[strtolower($request->title)]['target'] = $request->target;

        Option::where('name', 'social_media')->update(['settings' => $social]);

        return redirect('/admin/social');
    }
}
