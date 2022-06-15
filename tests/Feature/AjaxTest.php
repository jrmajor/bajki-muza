<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class AjaxTest extends TestCase
{
    #[TestDox('guest can not access ajax/artists endpoint')]
    public function testGuestArtists(): void
    {
        $this->get('ajax/artists')->assertRedirect('login');
    }

    #[TestDox('user can access ajax/artists endpoint')]
    public function testUserArtists(): void
    {
        $this->asUser()->get('ajax/artists')->assertOk();
    }

    #[TestDox('guest can not access ajax/discogs endpoint')]
    public function testGuestDiscogs(): void
    {
        $this->get('ajax/discogs')->assertRedirect('login');
    }

    #[TestDox('user can access ajax/discogs endpoint')]
    public function testUserDiscogs(): void
    {
        $this->asUser()->get('ajax/discogs')->assertOk();
    }

    #[TestDox('guest can not access ajax/filmpolski endpoint')]
    public function testGuestFilmpolski(): void
    {
        $this->get('ajax/filmpolski')->assertRedirect('login');
    }

    #[TestDox('user can access ajax/filmpolski endpoint')]
    public function testUserFilmpolski(): void
    {
        $this->asUser()->get('ajax/filmpolski')->assertOk();
    }

    #[TestDox('guest can not access ajax/wikipedia endpoint')]
    public function testGuestWikipedia(): void
    {
        $this->get('ajax/wikipedia')->assertRedirect('login');
    }

    #[TestDox('user can access ajax/wikipedia endpoint')]
    public function testUserWikipedia(): void
    {
        $this->asUser()->get('ajax/wikipedia')->assertOk();
    }
}
