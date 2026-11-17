{{-- Language switch + light/dark toggle (shown in the bar on desktop, inside the menu on phones) --}}
<div class="cs-prefs">
    <div class="cs-lang" role="group" aria-label="{{ __('Language') }}">
        <a href="{{ url()->current() }}?lang=en" lang="en" hreflang="en" @if (app()->getLocale() === 'en') aria-current="true" @endif>EN</a>
        <a href="{{ url()->current() }}?lang=ur" lang="ur" hreflang="ur" @if (app()->getLocale() === 'ur') aria-current="true" @endif>اردو</a>
    </div>
    <button class="cs-theme-btn" type="button" data-theme-toggle aria-label="{{ __('Switch light or dark mode') }}">
        <svg class="cs-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
        </svg>
        <svg class="cs-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>
        </svg>
    </button>
</div>
