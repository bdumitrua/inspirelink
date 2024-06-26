<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Elasticsearchable;
use App\Models\Interfaces\Likeable;

class Post extends Model implements Likeable
{
    use HasFactory, Elasticsearchable;

    protected $fillable = [
        'text',
        'media_url',
        'author_type',
        'author_id',
        'likes_count'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            $post->likes()->delete();
            $post->tags()->delete();
        });
    }

    public static function getESIndex(): string
    {
        return 'posts';
    }

    protected static function getESRefreshInterval(): string
    {
        return '5s';
    }

    public function toSearchableArray(): array
    {
        return $this->attributesToArray();
    }

    /**
     * Define the properties for the Elasticsearch index mappings.
     *
     * @return array
     */
    protected static function getSearchProperties(): array
    {
        return  [
            'id' => [
                'type' => 'keyword'
            ],
            'text' => [
                'type' => 'text'
            ],
            'media_url' => [
                'type' => 'text',
                'index' => false
            ],
            'author_type' => [
                'type' => 'text',
                'index' => false
            ],
            'author_id' => [
                'type' => 'text',
                'index' => false
            ],
            'likes_count' => [
                'type' => 'integer',
                'index' => false
            ],
            'created_at' => [
                'type' => 'date',
                'index' => false,
            ],
            'updated_at' => [
                'type' => 'date',
                'index' => false,
            ],
        ];
    }

    /**
     * Build the search query for Elasticsearch.
     *
     * @param string $searchString
     * @return array
     */
    protected static function getSearchQuery(string $searchString): array
    {
        return [
            'multi_match' => [
                'query' => $searchString,
                'fields' => ['text'],  // Поиск только по индексируемым полям
                "fuzziness" => 2
            ]
        ];
    }

    /**
     * @return bool
     */
    public function createdByUser(): bool
    {
        return $this->author_type === config('entities.user');
    }

    /**
     * @return bool
     */
    public function createdByTeam(): bool
    {
        return $this->author_type === config('entities.team');
    }

    /**
     * @return void
     */
    public function incrementLikesCount(): void
    {
        $this->timestamps = false; // To prevent updated_at change
        $this->increment('likes_count');
        $this->timestamps = true;
    }

    /**
     * @return void
     */
    public function decrementLikesCount(): void
    {
        $this->timestamps = false; // To prevent updated_at change
        $this->decrement('likes_count');
        $this->timestamps = true;
    }

    /**
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    /**
     * @return MorphTo
     */
    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function getAuthorType(): string
    {
        return $this->author_type;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    /**
     * @return MorphMany
     */
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'entity');
    }
}
