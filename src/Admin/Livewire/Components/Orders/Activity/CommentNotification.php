<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Orders\Activity;

use Filament\Notifications\DatabaseNotification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Spatie\Activitylog\Contracts\Activity;
use Xtend\Extensions\Filament\Notifications\Notification;

class CommentNotification extends Component
{
    use Notifies;

    public Activity $log;

    public bool $showConfirmationModal = false;

    protected $listeners = ['refresh' => '$refresh'];

    public function getIsOwnLogProperty(): bool
    {
        return $this->log->causer->is(auth()->user());
    }

    public function removeComment(): void
    {
        $subject = $this->log->subject;

        $subject->notify($this->makeNotification());

        $this->notify(
            __('adminhub::notifications.order.comment_deleted')
        );

        $this->log->delete();

        $this->emit('refreshTimelineComponent');
    }

    protected function makeNotification(): DatabaseNotification
    {
        $notification = Notification::make()
                ->comment()
                ->warning()
                ->title('Comment deleted by '.auth()->user()->fullName)
                ->body($this->log->properties['content'])
                ->toDatabase();

        $notification->data = array_merge($notification->data, [
            'is_comment' => true,
        ]);

        return $notification;
    }

    public function render(): View
    {
        return view('adminhub::livewire.components.orders.activity.comment-notification-log');
    }
}
