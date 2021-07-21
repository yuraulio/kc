<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Plan;
use App\Model\Event;
use App\Model\Category;

class PlanController extends Controller
{
    private function getEvents($view_tpl = null){

        if(!$view_tpl){
            $events =  Event::where('published',true)->get();
        }else{
            $events = Event::where('view_tpl',$view_tpl)->where('published',true)->get();
        }
 
        return $events;
    }

    private function getCategories(){
        $categories = Category::has('events')->get();
        return $categories;
    }

    public function index()
    {
        //dd('fdsa');

        $data['plans'] =Plan::has('events')->get();
        return view('admin/subscription/plans_list', $data);
    }

    public function create(){

        $plan = new Plan;

        $data['events'] = $this->getEvents('elearning_event');
        $data['noevents'] = $this->getEvents();
        $data['categories'] = $this->getCategories();
        $data['plan'] = $plan;
        $data['event_plans'] = $plan->events()->pluck('event_id')->toArray();
        $data['category_plans'] = $plan->categories()->pluck('category_id')->toArray();
        $data['event_noplans'] = $plan->noEvents()->pluck('event_id')->toArray();

        return view('admin/subscription/create', $data);

    }

    public function store(Request $request){
    

        $skey = env('STRIPE_SECRET');
        $stripe = Stripe::make($skey);      

        $id = 'plan'.time();
        
        $plan = $stripe->plans()->create([
            'id'                   => $id,
            'name'                 => $request->title,
            'amount'               => $request->price,
            'currency'             => 'EUR',
            'interval'             => $request->interval,
            'interval_count'       => $request->interval_count,
            'trial_period_days'    => $request->trial     
        ]);
       

        if($plan){

            $planN = new Plan;

            $planN->name = $request->title;
            $planN->description = $request->description;
            $planN->stripe_plan = $id;
            $planN->cost = $request->price;
            $planN->interval = $request->interval;
            $planN->trial_days = $request->trial;
            $planN->interval_count = $request->interval_count;

            $planN->save();

            if($request->events){
                foreach($request->events as $event){
                    $planN->events()->attach($event);
                }
            }

            if($request->categories){
                foreach($request->categories as $category){
                    $planN->categories()->attach($category);
                }
            }

            if($request->noevents){
                foreach($request->noevents as $event){
                    $planN->noEvents()->attach($event);
                }
            }

            return redirect('edit/plan/'.$planN->id);

        }

    }

    public function edit(Plan $plan){

        $data['events'] = $this->getEvents('elearning_english');
        $data['noevents'] = $this->getEvents();
        $data['categories'] = $this->getCategories();
        $data['plan'] = $plan;
        $data['event_plans'] = $plan->events()->pluck('content_id')->toArray();
        $data['category_plans'] = $plan->categories()->pluck('category_id')->toArray();
        $data['event_noplans'] = $plan->noEvents()->pluck('content_id')->toArray();

        return view('admin/subscription/create', $data);

    }

    public function update(Request $request, Plan $plan){

       
        
        if($plan){

            $plan->name = $request->title;
            $plan->description = $request->description;
    
           //$plan->cost = $request->price;
           //$plan->interval = $request->interval;
           //$plan->interval_count = $request->interval_count;

            $plan->save();
            $plan->events()->detach();
            $plan->categories()->detach();
            $plan->noEvents()->detach();
            
            if($request->events){
                foreach($request->events as $event){
                    $plan->events()->attach($event);
                }
            }

            if($request->categories){
                foreach($request->categories as $category){
                    $plan->categories()->attach($category);
                }
            }

            if($request->noevents){
                foreach($request->noevents as $event){
                    $plan->noEvents()->attach($event);
                }
            }

            return redirect('edit/plan/'.$plan->id);

        }


    }


    public function removePlan(Request $request)
    {
        //$skey = env('STRIPE_SECRET');
        //$stripe = Stripe::make($skey);

        $plan = DB::table('plans')->where('id', $request->id)->first();

        //dd(gettype($plan->stripe_plan));

        $plan1 = $stripe->plans()->delete($plan->stripe_plan);
        //dd($plan);
        //dd('from remove');
        if(DB::table('plans')->where('id', $request->id)->delete() && $plan1){
            return response()->json([
                'success' => true,
                'data' => $request->id
            ]);
        }else{
            return response()->json([
                'success' => false,
                'data' => $request->id
            ]);
        }
        //dd($request->id);
        
    }
}
