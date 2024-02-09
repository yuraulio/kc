<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Model\Option;
use App\Model\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
{
    public function index()
    {
        $data['paymentMethods'] = PaymentMethod::all();

        return view('admin.payment_methods.index', $data);
    }

    public function create()
    {
        $data['method'] = new PaymentMethod;
        $data['form_type'] = 'create';
        $data['availableProcessors'] = config('processors')['processors'];

        return view('admin.payment_methods.add_method', $data);
    }

    public function store(Request $request)
    {
        $data = $this->handleSubmits($request);

        $option = Option::where('name', 'payments_invoice')->first();
        $invoicesNumber = json_decode($option->settings, true);

        $method = PaymentMethod::create($data);
        if ($method) {
            if (!isset($invoicesNumber[$method->id])) {
                $invoicesNumber[$method->id] = 1;
                $option->settings = json_encode($invoicesNumber);
                $option->save();
            }

            return redirect('admin/payment-methods/edit/' . $method->id);
        } else {
            return redirect('admin/payment-methods');
        }
    }

    public function edit($method_id = 0)
    {
        $data['method'] = PaymentMethod::findOrFail($method_id)->toArray();

        $data['form_type'] = 'edit';
        $data['availableProcessors'] = config('processors')['processors'];

        $data['processor_config'] = get_processor_config($data['method']['processor_id']);
        $data['html'] = '';
        $data['html_test'] = '';
        $data['status'] = 0;

        if ($data['processor_config']) {
            $data['html'] = view('admin.payment_methods.edit_processor.' . $data['processor_config']['tpl'], $data)->render();
            $data['html_test'] = view('admin.payment_methods.edit_processor.' . $data['processor_config']['tpl'] . '_test', $data)->render();
            $data['status'] = 1;
        }

        return view('admin.payment_methods.add_method', $data);
    }

    public function update(Request $request, PaymentMethod $method)
    {
        $data = $this->handleSubmits($request);
        $method->update($data);

        $option = Option::where('name', 'payments_invoice')->first();
        $invoicesNumber = json_decode($option->settings, true);
        if (!isset($invoicesNumber[$method->id])) {
            $invoicesNumber[$method->id] = 1;
            $option->settings = json_encode($invoicesNumber);
            $option->save();
        }

        return redirect('admin/payment-methods/edit/' . $method->id);
    }

    private function handleSubmits($request)
    {
        $input = $request->except('_token');

        //dd($input);

        if (isset($input['processor'])) {
            $input['processor_options'] = encrypt(json_encode($input['processor']));
        } else {
            $input['processor_options'] = encrypt(json_encode([]));
        }

        if (isset($input['test_processor'])) {
            $input['test_processor_options'] = encrypt(json_encode($input['test_processor']));
        } else {
            $input['test_processor_options'] = encrypt(json_encode([]));
        }

        if ($input['processor_id']) {
            $input['processor_config'] = get_processor_config($input['processor_id']);
            $input['method_slug'] = $input['processor_config']['slug'];
            $input['processor_config'] = encrypt(json_encode($input['processor_config']));
        //$input['test_processor_config'] = encrypt(json_encode($input['test_processor_config']));
        } else {
            $input['processor_config'] = encrypt(json_encode([]));
            //$input['test_processor_config'] = encrypt(json_encode([]));
            $input['method_slug'] = '';
        }

        unset($input['processor']);
        unset($input['tmp_processor_str']);
        unset($input['form_type']);

        //dd($input);
        return $input;
    }
}
