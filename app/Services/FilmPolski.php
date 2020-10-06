<?php

namespace App\Services;

class FilmPolski
{
    public function url(int $id): string
    {
        return "http://www.filmpolski.pl/fp/index.php?osoba=$id";
    }
}
