<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class TaskStatusIsUsedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Task status is used in tasks and cannot be deleted.');
    }
}
