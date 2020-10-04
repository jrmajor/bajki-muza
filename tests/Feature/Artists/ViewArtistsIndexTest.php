<?php

use function Pest\Laravel\get;

get('artysci')
    ->assertOk()
    ->assertSeeLivewire('artists');
