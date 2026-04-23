<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class TaskStatus extends Model
{
    use HasFactory;

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'status_id');
    }
}
