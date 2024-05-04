<?php

namespace App\Images\Exceptions;

use Exception;

class FailedToReadStream extends Exception
{
    public function __construct(
        public string $path,
    ) {
        parent::__construct("Failed to read stream for file at {$this->path}.");
    }
}
