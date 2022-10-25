<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\DatabaseNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component;
use Lunar\Facades\ModelManifest;
use Lunar\Hub\Facades\ActivityLog;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Models\Customer;
use Lunar\Models\Order;
use Lunar\Models\Product;
use Spatie\Activitylog\Models\Activity;
use Xtend\Extensions\Filament\Notifications\Notification;

class Timeline extends Component
{
    use Notifies;

    public ?Model $subject;

    public ?string $comment = null;

    public bool $openModal = false;

    protected $listeners = ['refreshTimelineComponent' => '$refresh'];

    /**
     * @throws \Exception
     */
    public function mount()
    {
        $model = request()->route()->parameter(
            request()->route()->parameterNames()[0]
        );

        if (! $model instanceof Model) {
            throw new \Exception(__('Invalid model provided (:model).', ['model' => $model]));
        }

        $this->subject = $model;

        if (request()->has('timeline')) {
            $this->openModal = true;
        }
    }

    public function rules(): array
    {
        return [
            'comment' => 'string|required',
        ];
    }

    public function addComment(): void
    {
        activity()
            ->performedOn($this->subject)
            ->causedBy(
                auth()->user()
            )
            ->event('comment')
            ->withProperties(['content' => $this->comment])
            ->log('comment');

        $this->subject->notify(
            $this->makeNotification(),
        );

        $this->notify(
            __('adminhub::notifications.order.comment_added')
        );

        $this->comment = null;
        $this->emit('refreshTimelineComponent');
    }

    protected function makeNotification(): DatabaseNotification
    {
        $notification = Notification::make()
            ->comment()
            ->warning()
            ->title('Comment from '.auth()->user()->fullName)
            ->body($this->comment)
            ->actions([
                Action::make('view')
                    ->button()
                    ->url(route('hub.orders.show', ['order' => $this->subject]).'?timeline'),
            ])
            ->toDatabase();

        $notification->data = array_merge($notification->data, [
            'is_comment' => true,
        ]);

        return $notification;
    }

    public function getActivityLogProperty(): Collection
    {
        switch ($this->getRouteName()) {
            case 'hub.index':
                return $this->getActivityLogForClass([Order::class, Customer::class, Product::class]);
            case 'hub.components.timeline':
            case 'hub.customers.show':
            case 'hub.orders.show':
            case 'hub.products.show':
                return $this->getActivityLogForModel($this->subject);
            default:
                return collect();
        }
    }

    private function getActivityLogForClass(array $classes): Collection
    {
        $data = collect();

        foreach ($classes as $class) {
            $latestActivity = Activity::where('subject_type', $class)
                ->whereNotIn('event', ['updated'])
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestActivity) {
                $data->push($this->getActivityLogForModel($latestActivity->subject)->first());
            }
        }

        return $data;
    }

    private function getActivityLogForModel(Model $model): Collection
    {
        return $model->activities()
            ->whereNotIn('event', ['updated'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($log) {
                return $log->created_at->format('Y-m-d');
            })->map(function ($logs) {
                return [
                    'date' => $logs->first()->created_at->startOfDay(),
                    'items' => $logs->map(function (Activity $log) {
                        return [
                            'log' => $log,
                            'content' => $log->properties->get('content'),
                            'renderers' => $this->renderers->filter(function ($render) use ($log) {
                                return $render['event'] == $log->event;
                            })->pluck('class')->map(function ($class) use ($log) {
                                $renderer = new $class();

                                return $renderer->render($log);
                            }),
                        ];
                    }),
                ];
            });
    }

    public function getRenderersProperty(): Collection
    {
        $subjectClass = ModelManifest::getMorphClassBaseModel(get_class($this->subject)) ?? get_class($this->subject);

        return ActivityLog::getItems($subjectClass);
    }

    public function getTotalActivityCountProperty(): int
    {
        return $this->activityLog->sum(function ($item) {
            return $item['items']->count();
        });
    }

    public function render(): View
    {
        return view('adminhub::livewire.components.timeline');
    }

    protected function getRouteName(): string
    {
        return request()->route()->name ?? request()->route()->getName();
    }
}
