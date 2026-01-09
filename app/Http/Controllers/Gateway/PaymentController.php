<?php

namespace App\Http\Controllers\Gateway;

use App\Models\Bought;
use App\Models\Referre;
use App\Models\User;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Product;
use App\Constants\Status;
use App\Models\OrderItem;
use App\Models\CouponCode;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use App\Models\GatewayCurrency;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\WebhookService;

class PaymentController extends Controller
{
    // Handle deposit insertion
    public function depositInsert(Request $request)
    {
        // Handle wallet funding (when no product ID is provided)
        if (!$request->id || $request->id == '0') {
            if ($request->amount < 100) {
                $notify = "Amount cannot be less than 100";
                return back()->with('error', $notify);
            }

            // XtraPay (gateway 118) accepts all amounts - no upper limit
            // Other gateways have a 500,000 maximum limit
            if ($request->gateway != 118 && $request->amount > 500000) {
                $notify = "Amount cannot be more than 500,000";
                return back()->with('error', $notify);
            }

            // Server-side validation: PayVibe cannot be used for amounts over 10,000
            if ($request->gateway == 120 && $request->amount > 10000) {
                $notify = "PayVibe is not available for amounts over ₦10,000. Please select another payment method.";
                return back()->with('error', $notify);
            }

            // Fetch the gateway currency based on the provided method code and currency
            $gate = GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', Status::ENABLE);
            })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();

            if (!$gate) {
                $notify[] = ['error', 'Invalid gateway'];
                return back()->withNotify($notify);
            }

             // Initialize charge variable
        $charge = 0;

        // Check if the payment method is manual (method code 1000)
        if ($gate->method_code == 1000) {
    // Define your manual payment charges
    if ($request->amount < 5000) {
        $charge = 0; // No charges for amounts less than 5000
    } else {
        $fixedCharge = 0; // Fixed charge for manual payments
        $percentageCharge = $request->amount * 0.015; // 1.5% of the amount
        $charge = $fixedCharge + $percentageCharge; // Total charge for manual payments
    }
} else {
    // Calculate charges for other gateways based on the funding amount
    if ($request->amount < 5000) {
        $charge = 0; // No charges for amounts less than 5000
    } elseif ($request->amount >= 5000 && $request->amount <= 20000) {
        $charge = 100; // Fixed charge of 100 for amounts between 5000 and 20000
    } else {
        $fixedCharge = 150; // Fixed charge of 150 for amounts over 20000
        $percentageCharge = $request->amount * 0.015; // 1.5% of the amount
        $charge = $fixedCharge + $percentageCharge; // Total charge for amounts over 20000
    }
}
            // Calculate the total payable amount
            $payable = $request->amount + $charge;
            $final_amo = $payable * $gate->rate;

            // Create a new deposit record
            $data = new Deposit();
            $data->user_id = Auth::id();
            $data->method_code = $gate->method_code;
            $data->method_currency = strtoupper($gate->currency);
            $data->amount = $request->amount;
            $data->charge = $charge;
            $data->rate = $gate->rate;
            $data->final_amo = $final_amo;
            $data->btc_amo = 0;
            $data->btc_wallet = "";
            $data->trx = getTrx();
            $data->save();

            // Send webhook if gateway is Xtrapay or PayVibe
            if (strtolower(optional($data->gateway)->alias) === 'xtrapay') {
                WebhookService::sendPendingTransaction($data, Auth::user());
            } elseif (strtolower(optional($data->gateway)->alias) === 'payvibe') {
                WebhookService::sendPendingTransaction($data, Auth::user());
            }

            session()->put('Track', $data->trx);
            return to_route('user.deposit.confirm');
        }

        // Handle product purchase
        return $this->processPurchase($request);
    }

    // Handle purchase independently
    private function processPurchase(Request $request)
    {
        // Validate purchase-specific inputs
        $request->validate([
            'id' => 'required|exists:products,id',
            'qty' => 'required|integer|gt:0',
            'coupon_code' => 'nullable|exists:coupon_codes,coupon_code',
        ]);

        $qty = $request->qty;
        $product = Product::active()->whereHas('category', function($category) {
            return $category->active();
        })->findOrFail($request->id);

        // Calculate available stock dynamically
        $availableStock = ProductDetail::where('product_id', $product->id)
            ->where('is_sold', 0) // 0 means in stock
            ->count();

        // Check stock
        if ($availableStock < $qty) {
            $notify[] = ['error', "Not enough stock available. Only {$availableStock} quantity left"];
            return back()->withNotify($notify);
        }

        // Calculate total amount
        $amount = ($product->price * $qty);
        $user = Auth::user();

        // Apply coupon code if provided
        if ($request->coupon_code) {
            $coupon = CouponCode::where('coupon_code', $request->coupon_code)->first();
            if ($coupon && $coupon->status == Status::ENABLE) {
                $discount = ($coupon->amount / 100) * $amount;
                $amount -= $discount;
            } else {
                return back()->with('error', 'Coupon is not valid');
            }
        }

        // Check if user has sufficient balance
        if ($user->balance < $amount) {
            $notify[] = ['error', 'Insufficient Funds. Please fund your wallet.'];
            return back()->withNotify($notify);
        }

        // Deduct the amount from the user's balance
        $user->decrement('balance', $amount);

        // Create a new order
        $order = new Order();
        $order->user_id = $user->id;
        $order->total_amount = $amount;
        $order->name = $product->name;
        $order->status = Status::PAYMENT_SUCCESS; // Mark order as successful
        $order->save();

        // Get unsold product details
        $unsoldProductDetails = ProductDetail::where('product_id', $product->id)
            ->where('is_sold', 0) // 0 means in stock
            ->take($qty)
            ->get();

        // Create order items
        foreach ($unsoldProductDetails as $productDetail) {
            $item = new OrderItem();
            $item->order_id = $order->id;
            $item->product_id = $product->id;
            $item->product_detail_id = $productDetail->id;
            $item->price = $product->price;
            $item->name = $product->name;
            $item->save();

            // Mark the product detail as sold
            $productDetail->is_sold = 1; // 1 means sold
            $productDetail->save();
        }

        // Handle referral rewards
        $this->handleReferralRewards($user, $product, $amount);

        // Notify user and admin
        $this->notifyPurchase($qty, $product, $amount, $order, $request);

        $notify[] = ['success', 'Order placed successfully!'];
        return redirect('user/orders')->withNotify($notify);
    }

    // Handle referral rewards
    private function handleReferralRewards($user, $product, $amount)
    {
        $referrer = Referre::where('refrere', $user->username)->first();
        if ($referrer) {
            $reward = round(($product->price * 3) / 100, 2); // 3% referral reward
            $user = User::where('username', $referrer->referer)->first();
            
            if ($user) {
                $user->increment('ref_wallet', $reward);
            }
            
            $deposit = new Deposit();
            $deposit->order_id = 0;
            $deposit->user_id = $user->id;
            $deposit->amount = $reward;
            $deposit->method_code = 6000;
            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();
        }
    }

    // Notify user and admin about the purchase
    private function notifyPurchase($qty, $product, $amount, $order, $request)
    {
        // Log the purchase
        $br = new Bought();
        $br->user_name = Auth::user()->username;
        $br->qty = $qty;
        $br->item = $product->name;
        $br->amount = $amount;
        $br->save();

        // Send notification
        $message = "FADDED |" . Auth::user()->email . "| just bought | $qty | $order->id  | " . number_format($amount, 2) . "\n\n IP ====> " . $request->ip();
        send_notification2($message);
    }

    // Confirm deposit
    public function depositConfirm()
    {
        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }

        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);

        // Debug logging
        \Log::info('Payment Confirm Process', [
            'deposit_id' => $deposit->id,
            'gateway_alias' => $deposit->gateway->alias,
            'process_data' => $data
        ]);

        if (isset($data->error)) {
            \Log::error('Payment Process Error', [
                'deposit_id' => $deposit->id,
                'error_message' => $data->message
            ]);
            $notify[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        $pageTitle = 'Payment Confirm';
        return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
    }

    // Update user data after a successful deposit
    public static function userDataUpdate($deposit, $isManual = null)
    {
        if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {
            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $user = User::find($deposit->user_id);
            $email = User::where('id', $deposit->user_id)->first()->email;
            User::where('id', $deposit->user_id)->increment('balance', $deposit->amount);

            $message = "FADDED |" . $email . "|" . number_format($deposit->amount, 2) . "| has been manually funded by Admin";
            send_notification2($message);
            send_notification($message);

            // Send webhook if gateway is Xtrapay or PayVibe
            if (strtolower(optional($deposit->gateway)->alias) === 'xtrapay') {
                WebhookService::sendSuccessfulTransaction($deposit, $user);
            } elseif (strtolower(optional($deposit->gateway)->alias) === 'payvibe') {
                WebhookService::sendSuccessfulTransaction($deposit, $user);
            }

            if (!$isManual) {
                $adminNotification = new AdminNotification();
                $adminNotification->user_id = $user->id;
                $adminNotification->title = 'Payment successful via ' . $deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }

            notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                'method_name' => $deposit->gatewayCurrency()->name,
                'method_currency' => $deposit->method_currency,
                'method_amount' => showAmount($deposit->final_amo),
                'amount' => showAmount($deposit->amount),
                'charge' => showAmount($deposit->charge),
                'rate' => showAmount($deposit->rate),
                'trx' => $deposit->trx,
            ]);
        }
    }

    // Show manual deposit confirmation page
    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {
            $pageTitle = 'Payment Confirm';
            $method = $data->gatewayCurrency();
            $gateway = $method->method;
            return view($this->activeTemplate . 'user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
        }
        abort(404);
    }

    // Handle manual deposit update with receipt upload
    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }

        if ($request->receipt == null) {
            return back()->with('error', "Payment receipt is required");
        }

        $file = $request->file('receipt');
        $receipt_fileName = date("ymis") . $file->getClientOriginalName();
        $directory = date("Y") . "/" . date("m") . "/" . date("d");
        $path = getFilePath('verify') . '/' . $directory;
        $request->receipt->move($path, $receipt_fileName);
        $url = url('') . "/" . $path . "/" . $receipt_fileName;

        Deposit::where('trx', $track)->update([
            'status' => Status::PAYMENT_PENDING,
            'url' => $url,
        ]);

        $email = User::where('id', $data->user->id)->first()->email;
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $data->user->id;
        $adminNotification->title = 'Payment request from ' . $data->user->username;
        $adminNotification->click_url = $url;
        $adminNotification->save();

        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name' => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount' => showAmount($data->final_amo),
            'amount' => showAmount($data->amount),
            'charge' => showAmount($data->charge),
            'rate' => showAmount($data->rate),
            'trx' => $data->trx
        ]);

        $notify = "You have payment request is successful, you will be credited soon";
        return redirect('/user/deposit/new')->with('message', $notify);
    }
}