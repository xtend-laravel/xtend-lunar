<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Redirector;
use Lunar\Models\Language;

class SwitchLanguage extends Component
{
    public string $locale;

    public function mount()
    {
        $this->locale = app()->currentLocale();
    }

    public function setLocale(string $locale): Redirector
    {
        $this->locale = $locale;

        $lang = Language::where('code', $locale)->first();
        tap(Auth::guard('staff')->user())->update([
            'language_id' => $lang->id,
        ]);

        return redirect()->to(route('hub.index'));
    }

    public function getLanguagesProperty(): Collection
    {
        return Language::all();
    }

    public function render(): View
    {
        return view('adminhub::livewire.components.switch-language');
    }
}
