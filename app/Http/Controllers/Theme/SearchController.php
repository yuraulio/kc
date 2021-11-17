<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Event;
use App\Model\Instructor;
use Illuminate\Support\Str;


class SearchController extends Controller
{
    /*public function searchForTerm(Request $request)
    {
        
        $this->validate($request, [
            'search_term' => 'required',
        ]);

        $data['list'] = [];

        if($request->search_term != ''){
            $data['search_term'] = strtolower($request->search_term);
            $data['search_term_slug'] = Str::slug($data['search_term'], "-");
            //dd($data['search_term']);
        }else{
            $data['search_term'] = '';
        }

        if(strlen($data['search_term_slug']) > 2){

            $data['events'] = Event::whereIn('status',[0,2,3])
            ->where('published', 1)
            ->with('city', 'summary1', 'slugable', 'ticket')
            ->orderByDesc('published_at')
            ->paginate(10)
            ->getCollection();


            $data['instructor'] = Instructor::where('status',1)->get();
            //dd($data['events']);

            foreach($data['events'] as $event){
                $slug = $event['slugable']['slug'];

                $find = strpos($slug, $data['search_term_slug']);


                //var_dump($find);

                if($find !== false){
                    $data['list'][] = $event;
                }
            }
        }

        //dd($data['events']);
        return view('theme.search.search_results', $data);

    }*/

    public function searchForTerm(Request $request)
    {
        
        $this->validate($request, [
            'search_term' => 'required',
        ]);

        $data['list'] = [];

        if($request->search_term != ''){
            $data['search_term'] = $request->search_term;//strtolower($request->search_term);
            $data['search_term_slug'] = Str::slug($data['search_term'], "-");
            //dd($data['search_term']);
        }else{
            $data['search_term'] = '';
        }
        $searchString = $request->search_term;
        //if(strlen($data['search_term_slug']) > 2){

            $data['list'] = Event::
                                //whereIn('status',[0/*,2,3*/])->
                                
                                whereHas('category', function ($query) use ($searchString){
                                    $query->where('name', 'like', '%'.$searchString.'%');
                                })
                                ->orWhereHas('topic', function ($query) use ($searchString){
                                    $query->where('title', 'like', '%'.$searchString.'%');
                                })
                                ->orWhereHas('city', function ($query) use ($searchString){
                                    $query->where('name', 'like', '%'.$searchString.'%');
                                })
                                ->orWhereHas('lessons', function ($query) use ($searchString){
                                    $query->where('title', 'like', '%'.$searchString.'%');
                                })
                                
                                ->where('status',0)
                                ->where('published',0)
                                ->with('city', 'summary1', 'slugable', 'ticket')
                                ->orderByDesc('published_at')
                                //->paginate(10)
                                //->getCollection();
                                ->get();
            
                
            foreach($data['list'] as $key => $event){
                if($event['status'] != 0 || $event['published'] != 1){
                    unset($data['list'][$key]);
                }
            }
            //dd($data['list']);
            $data['instructor'] = Instructor::where('status',1)->get();

        //}

        //dd($data['events']);
        return view('theme.search.search_results', $data);

    }

}
