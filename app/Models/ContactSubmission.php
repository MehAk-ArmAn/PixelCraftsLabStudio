<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_READ = 'read';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_REPLIED = 'replied';
    public const STATUS_ARCHIVED = 'archived';
    public const STATUS_SPAM = 'spam';

    public const STATUSES = [
        self::STATUS_NEW, self::STATUS_READ, self::STATUS_IN_PROGRESS,
        self::STATUS_REPLIED, self::STATUS_ARCHIVED, self::STATUS_SPAM,
    ];

    protected $fillable = [
        'name', 'email', 'build_type', 'scope', 'timeline', 'service', 'budget', 'message',
        'business_name', 'website_url', 'social_platforms', 'primary_goal', 'target_audience',
        'current_marketing', 'preferred_channels', 'is_marketing_enquiry',
        'status', 'admin_notes', 'ip_address', 'user_agent',
        'read_at', 'replied_at', 'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'is_marketing_enquiry' => 'boolean',
            'read_at' => 'datetime',
            'replied_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at')->where('status', '!=', self::STATUS_SPAM);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_ARCHIVED, self::STATUS_SPAM]);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_IN_PROGRESS => 'In progress',
            default => ucfirst($this->status),
        };
    }
}
