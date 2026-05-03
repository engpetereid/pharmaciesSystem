<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockNotification;

class NotificationsController extends Controller
{

    public function index()
    {
        $notifications = StockNotification::with(['pharmacy', 'drug'])
            ->latest()
            ->paginate(50);

        return view('admin.notifications.index', compact('notifications'));
    }
}
