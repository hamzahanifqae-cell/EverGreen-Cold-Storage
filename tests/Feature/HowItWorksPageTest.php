<?php

namespace Tests\Feature;

use Tests\TestCase;

class HowItWorksPageTest extends TestCase
{
    public function test_how_it_works_page_is_available(): void
    {
        $this->get(route('how-it-works'))
            ->assertOk()
            ->assertSee('How Cold Storage CRM')
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
            ->assertDontSee('EverGreen');
    }

    public function test_marketing_pages_only_offer_the_demo_not_merchant_login(): void
    {
        foreach (['landing', 'how-it-works'] as $page) {
            $this->get(route($page))
                ->assertOk()
                ->assertSee('/demo/login', false)
                ->assertDontSee('/merchant/login', false)
                ->assertDontSee('Log in')
                ->assertDontSee('EverGreen');
        }
    }
}
