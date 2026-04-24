<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class NotificationSettingController extends Controller
{
    public function index()
    {
        $saleNotificationEnabled = Setting::getSetting('sale_notification_enabled', 1);
        $userRegisteredNotificationEnabled = Setting::getSetting('user_registered_notification_enabled', 1);

        return view('admin.notification-settings.index', compact('saleNotificationEnabled', 'userRegisteredNotificationEnabled'));
    }

    public function update(Request $request)
    {
        Setting::setSetting('sale_notification_enabled', $request->has('sale_notification_enabled') ? 1 : 0);
        Setting::setSetting('user_registered_notification_enabled', $request->has('user_registered_notification_enabled') ? 1 : 0);

        return redirect()->route('admin.notification-management.index')->with('success', 'Notification settings updated successfully.');
    }

    public function markAsRead()
    {
        \App\Models\AdminNotification::whereNull('read_at')->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
}
