<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PaypalPayment;
use App\Project;
use App\User;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    public function createTransaction(Request $request, $project_id)
    {
        $user = Auth::user();
        $request->session()->put('project_id', $project_id);
        // $users = User::pluck('email', 'id');
        $project_payment = 0;
        foreach ($user->projects as $project) {
            if ($project->is_paid == 1)
                $project_payment += 1;
        }
        return view('paypal.transaction', compact('user', 'project_payment'));
    }

    public function processTransaction(Request $request, $amount)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('successTransaction'),
                "cancel_url" => route('cancelTransaction'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $amount
                    ]
                ]
            ]
        ]);
        $user = Auth::user();
        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            $error = $response['message'] ?? "something went wrong";
            return view('paypal.cancel-message', compact('error', 'user'));
        } else {
            $error = $response['message'] ?? "something went wrong";
            return view('paypal.cancel-message', compact('error', 'user'));
        }
    }

    public function successTransaction(Request $request)
    {
        $project_id = $request->session()->get('project_id');
        $user = Auth::user();
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $paypal_payment = PaypalPayment::create([
                'project_id' => $project_id,
                'tnx_id' => $response['id'],
                'payer_id' => $response['payer']['payer_id'],
                'payer_name' => $response['payer']['name']['given_name'] . " " . $response['payer']['name']['surname'],
                'payer_email' => $response['payer']['email_address'],
                'amount' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'],
                'currency' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'],
                'status' => $response['status'],
            ]);
            if ($paypal_payment) {

                $project = Project::findOrFail($project_id);
                $project->update([
                    'is_paid' => true
                ]);
                $request->session()->forget('project_id');
            }
            return view('paypal.success-message', compact('user'));
        } else {
            $error = $response['message'] ?? "something went wrong";
            return view('paypal.cancel-message', compact('error', 'user'));
        }
    }

    public function cancelTransaction(Request $request)
    {
        $user = Auth::user();
        $error = $request['message'] ?? "Something went wrong";
        return view('paypal.cancel-message', compact('error', 'user'));
    }
}
