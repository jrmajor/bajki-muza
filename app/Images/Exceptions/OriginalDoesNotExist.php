<?php

namespace App\Images\Exceptions;

use Exception;

class OriginalDoesNotExist extends Exception
{
    public function __construct(
        public string $path,
    ) {
        parent::__construct("Original cover {$this->path} does not exist.");
    }
}
