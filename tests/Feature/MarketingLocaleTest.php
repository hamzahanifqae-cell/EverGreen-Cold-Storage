<?php

namespace Tests\Feature;

use App\Http\Middleware\SetMarketingLocale;
use Tests\TestCase;

class MarketingLocaleTest extends TestCase
{
    public function test_marketing_pages_default_to_english(): void
    {
        foreach (['landing', 'how-it-works'] as $page) {
            $this->get(route($page))
                ->assertOk()
                ->assertSee('<html lang="en" dir="ltr">', false)
                ->assertSee('?lang=ur', false);
        }
    }

    public function test_choosing_urdu_stores_a_cookie_and_redirects_to_the_clean_url(): void
    {
        $this->get(route('landing').'?lang=ur')
            ->assertRedirect(route('landing'))
            ->assertCookie(SetMarketingLocale::COOKIE, 'ur');
    }

    public function test_urdu_pages_are_translated_and_right_to_left(): void
    {
        $this->withCookie(SetMarketingLocale::COOKIE, 'ur')
            ->get(route('landing'))
            ->assertOk()
            ->assertSee('<html lang="ur" dir="rtl">', false)
            ->assertSee('ہماری خدمات')
            ->assertDontSee('Our services');

        $this->withCookie(SetMarketingLocale::COOKIE, 'ur')
            ->get(route('how-it-works'))
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('مراحل پڑھیں');
    }

    public function test_unknown_language_falls_back_to_english(): void
    {
        $this->withCookie(SetMarketingLocale::COOKIE, 'fr')
            ->get(route('landing'))
            ->assertOk()
            ->assertSee('<html lang="en" dir="ltr">', false);
    }
}
