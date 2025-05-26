<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'group',
        'name',
        'description',
        'value',
        'type',
        'validation_rules',
        'options',
        'default_value',
        'input_type',
        'category',
        'sort_order',
        'is_public',
        'requires_restart',
        'is_sensitive',
        'allowed_roles',
        'updated_by_admin_id',
        'last_modified_at',
        'change_reason',
        'environment',
        'is_active'
    ];

    protected $casts = [
        'validation_rules' => 'array',
        'options' => 'array',
        'allowed_roles' => 'array',
        'is_public' => 'boolean',
        'requires_restart' => 'boolean',
        'is_sensitive' => 'boolean',
        'is_active' => 'boolean',
        'last_modified_at' => 'datetime',
    ];

    /**
     * Get the admin who last updated this setting
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_admin_id');
    }

    /**
     * Get typed value
     */
    public function getTypedValueAttribute()
    {
        switch ($this->type) {
            case 'boolean':
                return (bool) $this->value;
            case 'integer':
                return (int) $this->value;
            case 'decimal':
                return (float) $this->value;
            case 'json':
            case 'array':
                return json_decode($this->value, true);
            default:
                return $this->value;
        }
    }

    /**
     * Set typed value
     */
    public function setTypedValue($value)
    {
        switch ($this->type) {
            case 'json':
            case 'array':
                $this->value = json_encode($value);
                break;
            case 'boolean':
                $this->value = $value ? '1' : '0';
                break;
            default:
                $this->value = (string) $value;
        }
    }

    /**
     * Get setting by key
     */
    public static function getSetting($key, $default = null)
    {
        $setting = self::where('key', $key)->where('is_active', true)->first();
        
        return $setting ? $setting->typed_value : $default;
    }

    /**
     * Set setting value
     */
    public static function setSetting($key, $value, User $admin = null, $reason = null)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->setTypedValue($value);
            $setting->updated_by_admin_id = $admin ? $admin->id : null;
            $setting->last_modified_at = now();
            $setting->change_reason = $reason;
            $setting->save();

            // Log admin activity
            if ($admin) {
                AdminActivityLog::logAction(
                    $admin,
                    'update_setting',
                    'SystemSetting',
                    $setting->id,
                    "Updated system setting: {$setting->name}",
                    ['value' => $setting->getOriginal('value')],
                    ['value' => $setting->value]
                );
            }
        }
        
        return $setting;
    }

    /**
     * Scope for public settings
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for settings by group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}