<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Label;
use App\Repositories\LabelRepository;

class LabelDeleter
{
    public function __construct(
        private readonly LabelRepository $labelRepository,
    ) {
    }

    public function delete(Label $label): void
    {
        $this->labelRepository->delete($label);
    }
}
