<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'rules',
        'venue',
        'event_date',
        'start_time',
        'end_time',
        'registration_deadline',
        'max_participants',
        'registration_fee',
        'banner',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'registration_deadline' => 'datetime',
            'registration_fee' => 'decimal:2',
        ];
    }

    /**
     * Event belongs to a category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * User who created the event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated the event.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
