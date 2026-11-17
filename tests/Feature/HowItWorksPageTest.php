<?php

namespace Tests\Feature;

use Tests\TestCase;

class HowItWorksPageTest extends TestCase
{
    public function test_how_it_works_page_is_available(): void
    {
        $this->get(route('how-it-works'))
            ->assertOk()
            ->assertSee('How EverGreen Cold Storage')
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
            ->assertSee('EverGreen Cold Storage')
            ->assertDontSee('Cold Storage CRM');
    }

    public function test_marketing_pages_have_no_demo_or_login_links(): void
    {
        foreach (['landing', 'how-it-works'] as $page) {
            $this->get(route($page))
                ->assertOk()
                ->assertDontSee('/demo/login', false)
                ->assertDontSee('Try the live demo')
                ->assertDontSee('/merchant/login', false)
                ->assertDontSee('Log in')
                ->assertDontSee('Cold Storage CRM');
        }
    }
}
