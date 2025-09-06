<x-filament-widgets::widget class="fi-fo">
    <x-filament::section>
        <!-- Header -->
        <h1 class="text-lg font-semibold text-gray-900 mb-6">Daily Shift History</h1>

        @forelse ($timeEntries as $time_entry)
            <div class="bg-gray-50 rounded-lg p-4 mt-4 relative border border-gray-100">
                <div class="absolute top-4 right-4">
                    <span
                        class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                        {{ $time_entry->status->value }}
                    </span>
                </div>
                <div class="flex items-center mb-2">
                    <div class="w-5 h-5 bg-gray-400 rounded-full mr-3 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span
                        class="text-gray-900 font-medium">{{ "Worked from " . $time_entry->clock_in_time->format('H:i') . " to " . $time_entry->clock_out_time->format('H:i') }}</span>
                </div>
                <p class="text-sm text-gray-600 ml-8"><span
                        class="font-medium">Total time:</span> {{ ($time_entry->formatted_hours_worked ?? "—") }}</p>
                <p class="text-sm text-gray-600 ml-8"><span
                        class="font-medium">Total break time:</span> {{ ($time_entry->formatted_break_hours ?? "—") }}
                </p>

                @if($time_entry->hours_worked_past_scheduled_shift)
                    <hr class="my-2 ml-8 border-gray-200">
                    <p class="text-sm text-gray-600 ml-8"><span
                            class="font-medium">Extra hours worked:</span> {{ $time_entry->hours_worked_past_scheduled_shift }}
                        minutes
                    </p>
                @endif

                @if($time_entry->over_time_work)
                    <div class="flex items-center ml-8 mt-2">
                        <x-icons.pencil-square class="w-4 h-4 fill-emerald-600 mr-2"/>
                        <p class="text-sm text-gray-600"><span
                                class="font-medium">Work:</span> {{ $time_entry->over_time_work }}</p>
                    </div>
                @endif

            </div>
        @empty
            <div class="bg-gray-50 rounded-lg p-4 mt-4 relative border border-gray-100">
                <span class="text-gray-500">No entries for today.</span>
            </div>
        @endforelse
    </x-filament::section>
</x-filament-widgets::widget>

