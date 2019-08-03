<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
class UsersController extends Controller
{
        public function allUsers()
        {
            $people = User::where('id','<>',Auth::id())->get();

            return response()->json(['people'=>$people],200);
        }
}
