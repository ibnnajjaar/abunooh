<x-filament-widgets::widget class="fi-fo">
    <x-filament::section>
        <!-- Header -->
        <h1 class="text-lg font-semibold text-gray-900 mb-6">Time Tracking</h1>


        <!-- Clock In Button -->

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-hold-button :action="'clockIn'" :visible="!$isClockedIn">
                    <x-clock-button color="emerald">
                        <x-icons.icon-progress color="yellow">
                            <x-icons.clock class="w-5 h-5 fill-gray-50" />
                        </x-icons.icon-progress>
                        Clock In
                    </x-clock-button>
                </x-hold-button>

                <x-hold-button :action="'confirmClockOut'" :visible="$isClockedIn">
                    <x-clock-button color="emerald">
                        <x-icons.icon-progress color="yellow">
                            <x-icons.clock class="w-5 h-5 fill-gray-50" />
                        </x-icons.icon-progress>
                        Clock Out
                    </x-clock-button>
                </x-hold-button>
            </div>
            <div>
                <x-hold-button :action="'breakOut'" :visible="$isClockedIn && !$currentBreak">
                    <x-clock-button color="teal">
                        <x-icons.icon-progress color="yellow">
                            <x-icons.clock class="w-5 h-5 fill-gray-50" />
                        </x-icons.icon-progress>
                        Break Out
                    </x-clock-button>
                </x-hold-button>
                <x-hold-button :action="'breakIn'" :visible="$isClockedIn && $currentBreak">
                    <x-clock-button color="cyan">
                        <x-icons.icon-progress color="yellow">
                            <x-icons.clock class="w-5 h-5 fill-gray-50" />
                        </x-icons.icon-progress>
                        Break In
                    </x-clock-button>
                </x-hold-button>
            </div>
        </div>

        <!-- Status Section -->
        @if ($isClockedIn)

            <div class="bg-emerald-50/50 border border-emerald-100 rounded-lg p-4 mt-4"
                 x-data="{
                    clockInTime: {{ $timeEntry->clock_in_time->timestamp }},
                    elapsedTime: '00:00:00',
                    updateElapsedTime() {
                        const now = Math.floor(Date.now() / 1000);
                        const diff = now - this.clockInTime;
                        const hours = Math.floor(diff / 3600).toString().padStart(2, '0');
                        const minutes = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
                        const seconds = Math.floor(diff % 60).toString().padStart(2, '0');
                        this.elapsedTime = `${hours}:${minutes}:${seconds}`;
                    }
                }"
                 x-init="setInterval(() => updateElapsedTime(), 1000); updateElapsedTime()">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-emerald-500 rounded-full mr-3"></div>
                        <span
                            class="text-emerald-900 font-medium">{{ "Clocked in at " . $timeEntry->clock_in_time->format('H:i') }}</span>
                    </div>
                    <span class="text-emerald-900 font-medium" x-text="elapsedTime"></span>
                </div>
            </div>
        @endif

        @if ($currentBreak)

            <h2 class="text-sm font-semibold text-gray-900 mt-6 mb-4">Ongoing Break</h2>
            <div class="bg-yellow-50/50 border border-yellow-100 rounded-lg p-4"
                 x-data="{
            breakStartTime: {{ $currentBreak->break_start_at->timestamp }},
            elapsedBreakTime: '00:00:00',
            updateBreakTime() {
                const now = Math.floor(Date.now() / 1000);
                const diff = now - this.breakStartTime;
                const hours = Math.floor(diff / 3600).toString().padStart(2, '0');
                const minutes = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
                const seconds = Math.floor(diff % 60).toString().padStart(2, '0');
                this.elapsedBreakTime = `${hours}:${minutes}:${seconds}`;
            }
        }"
                 x-init="setInterval(() => updateBreakTime(), 1000); updateBreakTime()">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span
                            class="text-yellow-900 font-medium">{{ "Break started at " . $currentBreak->break_start_at->format('H:i') }}</span>
                    </div>
                    <span class="text-yellow-900 font-medium" x-text="elapsedBreakTime"></span>
                </div>
            </div>
        @endif

        @if ($breaks && $breaks?->isNotEmpty())

            <h2 class="text-sm font-semibold text-sky-900 mt-6 mb-4">Previous Breaks</h2>
            <div class="space-y-3">
                @foreach ($breaks as $break)
                    <div class="bg-sky-50/50 border border-sky-100 rounded-lg p-4">
                        <div class="grid grid-cols-3 gap-4 {{ $break->note ? 'mb-2' : '' }}">
                            <div class="flex items-center">
                                <x-icons.clock class="w-4 h-4 mr-2 fill-sky-600"/>
                                <span
                                    class="text-sm text-sky-600"><span class="font-medium">Start:</span> {{ $break->break_start_at->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center">
                                <x-icons.clock class="w-4 h-4 mr-2 fill-sky-600"/>
                                <span
                                    class="text-sm text-sky-600"><span class="font-medium">End:</span> {{ $break->break_end_at->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center">
                                <x-icons.clock class="w-4 h-4 mr-2 fill-sky-600"/>
                                <span
                                    class="text-sm text-sky-600"><span class="font-medium">Duration:</span> {{ gmdate('H:i', $break->total_break_time * 60) }}</span>
                            </div>
                        </div>
                        @if($break->note)
                            <div class="mt-2 pt-2 border-t border-sky-100">
                                <div class="flex items-start">
                                    <x-icons.pencil-square class="w-4 h-4 mr-2 mt-0.5 fill-sky-600"/>
                                    <p class="text-sm text-sky-700">{{ $break->note }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif


        <!-- Overtime Modal -->

        <div
            x-data="{ open: @entangle('showOvertimeModal') }"
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center"
            style="display: none;"
        >
            <div class="fixed inset-0 bg-black/40"></div>

            <div class="mx-4 sm:mx-auto relative bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-lg p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Overtime details</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    You have worked past the expected hours. Optionally describe what you worked on during overtime.
                </p>

                <div class="mt-4">
                    <textarea
                        wire:model.live="overtimeNotes"
                        rows="5"
                        placeholder="What did you work on during overtime?"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 p-3 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500"
                    ></textarea>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button"
                            class="px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 hover:bg-gray-200"
                            wire:click="skipOvertime"
                            @click="open = false"
                    >Skip</button>

                    <button type="button"
                            class="px-4 py-2 rounded-md bg-sky-600 text-white hover:bg-sky-700"
                            wire:click="saveOvertimeAndClockOut"
                            @click="open = false"
                    >Save &amp; Clock Out</button>
                </div>
            </div>
        </div>

        <!-- Break In Modal -->
        <div
            x-data="{ open: @entangle('showBreakInModal') }"
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center"
            style="display: none;"
        >
            <div class="fixed inset-0 bg-black/40"></div>

            <div class="mx-4 sm:mx-auto relative bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-lg p-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Break note (optional)</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    You are breaking in. Optionally describe what you did during the break. If you skip, you will still break in.
                </p>

                <div class="mt-4">
                    <textarea
                        wire:model.live="breakInNotes"
                        rows="5"
                        placeholder="What did you do during your break? (optional)"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 p-3 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500"
                    ></textarea>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button"
                            class="px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 hover:bg-gray-200"
                            wire:click="skipBreakIn"
                            @click="open = false"
                    >Skip</button>

                    <button type="button"
                            class="px-4 py-2 rounded-md bg-sky-600 text-white hover:bg-sky-700"
                            wire:click="saveBreakIn"
                            @click="open = false"
                    >Save &amp; Break In</button>
                </div>
            </div>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>

