<?php

namespace Xtend\Extensions\Lunar\Admin\Livewire\Components\Settings\Staff;

use Illuminate\Support\Facades\Hash;
use Lunar\Hub\Http\Livewire\Components\Settings\Staff\StaffCreate as LunarStaffCreate;

class StaffCreate extends LunarStaffCreate
{
    /**
     * Create the staff member.
     *
     * @return void
     */
    public function create()
    {
        $this->validate();

        $this->staff->password = Hash::make($this->password);
        $this->staff->admin = (bool) $this->staff->admin;
        $this->staff->language_id = 1;

        $this->staff->save();

        $this->syncPermissions();

        $this->notify('Staff member added.', 'hub.staff.index');
    }
}
