<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'changes',
        'original_data', // Changed from 'old_values' to match your table
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    protected $casts = [
        'changes' => 'array',
        'original_data' => 'array', // Changed from 'old_values'
    ];

    /**
     * Get the user that performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for admin actions only
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
        $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query;
    }

    /**
     * Log an activity
     */
    public static function log(string $action, $model = null, array $changes = [], array $oldValues = [])
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'changes' => $changes,
            'original_data' => $oldValues, // Changed from 'old_values' to 'original_data'
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
            'method' => request()->method(),
        ]);
    }
}