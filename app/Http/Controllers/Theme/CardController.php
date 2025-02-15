<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store_from_payment(Request $request)
    {
        $user = Auth::user();
        if ($user['stripe_id'] == null) {
            $options = ['name' => $user['firstname'] . ' ' . $user['lastname'], 'email' => $user['email']];
            $user->createAsStripeCustomer($options);

            $stripe_ids = json_decode($user->stripe_ids, true) ? json_decode($user->stripe_ids, true) : [];
            $stripe_ids[] = $user->stripe_id;

            $user->stripe_ids = json_encode($stripe_ids);
            $user->save();
        }

        try {
            $card['brand'] = '';
            $card['last4'] = '';
            $card['exp_month'] = '';
            $card['exp_year'] = '';

            $user->addPaymentMethod($request->payment_method);
            $user->updateDefaultPaymentMethod($request->payment_method);

            if ($user->defaultPaymentMethod()->card) {
                $card['brand'] = $user->defaultPaymentMethod()->card->brand;
                $card['last4'] = $user->defaultPaymentMethod()->card->last4;
                $card['exp_month'] = $user->defaultPaymentMethod()->card->exp_month;
                $card['exp_year'] = $user->defaultPaymentMethod()->card->exp_year;
            }

            return response()->json([
                'success' => true,
                'card' => $card,
                'payment_method' => $request->payment_method,
                'id' => Auth::user()->createSetupIntent()->client_secret,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Cartalyst\Stripe\Api\Exception\ServerErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Stripe\Exception\CardException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        }
    }

    public function storePaymentMyaccount(Request $request)
    {
        $user = Auth::user();
        if ($user['stripe_id'] == null) {
            $options = ['name' => $user['firstname'] . ' ' . $user['lastname'], 'email' => $user['email']];
            $user->createAsStripeCustomer($options);

            $stripe_ids = json_decode($user->stripe_ids, true) ? json_decode($user->stripe_ids, true) : [];
            $stripe_ids[] = $user->stripe_id;

            $user->stripe_ids = json_encode($stripe_ids);
            $user->save();
        }

        try {
            $card['brand'] = '';
            $card['last4'] = '';
            $card['exp_month'] = '';
            $card['exp_year'] = '';

            $user->addPaymentMethod($request->payment_method);
            $user->updateDefaultPaymentMethod($request->payment_method);

            $data['defaultPaymetnt'] = [];
            $data['defaultPaymetntId'] = -1;
            $card = $user->defaultPaymentMethod() ? $user->defaultPaymentMethod()->toArray() : [];

            if (!empty($card)) {
                $data['defaultPaymetntId'] = $card['id'];
                $data['defaultPaymetnt'][] = ['brand' => $card['card']['brand'], 'last4' => $card['card']['last4'],
                    'exp_month' => $card['card']['exp_month'], 'exp_year' => $card['card']['exp_year']];
            }

            $data['cards'] = $user->paymentMethods()->toArray();

            return response()->json([
                'success' => true,
                'defaultPaymetntId' => $data['defaultPaymetntId'],
                'default_card' => $data['defaultPaymetnt'],
                'cards' => $data['cards'],
                'payment_method' => $request->payment_method,
                'id' => Auth::user()->createSetupIntent()->client_secret,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Laravel\Cashier\Exceptions\IncompletePayment $e) {
            return response()->json([
                'success' => false,
                'message' => 'Your card or bank account has insufficient funds or a limit. Please contact your bank and try again later.',

            ]);
        } catch(\Cartalyst\Stripe\Api\Exception\ServerErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        }
    }

    public function updatePaymentMethod(Request $request)
    {
        try {
            $user = Auth::user();
            $user->updateDefaultPaymentMethod($request->card_id);

            return back();
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Laravel\Cashier\Exceptions\IncompletePayment $e) {
            return response()->json([
                'success' => false,
                'message' => 'Your card or bank account has insufficient funds or a limit. Please contact your bank and try again later.',

            ]);
        } catch(\Cartalyst\Stripe\Api\Exception\ServerErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        }
    }

    public function removePaymentMethod(Request $request)
    {
        try {
            $user = Auth::user();
            $paymentMethod = $user->findPaymentMethod($request->card_id);
            $paymentMethod->delete();

            return back();
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Laravel\Cashier\Exceptions\IncompletePayment $e) {
            return response()->json([
                'success' => false,
                'message' => 'Your card or bank account has insufficient funds or a limit. Please contact your bank and try again later.',

            ]);
        } catch(\Cartalyst\Stripe\Api\Exception\ServerErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),

            ]);
        } catch(\Stripe\Exception\CardException $e) {
            \Session::put('dperror', $e->getMessage());

            return '/cart';
        }
    }
}
