<div x-data="{
    open: false,
    toggle() {
        if (this.open) {
            return this.close()
        }
        this.open = true
    },
    close(focusAfter) {
        this.open = false
        focusAfter && focusAfter.focus()
    },
}" x-cloak x-on:keydown.escape.prevent.stop="close($refs.button)"
    x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['dropdown-button']"
    class="relative mt-1">
    <!-- Button -->
    <button x-ref="button" x-on:click="toggle()" :aria-expanded="open" :aria-controls="$id('dropdown-button')"
        type="button" class="flex flex-col items-center rounded-md focus:outline-none">
        @if ($locale)
            <div class="flex items-center space-x-1">
                <span>
                    <img class="h-3 w-5" src="{{ Vite::asset('resources/images/flags/' . $locale . '.svg') }}" alt="" />
                </span>
                <span class="text-xs font-bold">
                    {{ strtoupper($locale) }}
                </span>
            </div>
        @endif
        <span aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="portrait-md:w-6 portrait-md:h-6 h-3 w-3 text-white"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </span>
    </button>

    <!-- Panel -->
    <div x-ref="panel" x-show="open" x-transition.origin.top.left x-on:click.outside="close($refs.button)"
        :id="$id('dropdown-button')" style="display: none;"
        class="absolute z-10 -ml-4 -mt-2 max-w-md transform rounded-md bg-white px-2 shadow-inner sm:px-0 lg:left-1/2 lg:ml-0 lg:-translate-x-1/2">
        <div class="flex w-16 flex-col items-center space-y-2 p-2">
            @foreach ($this->languages as $language)
                @if ($locale != $language->code)
                    <button wire:click.prevent="setLocale('{{ $language->code }}')"
                        class="portrait-md:text-2xl flex justify-end">
                        <div class="flex items-center space-x-1">
                            <span>
                                <img class="h-3 w-5 shrink-0"
                                    src="{{ Vite::asset('resources/images/flags/' . $language->code . '.svg') }}"
                                    alt="{{ $language->name }}" />
                            </span>
                            <span class="text-xs font-bold">
                                {{ strtoupper($language->code) }}
                            </span>
                        </div>
                    </button>
                @endif
            @endforeach
        </div>
    </div>
</div>
