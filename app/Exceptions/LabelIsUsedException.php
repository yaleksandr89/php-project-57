<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class LabelIsUsedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Label is used in tasks and cannot be deleted.');
    }
}
