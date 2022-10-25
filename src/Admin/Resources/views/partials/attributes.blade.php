<div class="space-y-4">
    @foreach ($attributeGroups ?? $this->attributeGroups as $groupIndex => $group)
        <div class="@if (!($inline ?? false)) shadow sm:rounded-md @endif"
            wire:key="attribute-group-{{ $groupIndex }}">
            <div class="@if (!($inline ?? false))  @endif flex-col space-y-4 rounded-t-xl bg-white">
                <header class="flex items-center gap-1 rounded-t-xl bg-[#353F4F] p-3">
                    <x-bx-detail class="h-6 w-6 text-[#CFA55B]" />
                    <span class="ml-2 text-sm font-semibold text-white">
                        {{ $group['model']->translate('name') }}
                    </span>
                </header>

                <div class="space-y-4 p-6">
                    @foreach ($group['fields'] as $attIndex => $field)
                        <div wire:key="attributes_{{ $field['handle'] }}">
                            <x-hub::input.group :label="$field['name']" :for="$field['handle']" :required="$field['required']" :error="$errors->first(($mapping ?? 'attributeMapping') . '.' . $attIndex . '.data') ?:
                                $errors->first(
                                    ($mapping ?? 'attributeMapping') .
                                        '.' .
                                        $attIndex .
                                        '.data.' .
                                        $this->defaultLanguage->code,
                                )">
                                @include($field['view'], [
                                    'field' => $field,
                                ])
                            </x-hub::input.group>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
