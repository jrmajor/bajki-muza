<?php

namespace App\Console\Commands;

enum ExitCode: int
{
    case Ok = 0;
    case Error = 1;
}
