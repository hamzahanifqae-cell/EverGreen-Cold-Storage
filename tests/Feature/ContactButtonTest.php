<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactButtonTest extends TestCase
{
    public function test_contact_button_scrolls_to_the_footer_until_a_number_is_set(): void
    {
        config(['branding.contact_phone' => null]);

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Contact us')
            ->assertSee('href="#contact"', false)
            ->assertSee('id="contact"', false);
    }

    public function test_contact_button_calls_the_configured_number(): void
    {
        config(['branding.contact_phone' => '+92 300 1234567']);

        foreach (['landing', 'how-it-works'] as $page) {
            $this->get(route($page))
                ->assertOk()
                ->assertSee('href="tel:+923001234567"', false);
        }
    }

    public function test_how_it_works_is_still_in_the_menu(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('href="'.route('how-it-works').'"', false);
    }
}
