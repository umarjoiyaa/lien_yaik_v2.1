<?php

namespace App\Http\Controllers;

use App\Mail\AcceptEmail;
use App\Mail\RejectEmail;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function index()
    {
        $purchases = PurchaseOrder::where('status', '=', 0)->get();

        $output = '';
        $length = 0;

        foreach ($purchases as $order) {
            $orderDetail = PurchaseOrderDetail::where('order_id', '=', $order->id)->whereNull('accept')->whereNull('reject')->get();
            foreach ($orderDetail as $detail) {
                if (Auth::user()->id == $detail->user && $detail->reject == null) {
                    $output .= '<div class="media media-sm p-4 mb-0">
                                    <div class="media-body">
                                        <a>
                                            <span class="title mb-0">Hey '.Auth::user()->name.'</span>
                                            <span class="discribe" style="width: auto !important;">'. $order->order_no .' Assign to you from '. $order->user->name .'</span>
                                            <div class="buttons">
                                                <a href="'. url('productions/purchase-order/review/'.$order->id) .'" class="btn btn-sm btn-primary text-white shadow-none review">Review</a>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <hr>';
                    $length++;
                }
            }
        }

        return response()->json(['output' => $output, 'length' => $length]);
    }

    public function review($id)
    {
        $review = PurchaseOrder::find($id);
        $users = User::all();
        return view('productions.purchase-order.review', compact('review', 'users'));
    }

    function accept(Request $request)
    {
        $order = PurchaseOrder::find($request->id);
        $order->status = "1";
        $order->save();

        $user = User::where('id', '=', $order->issued)->first();

        $accepted = PurchaseOrderDetail::where('order_id', '=', $request->id)->where('user', '=', Auth::user()->id)->first();
        $accepted->accept = "1";
        if($request->remarks != null){
            $accepted->remarks = $request->remarks;
        }
        $accepted->save();

        $accepts = PurchaseOrderDetail::where('order_id', '=', $request->id)->whereNull('accept')->get();
        foreach($accepts as $accept){
            $accept->accept = "0";
            $accept->save();
        }
 
        $email = new AcceptEmail($user, Auth::user()->name, $order);
        Mail::to($user->email)->send($email);

        return response()->json($request->id);
    }

    function reject(Request $request)
    {
        $order = PurchaseOrder::find($request->id);
        $order->status = "2";
        $order->save();

        $user = User::where('id', '=', $order->issued)->first();

        $rejected = PurchaseOrderDetail::where('order_id', '=', $request->id)->where('user', '=', Auth::user()->id)->first();
        $rejected->reject = "1";
        if($request->remarks != null){
            $rejected->remarks = $request->remarks;
        }
        $rejected->save();

        $rejects = PurchaseOrderDetail::where('order_id', '=', $request->id)->whereNull('reject')->get();
        foreach($rejects as $reject){
            $reject->reject = "0";
            $reject->save();
        }
 
        $email = new RejectEmail($user, Auth::user()->name, $order);
        Mail::to($user->email)->send($email);

        return response()->json($request->id);
    }
}
