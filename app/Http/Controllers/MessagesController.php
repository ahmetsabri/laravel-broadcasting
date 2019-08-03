<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Message;
use App\Events\SendMessage;
class MessagesController extends Controller
{
    public function sendMessages(Request $request)
    {
        $user = Auth::user();
        
        $to = $request->to;

        $message = $request->message;

        $sent_img = null;

        $image_new_name =  null;

        $full_image = null;
        if ($request->filled('img')) {
                $full_image = $request->full_image;
                $image = $request->img;
                $extension = $request->extension;
                $image_base64 = base64_decode($image);
               
                
                $image_new_name =  "chat/".uniqid().".$extension";
 
                $sent_img = file_put_contents($image_new_name, $image_base64);

        }

        $sent_message = $user->sent()->create([
            'to_id'=>$to,
            'message'=>$message,
            'img'=>$image_new_name,
        ]);

        event (new SendMessage(Auth::user()->name,$to,$sent_message,$full_image));

    }

    public function loadMessages(Request $request)
    {

        $currnet_user_id = Auth::id();
        $to_id = $request->to_id;

        $messages = Message::where([['user_id','=',$currnet_user_id],['to_id','=',$to_id]])->orWhere([['user_id','=',$to_id],['to_id','=',$currnet_user_id]])->get();

        return response()->json(['messages'=>$messages],201);
    }

}
