<?php

it('redirects from / to /bajki')
    ->get('/')
    ->assertRedirect('/bajki');
