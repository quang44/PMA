<?php

namespace App\Http\Controllers;

use App\Models\FirebaseNotification;
use App\Models\Notification as NotificationCustomer;
use App\Models\User;
use App\Utility\CustomerBillUtility;
use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
    public function index() {
        $notifications = FirebaseNotification::query()
            ->orderBy('id', 'DESC')
            ->where('item_type','maintain')
            ->orWhere('item_type','event')
            ->paginate(15);

        auth()->user()->unreadNotifications->markAsRead();

        if(Auth::user()->user_type == 'admin') {
            return view('backend.notification.index', compact('notifications'));
        }

        if(Auth::user()->user_type == 'seller') {
            return view('seller.notification.index', compact('notifications'));
        }

        if(Auth::user()->user_type == 'customer') {
            return view('frontend.user.customer.notification.index', compact('notifications'));
        }

    }


    function create()
    {


        return view('backend.notification.create');
    }

    function store(Request $request)
    {
        $req = new \stdClass();

        $req->title = $request->title;
        $req->text = $request->text;
        $req->type = $request->type;
        $req->token = $request->_token;


        if ($request->type == 'maintain') {
            $type = CustomerBillUtility::TYPE_NOTIFICATION_MAINTAIN;
        } else {
            $type = CustomerBillUtility::TYPE_NOTIFICATION_EVENT;
        }


        $users = User::query()->whereNotNull('device_token');
        $group=(int)$request->group;
//        dd($group);
            if ($group==1){
                $users = $users-> where('user_type','customer');
            }else if ($group==2){
                $users = $users-> where('user_type','employee')->where('belong','>',0);
            }else if($group==3){
                $users = $users-> where('user_type','employee')->where('belong',0);
            }
          $users = $users->get();
        foreach ($users  as $user) {
            sendFireBase($user, $request->title, $request->text, $request->type);
        }

        $firebase_notification = new FirebaseNotification;
        $firebase_notification->title = $request->title;
        $firebase_notification->text = $request->text;
        $firebase_notification->item_type = $request->type;
        $firebase_notification->item_type_id=$request->group;
        $firebase_notification->save();

        $Notification = new NotificationCustomer;
        $Notification->create([
            'type' => $type,
            'data' => $request->text,
            'send_group'=>$request->group,
            'notifiable_id'=>$firebase_notification->id,
            'notifiable_type' => CustomerBillUtility::TYPE_NOTIFICATION_USER,
        ]);
        flash('Thêm thông báo thành công');
        return redirect()->route('admin.all-notification');
    }


    function edit($id)
    {
        $notification = FirebaseNotification::findOrFail(decrypt($id));

        return view('backend.notification.edit', compact('notification'));
    }


    function update(Request $request, $id)
    {
        $req = new \stdClass();

        $req->title = $request->title;
        $req->text = $request->text;
        $req->type = $request->type;
        $req->token = $request->_token;


        if ($request->type == 'maintain') {
            $type = CustomerBillUtility::TYPE_NOTIFICATION_MAINTAIN;
        } else {
            $type = CustomerBillUtility::TYPE_NOTIFICATION_EVENT;
        }

        $firebase_notification = FirebaseNotification::query()->findOrFail($id);
        $firebase_notification->title = $request->title;
        $firebase_notification->text = $request->text;
        $firebase_notification->item_type = $request->type;
        $firebase_notification->item_type_id=$request->group;
        $firebase_notification->save();

        $users = User::query()->whereNotNull('device_token');
        $group=(int)$request->group;
        if ($group==1){
            $users = $users-> where('user_type','customer');
        }else if ($group==2){
            $users = $users-> where('user_type','employee')->where('belong','>',0);
        }else if($group==3){
            $users = $users-> where('user_type','employee')->where('belong',0);
        }

        $users = $users->get();
        foreach ($users as $user) {
            sendFireBase($user, $request->title, $request->text, $request->type);
        }

        $Notification =NotificationCustomer::query()->where('notifiable_id',$firebase_notification->id)->first();
        if($Notification){
            $Notification->update([
                'type' => $type,
                'data' => $request->text,
                'send_group'=>$request->group,
                'notifiable_id'=>$firebase_notification->id,
                'notifiable_type' => CustomerBillUtility::TYPE_NOTIFICATION_USER,
            ]);
        }
        flash('Sửa thông báo thành công')->success();
        return redirect()->route('admin.all-notification');
    }

    function destroy($id)
    {
        $notification = FirebaseNotification::query()->findOrFail(decrypt($id));
        NotificationCustomer::where('notifiable_id',$notification->id)->delete();
        $notification->delete();
        flash('xóa thông báo thành công')->success();
        return redirect()->route('admin.all-notification');
    }


    }
