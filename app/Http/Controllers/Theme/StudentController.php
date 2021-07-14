<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Media;
use App\Model\User;
use App\Model\Topic;
use App\Model\Lesson;
use App\Model\Summary;
use App\Model\Instructor;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;

class StudentController extends Controller
{
    public function index(){
        //dd(getenv('STRIPE_SECRET'));
        //Stripe::setApiKey($ev->paymentMethod->first()->processor_options['secret_key']);
       // Stripe::setApiKey(getenv('STRIPE_SECRET'));
       //session()->put('payment_method',2);
        //$stripe = new \Stripe\StripeClient(getenv("STRIPE_KEY"));
        //dd('from student controller');
        $user = Auth::user();

        $data['elearningAccess'] = 0;
        $data['cards'] = [];
        $data['subscriptionAccess'] = [];
        $data['mySubscriptions'] = [];

        $data['user'] = User::with('image', 'events.city', 'events.summary1')->find($user->id);

        //$paymentMethods = $user->paymentMethods();
        //dd($paymentMethods);

        //count elearning

        foreach($data['user']['events'] as $key => $event){

            //if elearning assign progress for this event
            if($event->is_elearning_course()){
                //$data['user']['events'][$key]['video_progress'] = $event->progress($user);
                //$data['user']['events'][$key]['topics'] = $event->topic;
                //$data['user']['events'][$key] = $event->topicsLessonsInstructors();
                $data['user']['events'][$key]['topics'] = $event->topicsLessonsInstructors()['topics'];
                $data['user']['events'][$key]['video_progress'] = intval($event->progress($user));
                //dd($event->topicsLessonsInstructors()['instructors']);
                //dd($data['user']['events'][$key]);

            }

            $data['instructors'] = Instructor::all()->groupby('id');
            $find = false;
            $view_tpl = $event['view_tpl'];
            $find = strpos($view_tpl, 'elearning');
            //dd($find);

            if($find !== false){
                $find = true;
                $data['elearningAccess'] = $find;
            }

            //dd($event->topic);

            //dd($data);

        }
        //dd($data->toArray());



        return view('theme.myaccount.student', $data);
    }

    public function removeProfileImage(Request $request){
        $user = Auth::user();
        $media = $user->image;

        $path_crop = explode('.', $media['original_name']);
        $path_crop = $media['path'].$path_crop[0].'-crop'.$media['ext'];
        $path_crop = substr_replace($path_crop, "", 0, 1);

        $path = $media['path'].$media['original_name'];
        $path = substr_replace($path, "", 0, 1);

        if(file_exists($path_crop)){
            //unlink crop image
            unlink($path_crop);  
        }

        //unlink image
        unlink($path);

        //null db image
        $media->original_name = null;
        $media->name = null;
        $media->path = null;
        $media->ext = null;
        $media->file_info = null;
        $media->height = null;
        $media->width = null;
        $media->details = null;
        $media->save();

    }
}
