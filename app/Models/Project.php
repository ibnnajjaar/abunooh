<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use App\Support\Enums\PublishStatuses;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = [];

    protected $attributes = [
        'order_column' => 1,
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query): void
    {
        $query->where('publish_status', PublishStatuses::PUBLISHED->value);
    }

    public function scopePending($query): void
    {
        $query->where('publish_status', PublishStatuses::PENDING->value);
    }

    public function featuredImage(): Attribute
    {
        return Attribute::get(
            function () {
                return $this->getFirstMediaUrl('featured_image');
            }
        );
    }

    public function formattedDescription(): Attribute
    {
        return Attribute::get(
            function () {
                return str($this->description)->markdown();
            }
        );
    }
}
