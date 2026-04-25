<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Label;
use App\Repositories\LabelRepository;

class LabelUpdater
{
    public function __construct(
        private readonly LabelRepository $labelRepository,
    ) {
    }

    public function update(Label $label, array $labelData): Label
    {
        return $this->labelRepository->update($label, $labelData);
    }
}
