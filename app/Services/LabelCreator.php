<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Label;
use App\Repositories\LabelRepository;

class LabelCreator
{
    public function __construct(
        private readonly LabelRepository $labelRepository,
    ) {
    }

    public function create(array $labelData): Label
    {
        return $this->labelRepository->create($labelData);
    }
}
