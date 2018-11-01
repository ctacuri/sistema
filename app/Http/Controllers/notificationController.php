<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;

class notificationController extends Controller
{
    public function get(){
        return Notification::all();
    }
}
