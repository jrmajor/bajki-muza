<?php

use function Pest\Laravel\get;

get('/')
    ->assertRedirect('bajki');
