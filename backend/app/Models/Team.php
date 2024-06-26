<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Elasticsearchable;

class Team extends Model
{
    use HasFactory, Elasticsearchable;

    protected $fillable = [
        'name',
        'avatar',
        'description',
        'email',
        'site',
        'chat_id',
        'admin_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($team) {
            $team->posts()->delete();
            $team->tags()->delete();
            $team->subscribers()->delete();



            // It's not logical to DELETE the whole chat, 
            // so we just change the admin of chat to team admin
            /** @var GroupChat|null */
            $teamChat = $team->chat()->first();
            if (!empty($teamChat)) {
                $teamChat->admin_type = config('entities.user');
                $teamChat->admin_id = $team->admin_id;
                $teamChat->save();
            }
        });
    }

    public static function getESIndex(): string
    {
        return 'teams';
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
                'type' => 'text'
            ],
            'avatar' => [
                'type' => 'text',
                'index' => false
            ],
            'description' => [
                'type' => 'text',
                'index' => false
            ],
            'email' => [
                'type' => 'text',
                'index' => false
            ],
            'site' => [
                'type' => 'text',
                'index' => false
            ],
            'chat_id' => [
                'type' => 'text',
                'index' => false
            ],
            'admin_id' => [
                'type' => 'text',
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
                'fields' => ['name'],  // Поиск только по индексируемым полям
                "fuzziness" => 2
            ]
        ];
    }

    /**
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * @return HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * @return HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(TeamLink::class);
    }

    /**
     * @return HasMany
     */
    public function vacancies(): HasMany
    {
        return $this->hasMany(TeamVacancy::class);
    }

    /**
     * @return MorphMany
     */
    public function posts(): MorphMany
    {
        return $this->morphMany(Post::class, 'entity');
    }

    /**
     * @return MorphMany
     */
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'entity');
    }

    /**
     * @return MorphMany
     */
    public function subscribers(): MorphMany
    {
        return $this->morphMany(Subscription::class, 'entity');
    }

    public function chat(): MorphOne
    {
        return $this->morphOne(GroupChat::class, 'admin');
    }
}
