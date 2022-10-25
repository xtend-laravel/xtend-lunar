<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components;

use Illuminate\Support\Facades\Hash;
use Lunar\Models\Language;
use Xtend\Extensions\Lunar\Admin\Forms;
use Xtend\Extensions\Lunar\Admin\Forms\LunarForm;

class ProfileForm extends LunarForm
{
    public ?string $password = null;

    public ?string $currentPassword = null;

    protected function rules(): array
    {
        return [
            'model.firstname' => 'required',
            'model.lastname' => 'required',
            'model.email' => 'email|required|unique:'.get_class($this->model).',email,'.$this->model->id,
            'model.language_id' => 'required',
            'password' => 'nullable|min:8',
            'currentPassword' => 'nullable|current_password:staff',
        ];
    }

    protected function schema(): array
    {
        return [
            Forms\Fields\Input\Text::make('firstname')->required(),
            Forms\Fields\Input\Text::make('lastname')->required(),
            Forms\Fields\Input\Text::make('email')->required(),
            Forms\Fields\Input\Select::make('language_id')->options(
                options: Language::all()->map(fn ($language) => ['label' => $language->name, 'value' => $language->id])->toArray(),
                relationship: true,
            ),
            Forms\Fields\Input\Password::make('password'),
            Forms\Fields\Input\Password::make('currentPassword'),
        ];
    }

    public function update()
    {
        $this->validate();

        if ($this->model->isDirty(['email'])) {
            $this->emit('hub.staff.avatar.updated', $this->model->gravatar);
        }

        if ($this->model->isDirty(['firstname', 'lastname'])) {
            $this->emit('hub.staff.name.updated', $this->model->fullName);
        }

        $isLangChanged = $this->model->isDirty(['language_id']);

        if ($this->password) {
            $this->model->password = Hash::make($this->password);

            $this->password = null;
            $this->currentPassword = null;
        }

        $this->model->save();

        $this->notify(
            __('adminhub::notifications.account.updated')
        );

        if ($isLangChanged) {
            return redirect()->to(route('hub.account'));
        }
    }
}
