<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentView extends Model
{
    protected $fillable = ['viewable_type', 'viewable_id', 'user_id', 'action'];

    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(Model $viewable, string $action = 'view'): void
    {
        static::create([
            'viewable_type' => get_class($viewable),
            'viewable_id' => $viewable->getKey(),
            'user_id' => auth()->id(),
            'action' => $action,
        ]);
    }
}
