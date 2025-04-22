<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;
    
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function index(): View
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('livewire.pages.notifications.index', compact('notifications'));
    }
    
    public function markAsRead(Notification $notification)
    {
        // Ensure the notification belongs to the user
        if ($notification->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        $this->notificationService->markAsRead($notification);
        
        return back()->with('success', 'Notification marked as read.');
    }
    
    public function markAllAsRead()
    {
        $user = auth()->user();
        
        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return back()->with('success', 'All notifications marked as read.');
    }
}