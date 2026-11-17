{{-- Shared shell for the marketing pages (home + how it works). --}}
@php
    $isUrdu = app()->getLocale() === 'ur';
    // "Contact us" calls the number once CONTACT_PHONE is set; until then it scrolls to the footer.
    $contactPhone = (string) config('branding.contact_phone');
    $contactHref = $contactPhone !== '' ? 'tel:'.preg_replace('/[^\d+]/', '', $contactPhone) : '#contact';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EverGreen Cold Storage')</title>
    <meta name="description" content="@yield('description', __('EverGreen Cold Storage — safe cold storage for your goods.'))">
    {{-- Apply the saved (or system) theme before first paint, so there is no flash. --}}
    <script>
        (function () {
            var theme;
            try { theme = localStorage.getItem('cs-theme'); } catch (e) {}
            if (theme !== 'light' && theme !== 'dark') {
                theme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    {{-- Bump ?v= when the icon changes so browsers drop their cached favicon. --}}
    <link rel="icon" href="{{ asset('images/evergreen-favicon.svg') }}?v=3" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('images/evergreen-apple-touch.png') }}?v=3">
    <meta name="theme-color" content="#06110f">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:600,700|inter:400,500,600" rel="stylesheet">
    @vite(['resources/css/home.css', 'resources/js/home.js'])
</head>
<body class="cs-body">

{{-- Snow loader: hidden by home.js on load, with a CSS fallback in case JS fails --}}
<div class="cs-loader" data-loader aria-hidden="true">
    <div class="cs-loader-inner">
        <svg class="cs-loader-flake" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M32 6v52M9.5 19l45 26M9.5 45l45-26"/>
            <path d="M25 10l7 6 7-6M25 54l7-6 7 6M8 27l9-1.5-3-8.5M56 37l-9 1.5 3 8.5M8 37l9 1.5-3 8.5M56 27l-9-1.5 3-8.5"/>
        </svg>
        <p>EverGreen <strong>Cold Storage</strong></p>
        <span class="cs-loader-bar"><i></i></span>
    </div>
    @for ($i = 0; $i < 18; $i++)
        <span class="cs-loader-snow" style="--x: {{ ($i * 37) % 100 }}%; --d: {{ 2 + ($i % 5) * 0.6 }}s; --delay: {{ ($i % 7) * -0.5 }}s; --s: {{ 4 + ($i % 4) * 2 }}px"></span>
    @endfor
</div>

{{-- Navigation --}}
<header class="cs-nav" data-nav>
    <div class="cs-shell cs-nav-inner">
        <a class="cs-brand" href="{{ url('/') }}" aria-label="{{ __('EverGreen Cold Storage home') }}">
            <img src="{{ asset('images/evergreen-favicon.svg') }}" alt="" width="36" height="36">
            <span>EverGreen <strong>Cold Storage</strong></span>
        </a>

        <nav class="cs-menu" id="cs-menu" data-menu aria-label="Main">
            <a href="{{ route('landing') }}#features">{{ __('Services') }}</a>
            <a href="{{ route('landing') }}#process">{{ __('Steps') }}</a>
            <a href="{{ route('landing') }}#faq">{{ __('FAQ') }}</a>
            <a href="{{ route('how-it-works') }}" @if (request()->routeIs('how-it-works')) aria-current="page" @endif>{{ __('How it works') }}</a>
            @include('landing.prefs')
        </nav>

        <div class="cs-nav-actions">
            @include('landing.prefs')
            <a class="cs-btn cs-btn-primary" href="{{ $contactHref }}"><span>{{ __('Contact us') }}</span></a>
            <button class="cs-burger" type="button" data-menu-toggle aria-controls="cs-menu" aria-expanded="false" aria-label="{{ __('Open menu') }}">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

@php($demoNotice = \App\Support\DemoAccount::landingNotice(request()->query('demo')))
@if ($demoNotice)
    {{-- Shown after leaving the demo (?demo=left|expired|blocked) --}}
    <div class="cs-toast {{ request()->query('demo') === 'left' ? '' : 'is-warn' }}" role="status" data-toast>
        <span class="cs-toast-icon" aria-hidden="true">
            @if (request()->query('demo') === 'left')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>
            @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            @endif
        </span>
        <p>{{ $demoNotice }}</p>
        <button type="button" class="cs-toast-close" data-toast-close aria-label="{{ __('Dismiss') }}">×</button>
    </div>
@endif

@yield('content')

{{-- Footer --}}
<footer class="cs-footer" id="contact">
    <div class="cs-ticker" aria-hidden="true">
        <div class="cs-ticker-track">
            @for ($i = 0; $i < 2; $i++)
                <span>{{ __('Safe cold rooms') }}</span><span>{{ __('Printed receipts') }}</span><span>{{ __('Take back any time') }}</span>
                <span>{{ __('Fair rent') }}</span><span>{{ __('Cash or bank') }}</span><span>{{ __('Book space ahead') }}</span>
            @endfor
        </div>
    </div>
    <div class="cs-shell cs-footer-grid">
        <div>
            <a class="cs-brand cs-brand-light" href="{{ url('/') }}">
                <img src="{{ asset('images/evergreen-favicon.svg') }}" alt="" width="32" height="32">
                <span>EverGreen <strong>Cold Storage</strong></span>
            </a>
            <p>{{ __('Safe cold storage for your goods.') }}</p>
        </div>
        <div>
            <h4>{{ __('Explore') }}</h4>
            <a href="{{ route('landing') }}#features">{{ __('Services') }}</a>
            <a href="{{ route('landing') }}#process">{{ __('Steps') }}</a>
            <a href="{{ route('landing') }}#faq">{{ __('FAQ') }}</a>
        </div>
        <div>
            <h4>{{ __('Learn more') }}</h4>
            <a href="{{ route('how-it-works') }}">{{ __('How it works') }}</a>
        </div>
    </div>
    <div class="cs-shell cs-footer-bottom">
        <p>© {{ date('Y') }} {{ __('EverGreen Cold Storage. All rights reserved.') }}</p>
        <p class="cs-credit-line">{{ __('Developed by') }} <a class="cs-credit" href="https://www.werqa.com/" target="_blank" rel="noopener">WeRQA</a></p>
    </div>
</footer>

</body>
</html>
