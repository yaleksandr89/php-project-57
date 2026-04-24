<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\LabelIsUsedException;
use App\Models\Label;
use App\Repositories\LabelRepository;

class LabelDeleter
{
    public function __construct(
        private readonly LabelRepository $labelRepository,
    ) {}

    public function delete(Label $label): void
    {
        if ($this->labelRepository->isLabelUsed($label)) {
            throw new LabelIsUsedException;
        }

        $this->labelRepository->delete($label);
    }
}
