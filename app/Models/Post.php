<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Support\Enums\PostTypes;
use App\Support\Enums\PublishStatuses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'published_at',
        'status',
        'post_type',
        'is_menu_item',
        'og_image_url',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_menu_item' => 'boolean',
        'status'       => PublishStatuses::class,
        'post_type'    => PostTypes::class,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopeSearch($query, $search)
    {
        $query->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('excerpt', 'like', '%' . $search . '%')
                ->orWhere('content', 'like', '%' . $search . '%');
        });
    }

    public function scopePublished($query)
    {
        $query->where('status', PublishStatuses::PUBLISHED->value)
            ->where('published_at', '<', Carbon::now());
    }

    public function scopePending($query)
    {
        $query->where('status', PublishStatuses::PENDING->value);
    }

    public function scopePastSchedule($query)
    {
        $query->where('published_at', '<', Carbon::now());
    }

    public function hasOgImage(): Attribute
    {
        return Attribute::make(
            get: fn () => ! empty($this->og_image_url)
        );
    }

    public function getFormattedPublishDateAttribute(): ?string
    {
        return $this->published_at?->format('M d');
    }

    public function getFormattedLongPublishDateAttribute(): ?string
    {
        return $this->published_at?->format('d M, Y');
    }

    public function getAuthorNameAttribute()
    {
        return $this->author?->name;
    }

    public function getUrlAttribute(): string
    {
        return route('posts.show', $this);
    }

    public function getFormattedContentAttribute(): string
    {
        return str($this->content)->markdown();
    }

    public function formattedStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->status?->value)->title()
        );
    }

    public function formattedPostType(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->post_type?->value)->title()
        );
    }


    public function getFormattedTitleAttribute(): string
    {
        return ucwords($this->title);
    }

    public function registerMediaCollections(): void
    {
        $this->registerGraphifyMediaCollection();
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status == PublishStatuses::PUBLISHED
            && $this->published_at?->isNowOrPast();
    }
}
