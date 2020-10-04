<?php

use function Pest\Laravel\get;

get('bajki')
    ->assertOk()
    ->assertSeeLivewire('tales');
