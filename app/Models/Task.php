<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaskStatus;
use App\Utils\HasStates;
use App\Utils\TaskStatusState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    use HasStates;

    protected $fillable = ['user_id', 'title', 'description', 'status'];
    protected $casts = ['type' => TaskStatus::class];
    protected $states = ['status' => TaskStatusState::class];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
