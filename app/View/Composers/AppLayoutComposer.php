<?php

namespace App\View\Composers;

use App\Models\Notification;
use Illuminate\View\View;

class AppLayoutComposer
{
    public function compose(View $view): void
    {
        if (auth()->check() && auth()->user()->isOrangTua()) {
            $view->with('unreadCount', Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count());
        }
    }
}
