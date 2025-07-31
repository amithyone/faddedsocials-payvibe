<?php

namespace App\Http\Controllers\User;

use App\Models\GiftItem;
use App\Models\GiftOrder;
use App\Models\ProductDetail;
use App\Models\Referre;
use App\Models\TrackOrder;
use App\Models\User;
use App\Models\Order;
use App\Models\Deposit;
use App\Constants\Status;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\GatewayCurrency;
use Illuminate\Support\Facades\Storage;
use Stripe\Issuing\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user = auth()->user();

        $widget['total_payments'] = Deposit::where('user_id', $user->id)->successful()->sum('amount');
        $widget['total_orders'] = Order::where('user_id', $user->id)->paid()->count();
        $widget['total_tickets'] = SupportTicket::where('user_id', $user->id)->count();

        //$latestDeposits = $user->deposits()->take(5)->get();

        $latestDeposits = Deposit::latest()->where('user_id', Auth::id())->with('gateway', 'order')->orderBy('id', 'desc')->take(10)->get();


        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'user', 'widget', 'latestDeposits'));
    }




    public function refer(request $request)
    {

        if(Auth::user()->refer == null){

            $code = random_int(00000, 99999);
            $url = url('')."/user/register?code=$code";
            User::where('id', Auth::id())->update(['refer' => $url, 'referal_code'=> $code]);
            $data['refer'] = Auth::user()->refer;

            $data['earned'] = Deposit::where('method_code', 6000)->where('status', 1)->sum('amount');
            $data['withdrawal'] = Deposit::where('user_id', Auth::id())->where('status', 3)->sum('final_amo');


            return view($this->activeTemplate . 'user.referal', $data);
        }else{


            $data['earned'] = Deposit::where('method_code', 6000)->where('user_id', Auth::id())->sum('amount');
            $data['withdrawal'] = Deposit::where('user_id', Auth::id())->where('status', 4)->sum('final_amo');
            $data['referal'] = Deposit::latest()->where('method_code', 6000)->where('user_id', Auth::id())->paginate('10');


            $data['refer'] = Auth::user()->refer;
            return view($this->activeTemplate . 'user.referal', $data);


        }



    }
    public function resloveDeposit(Request $request)
    {
        $pageTitle = 'Reslove Deposit';
        $dep = Deposit::where('trx', $request->trx)->first() ?? null;

        if ($dep->status == 1) {
            $notify[] = ['error', "This Transaction has been successful"];
            return back()->withNotify($notify);
        }


        if ($dep == null) {
            $notify[] = ['error', "Transaction has been deleted"];
            return back()->withNotify($notify);
        } else {

            $trx = $request->trx;
            return view($this->activeTemplate . 'user.resolve_deposit', compact('pageTitle', 'trx'));
        }
    }


    public function  resolve_now(request $request)
    {


        $trx = Deposit::where('trx', $request->trx_ref)->first()->status ?? null;

        $ck_trx = (int)$trx;

        if ($ck_trx == 1) {

            $email = Auth::user()->email;
            $message =  "$email |FADDED | is trying to fund and a successful order with orderid $request->trx_ref";
            send_notification2($message);

            $message =  "$email | FADDED | is trying to fund and a successful order with orderid $request->trx_ref";
            send_notification($message);

            $notify[] = ['error', "This Transaction has been successful"];
            return back()->withNotify($notify);
        }



        if ($ck_trx != 0) {

            $email = Auth::user()->email;
            $message =  "$email |FADDED | is trying to fund and a successful order with orderid $request->trx_ref";
            send_notification2($message);

            $message =  "$email | FADDED | is trying to fund and a successful order with orderid $request->trx_ref";
            send_notification($message);

            $notify[] = ['error', "This Transaction has been successful"];
            return back()->withNotify($notify);
        }

        if ($ck_trx == 2) {

            $email = Auth::user()->email;
            $message =  "$email |FADDED | is trying to fund and a successful order with orderid $request->trx_ref";
            send_notification2($message);

            $message =  "$email | FADDED | is trying to fund and a successful order with orderid $request->trx_ref";
            send_notification($message);

            $notify[] = ['error', "This Transaction has been successful"];
            return back()->withNotify($notify);
        }




        if ($ck_trx == 0) {
            $session_id = $request->session_id;
            if ($session_id == null) {
                $notify[] = ['error', "session id or amount cant be empty"];
                return back()->withNotify($notify);
            }


            $curl = curl_init();
            $databody = array(
                'session_id' => "$session_id",
                'ref' => "$request->trx_ref"

            );

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://web.sprintpay.online/api/resolve',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $databody,
            ));

            $var = curl_exec($curl);
            curl_close($curl);
            $var = json_decode($var);

            $message = $var->message ?? null;
            $status = $var->status ?? null;
            $trx = $var->trx ?? null;
            $amount = $var->amount ?? null;

            if ($status == true) {
                User::where('id', Auth::id())->increment('balance', $var->amount);
                Deposit::where('trx', $request->trx_ref)->update(['status' => 1]);


                $user_email = Auth::user()->email;
                $message = "$user_email | $request->trx_ref | $session_id | $var->amount | just resolved deposit | FADDEDs";
                send_notification($message);
                send_notification2($message);
                return back()->with('success', "Transaction successfully Resolved, NGN $amount added to ur wallet");
            }

            if ($status == false) {
                return back()->with('error', $message);
            }


            return back()->with('error', "please try again later");

        }
    }


    public function send_gift(request $request)
    {

        $pageTitle = 'Send Gift';
        $data['order_completed'] = GiftOrder::where('user_id', Auth::id())->where('status', 1)->count();
        $data['order_pending'] = GiftOrder::where('user_id', Auth::id())->where('status', 0)->count();
        $data['gift_items'] = GiftItem::all();
        $data['orders'] = GiftOrder::where('user_id', Auth::id())->get();

        return view($this->activeTemplate . 'user.send-gift', $data);


    }


    public function track_order(request $request)
    {

        $data['order'] = GiftOrder::where('id', $request->id)->first();
        $data['order_track'] = TrackOrder::latest()->where('item_id', $request->id)->get();
        $data['order_status'] = GiftOrder::where('id', $request->id)->first()->status;



        return view($this->activeTemplate . 'user.track-order', $data);


    }

    public function submit_giftorder(request $request)
    {

        $gift = GiftItem::where('id', $request->gift_item)->first()->title ?? null;

        if($gift  == null){
            return back()->with('error', "Please select Item to send");

        }

        $order = new GiftOrder();
        $order->user_id = Auth::id();
        $order->gift_item = $gift;
        $order->address = $request->address;
        $order->note = $request->note;
        $order->qty = $request->qty;
        $order->save();

        return back()->with('message', "Your Order has been received");

    }





    public function depositHistory(Request $request)
    {
        $pageTitle = 'Payment History';
        $deposits = auth()->user()->deposits()->searchable(['trx'])->with('gateway', 'order')->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.deposit_history', compact('pageTitle', 'deposits'));
    }


    public function depositNew(Request $request)
    {
        $pageTitle = 'Fund Wallet';
        
        // Get the amount from request (if provided)
        $amount = $request->amount ?? 0;
        
        // Load all gateway currencies
        $allGateways = GatewayCurrency::where('status', 1)->get();
        
        // Filter gateways based on amount
        $gateway_currency = $allGateways->filter(function($gateway) use ($amount) {
            // PayVibe (method_code 120) - only show for amounts below 10,000
            if ($gateway->method_code == 120) {
                return $amount < 10000;
            }
            
            // Payaza (method_code 119) - show for amounts 10,000 and above
            if ($gateway->method_code == 119) {
                return $amount >= 10000;
            }
            
            // Xtrapay (method_code 118) - show for amounts 10,000 and above
            if ($gateway->method_code == 118) {
                return $amount >= 10000;
            }
            
            // Manual payments (method_code >= 1000) - show for amounts 10,000 and above
            if ($gateway->method_code >= 1000) {
                return $amount >= 10000;
            }
            
            // Other gateways - show for all amounts
            return true;
        });
        
        $deposits = Deposit::latest()->where('user_id', Auth::id())->with('gateway', 'order')->paginate('5');
        return view($this->activeTemplate . 'user.deposit_new', compact('pageTitle', 'gateway_currency', 'deposits', 'amount'));
    }

    public function attachmentDownload($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general = gs();
        $title = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function userData()
    {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.products');
        }
        $pageTitle = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate . 'user.user_data', compact('pageTitle', 'user', 'mobileCode', 'countries'));
    }

    public function rules(Request $request)
    {

        return view($this->activeTemplate . 'user.rules');

    }


    public function terms(Request $request)
    {

        return view($this->activeTemplate . 'user.terms');

    }



    public function why(Request $request)
    {

        return view($this->activeTemplate . 'user.why');

    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.products');
        }

        $validationRule = [
            'firstname' => 'required',
            'lastname' => 'required',
        ];

        if ($user->login_by) {
            if (!$user->email) {
                $validationRule = array_merge($validationRule, [
                    'email' => 'required|string|email|unique:users',
                ]);
            }
            $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
            $countryCodes = implode(',', array_keys($countryData));
            $mobileCodes = implode(',', array_column($countryData, 'dial_code'));
            $countries = implode(',', array_column($countryData, 'country'));
            $validationRule = array_merge($validationRule, [
                'mobile' => 'required|regex:/^([0-9]*)$/',
                'mobile_code' => 'required|in:' . $mobileCodes,
                'country_code' => 'required|in:' . $countryCodes,
                'country' => 'required|in:' . $countries,
            ]);
        }

        $request->validate($validationRule);
        $hasEmail = $user->email ? true : false;
        $general = gs();

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = [
            'country' => $user->login_by ? $request->country : @$user->address->country,
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'city' => $request->city,
        ];
        $user->country_code = $request->country_code;
        $user->mobile = $request->mobile_code . $request->mobile;
        $user->profile_complete = 1;
        if (!$hasEmail) {
            $user->ev = $general->ev ? Status::NO : Status::YES;
            $user->email = $request->email;
        }
        $user->sv = $general->sv ? Status::NO : Status::YES;
        $user->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.products')->withNotify($notify);
    }

    public function orders()
    {
        $pageTitle = 'Orders';

        $orders = Order::where('user_id', auth()->id())
            ->where('status', Status::ORDER_PAID)
            ->searchable(['deposit:trx'])
            ->orderBy('id', 'desc')
            ->with('deposit', 'orderItems')
            ->paginate(getPaginate());




        $count_order = Order::where('user_id', Auth::id())->where('status', 1)->count();
        $order_sum = Order::where('user_id', Auth::id())->where('status', 1)->sum('total_amount');


        return view($this->activeTemplate . 'user.orders', compact('pageTitle', 'orders', 'count_order', 'order_sum'));
    }

    public function orderDetails($id)
    {
        $pageTitle = 'Order Details';
        $order = Order::where('user_id', auth()->id())->where('status', Status::ORDER_PAID)->findOrFail($id);
        $orderItems = OrderItem::whereIn('id', $order->orderItems->pluck('id') ?? [])->with('product', 'productDetail')->paginate(getPaginate());
        $orderd = OrderItem::whereIn('id', $order->orderItems->pluck('id') ?? [])->with('productDetail')->get();

        foreach ($orderd as $product)
        {
            $item[] = $product->productDetail->id;
        }

        $update = ProductDetail::whereIn('id', $item)->update(['is_sold'=>Status::YES]);

        $get_id = $id;

        return view($this->activeTemplate . 'user.order_details', compact('pageTitle', 'order', 'orderItems', 'get_id'));
    }



    public function copy($id)
    {

        $order = Order::where('user_id', auth()->id())->where('status', Status::ORDER_PAID)->findOrFail($id);
        $copyall = OrderItem::whereIn('id', $order->orderItems->pluck('id') ?? [])->with('product', 'productDetail')->get();

        $result = [];
        foreach ($copyall as $data){
            $result[] = $data->productDetail->details;
        }

        $text = implode(PHP_EOL, $result);
        Storage::put('result.txt', $text);
        $filePath = storage_path('app/result.txt');
        return response()->download($filePath, 'result.txt');



    }


    public function send_money_now(request $request){

        $ck_wallet = User::where('id', Auth::id())->first()->ref_wallet;
        if($ck_wallet < $request->amount){
            return back()->with('error', "insufficient Funds");
        }

        User::where('id', Auth::id())->decrement('ref_wallet', $request->amount);
        User::where('id', Auth::id())->increment('balance', $request->amount);


        $depo = new Deposit();
        $depo->user_id = Auth::id();
        $depo->order_id = 0;
        $depo->method_code = 6000;
        $depo->amount = $request->amount;
        $depo->bank_name = $request->bank_name;
        $depo->account_no = $request->account_no;
        $depo->status = 5;
        $depo->save();

        return back()->with('message', 'Withdrawal has been successfully added to your wallet');

    }

    public function send_money(request $request)
    {
        if(Auth::user()->ref_wallet < $request->amount){
            return back()->with('error', "Insufficient Referral Funds");
        }

        User::where('id', Auth::id())->decrement('ref_wallet', $request->amount);
        User::where('id', Auth::id())->increment('balance', $request->amount);

        $data = new Deposit();
        $data->user_id = Auth::id();
        $data->method_code = 1001;
        $data->method_currency = "NGN";
        $data->amount = $request->amount;
        $data->charge = 0;
        $data->rate = 0;
        $data->final_amo = $request->amount;
        $data->btc_amo = 0;
        $data->status = 4;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        $data->save();

        return back()->with('message', "Referral Funds added to wallet successfully");


    }







}
