<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Label;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LabelRepository
{
    public function getPaginated(): LengthAwarePaginator
    {
        return Label::query()
            ->latest('id')
            ->paginate(15);
    }

    public function create(array $labelData): Label
    {
        return Label::query()
            ->create($labelData);
    }

    public function update(Label $label, array $labelData): Label
    {
        $label
            ->update($labelData);

        return $label;
    }

    public function delete(Label $label): void
    {
        $label
            ->delete();
    }

    public function isLabelUsed(Label $label): bool
    {
        return $label
            ->tasks()
            ->exists();
    }
}
