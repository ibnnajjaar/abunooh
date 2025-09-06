@props([
    'action',
    'visible' => true
])

@if ($visible)
<div
    x-data="{
        isPressing: false,
        progress: 0,
        holdMs: 2000,
        frameId: null,
        startTs: 0,
        startHold() {
            if (this.isPressing) return;
            this.isPressing = true;
            this.progress = 0;
            this.startTs = performance.now();
            const step = (ts) => {
                if (!this.isPressing) return;
                const elapsed = ts - this.startTs;
                this.progress = Math.min(1, elapsed / this.holdMs);
                if (this.progress >= 1) {
                    this.isPressing = false;
                    this.progress = 1;
                    $wire.{{ $action }}(); // Use the passed-in action
                    return;
                }
                this.frameId = requestAnimationFrame(step);
            };
            this.frameId = requestAnimationFrame(step);
        },
        cancelHold() {
            this.isPressing = false;
            this.progress = 0;
            if (this.frameId) cancelAnimationFrame(this.frameId);
            this.frameId = null;
        }
    }"
    @mousedown.prevent="startHold()"
    @mouseup="cancelHold()"
    @mouseleave="cancelHold()"
    @touchstart.prevent="startHold()"
    @touchend="cancelHold()"
    @touchcancel="cancelHold()"
    {{ $attributes }}
>
    {{ $slot }}
</div>
@endif
