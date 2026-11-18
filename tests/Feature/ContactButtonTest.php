<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactButtonTest extends TestCase
{
    public function test_contact_button_opens_our_locations_when_no_single_number_is_set(): void
    {
        config(['branding.contact_phone' => null]);

        foreach (['landing', 'how-it-works'] as $page) {
            $this->get(route($page))
                ->assertOk()
                ->assertSee('Contact us')
                ->assertSee('href="'.route('landing').'#locations"', false);
        }
    }

    public function test_landing_page_lists_both_units_with_tap_to_call_numbers_and_a_map(): void
    {
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('id="locations"', false)
            ->assertSee('Zafar Iqbal')
            ->assertSee('Gehlan Bypass, Allahabad')
            ->assertSee('href="tel:+923234911555"', false)
            ->assertSee('Ghulam Murtaza')
            ->assertSee('Bhana Sahiba, Tehsil Depalpur, District Okara')
            ->assertSee('href="tel:+923224422211"', false)
            ->assertSee('data-map', false)
            ->assertSee('30.877463', false)
            ->assertSee('30.699587', false);
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
