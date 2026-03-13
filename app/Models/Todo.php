<?php

namespace App\Models;

use App\Enums\Priority;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'title',
        'priority',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'priority' => Priority::class,
            'is_completed' => 'boolean',
        ];
    }
}
