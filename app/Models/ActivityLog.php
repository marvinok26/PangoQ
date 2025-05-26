<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Prunable;

class ActivityLog extends Model
{
    use HasFactory, Prunable;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'changes',
        'original_data',
        'ip_address',
        'user_agent',
        'url',
        'method'
    ];

    protected $casts = [
        'changes' => 'array',
        'original_data' => 'array',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject model
     */
    public function subject()
    {
        return $this->morphTo('model');
    }

    /**
     * Log an activity
     */
    public static function log($action, $model = null, $changes = null, $originalData = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'changes' => $changes,
            'original_data' => $originalData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method()
        ]);
    }

    /**
     * Scope for admin actions
     */
    public function scopeAdminActions($query)
    {
        return $query->whereHas('user', function($q) {
            $q->where('is_admin', true);
        });
    }

    /**
     * Scope for specific model
     */
    public function scopeForModel($query, $modelType, $modelId = null)
    {
        $query = $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query;
    }

    /**
     * Get the prunable model query.
     * Keeps logs for 90 days
     */
    public function prunable()
    {
        return static::where('created_at', '<=', now()->subDays(90));
    }
}