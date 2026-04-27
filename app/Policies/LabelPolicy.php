<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Label;
use App\Models\User;

class LabelPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Label $label): bool
    {
        return true;
    }

    public function delete(User $user, Label $label): bool
    {
        return true;
    }
}
