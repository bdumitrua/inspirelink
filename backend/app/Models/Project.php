<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Elasticsearchable;
use App\Models\ProjectMember;
use App\Models\ProjectLink;
use App\Models\Interfaces\Likeable;

class Project extends Model implements Likeable
{
    use HasFactory, Elasticsearchable;

    protected $fillable = [
        'author_type',
        'author_id',
        'name',
        'description',
        'cover_url',
        'likes_count'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($project) {
            $project->likes()->delete();
        });
    }

    public static function getESIndex(): string
    {
        return 'projects';
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
            'name' => [
                'type' => 'text',
            ],
            'description' => [
                'type' => 'text',
            ],
            'author_type' => [
                'type' => 'text',
                'index' => false
            ],
            'author_id' => [
                'type' => 'text',
                'index' => false
            ],
            'cover_url' => [
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
                'fields' => ['name', 'description'],  // Поиск только по индексируемым полям
                "fuzziness" => 2
            ]
        ];
    }

    public function scopeFromUser(Builder $query, int $userId): void
    {
        $query->where('author_type', '=', config('entities.user'))
            ->where('author_id', '=', $userId);
    }

    public function scopeFromTeam(Builder $query, int $teamId): void
    {
        $query->where('author_type', '=', config('entities.team'))
            ->where('author_id', '=', $teamId);
    }

    /**
     * @return bool
     */
    public function createdByUser(int $userId = 0): bool
    {
        return empty($userId)
            ? $this->author_type === config('entities.user')
            : $this->author_type === config('entities.user') && $this->author_id === $userId;
    }

    /**
     * @return bool
     */
    public function createdByTeam(int $teamId = 0): bool
    {
        return empty($teamId)
            ? $this->author_type === config('entities.team')
            : $this->author_type === config('entities.team') && $this->author_id === $teamId;
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
     * @return HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(ProjectLink::class);
    }

    /**
     * @return HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(ProjectMember::class);
    }

    /**
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
