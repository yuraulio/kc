<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class CardController extends Controller
{
    public function store_from_payment(Request $request){
      
        /*$this->validate($request, [
            'payment_method' => 'required',
            'exp_month' => 'required',
            'cvv' => 'required',
            'exp_year' => 'required',
        ]);*/
    
        $user = Auth::user();	
        if($user['stripe_id'] == null){
           
            $options=['name' => $user['firstname'] . ' ' . $user['lastname'], 'email' => $user['email']];
            $user->createAsStripeCustomer($options);

            $stripe_ids = json_decode($user->stripe_ids,true);
            $stripe_ids[] = $user->stripe_id;

            $user->stripe_ids = json_encode($stripe_ids);
            $user->save();

            //dd($user);
        }


        try{

            $card['brand'] = '';
            $card['last4'] = '';
            $card[ 'exp_month'] = '';
            $card['exp_year'] = '';
           
            //dd($request->all());
            $user->addPaymentMethod($request->payment_method);
            $user->updateDefaultPaymentMethod($request->payment_method);
           
            if($user->defaultPaymentMethod()->card){
                $card['brand'] = $user->defaultPaymentMethod()->card->brand;
                $card['last4'] = $user->defaultPaymentMethod()->card->last4;
                $card[ 'exp_month'] = $user->defaultPaymentMethod()->card->exp_month;
                $card['exp_year'] = $user->defaultPaymentMethod()->card->exp_year;
            }

           return response()->json([
                'success' => true,
                'card' => $card,
                'payment_method' => $request->payment_method
            ]);
          
        } catch (Exception $e) {
           
            return response()->json([
                'success' => false,
              	'message' => $e->getMessage()
                
              ]);
            //\Session::flash('stripe-error',$e->getMessage());
            // return redirect('/info/order_error');
        }
        catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
           
            return response()->json([
                'success' => false,
              	'message' => $e->getMessage()
                
              ]);
           // \Session::flash('stripe-error',$e->getMessage());
             //return redirect('/info/order_error');
        }
        catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
           
            return response()->json([
                'success' => false,
              	'message' => $e->getMessage()
                
              ]);
            //\Session::flash('stripe-error',$e->getMessage());
        }
        catch(\Cartalyst\Stripe\Api\Exception\ServerErrorException $e) {
        
            return response()->json([
                'success' => false,
              	'message' => $e->getMessage()
                
              ]);
            //\Session::flash('stripe-error',$e->getMessage());
           
        }
        
    }
}
