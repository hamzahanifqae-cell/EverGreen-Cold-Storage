<?php

namespace Tests\Feature;

use Tests\TestCase;

class HowItWorksPageTest extends TestCase
{
    public function test_how_it_works_page_is_available(): void
    {
        $this->get(route('how-it-works'))
            ->assertOk()
            ->assertSee('How EverGreen works')
            ->assertSee('From the door to payment')
            ->assertSee('Take goods in')
            ->assertSee('Take payment')
            ->assertSee('Is this my shop stock?');
    }

    public function test_landing_page_links_to_how_it_works(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee(route('how-it-works', absolute: false), false)
            ->assertSee('How it works')
            ->assertDontSee('Merchant login')
            ->assertDontSee('Open merchant panel')
            ->assertDontSee('Sign in to EverGreen')
            ->assertSee('data-lang-toggle', false)
            ->assertSee('اردو');
    }

    public function test_how_it_works_page_includes_language_toggle(): void
    {
        $this->get(route('how-it-works'))
            ->assertOk()
            ->assertSee('data-lang-toggle', false)
            ->assertSee('اردو')
            ->assertDontSee('Merchant login')
            ->assertDontSee('Open merchant panel')
            ->assertDontSee('Sign in to EverGreen');
    }
}
