<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Support\Traits\HasActivityLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property int $id
 * @property string $name
 * @property Carbon $start_time
 * @property Carbon $end_time
 */
class Shift extends Model
{
    use HasActivityLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];

    /**
     * Cast start_time to a Carbon instance and store as H:i:s.
     */
    protected function startTime(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?Carbon => $value !== null ? Carbon::createFromFormat('H:i:s', $value) : null,
            set: function ($value): ?string {
                if (! $value instanceof Carbon) {
                    $value = Carbon::parse($value);
                }
                return $value->format('H:i:s');
            }
        );
    }

    /**
     * Cast end_time to a Carbon instance and store as H:i:s.
     */
    protected function endTime(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?Carbon => $value !== null ? Carbon::createFromFormat('H:i:s', $value) : null,
            set: function ($value): ?string {
                if (! $value instanceof Carbon) {
                    $value = Carbon::parse($value);
                }
                return $value->format('H:i:s');
            }
        );
    }
}
