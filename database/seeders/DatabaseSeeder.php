<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        throw new CommandNotFoundException('Seeders are not implemented yet.');
    }
}
