<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications();
        Auth::user()->notification_count = 0;
        Auth::user()->save();

        return view('notifications.index', compact('notifications'));
    }

    public function count()
    {
        return Auth::user()->notification_count;
    }
}
