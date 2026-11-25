{{--
    "Install app" banner for the CRM (merchant + staff panels).

    Chrome's own install popup is unreliable: after one dismissal it stays hidden on that
    phone for ~3 months, and other browsers never show it. This banner gives a one-tap
    Install button whenever Chrome allows it, and step-by-step instructions otherwise.
    Phones and tablets only (never on desktop). Never shown on a phone where the installed
    app has been opened, and hidden for 7 days after ✕.
--}}
<div class="eg-install" data-eg-install hidden role="dialog" aria-live="polite" aria-label="Install the EverGreen app">
    <img src="{{ asset('images/pwa/icon-192.png') }}" alt="" width="44" height="44">
    <div class="eg-install-text">
        <strong>Install the EverGreen app</strong>
        <span data-eg-install-hint>Open the CRM from your home screen, full screen.</span>
    </div>
    <button type="button" class="eg-install-btn" data-eg-install-go hidden>Install</button>
    <button type="button" class="eg-install-close" data-eg-install-close aria-label="Not now">✕</button>
</div>

<style>
    .eg-install {
        position: fixed; left: 12px; right: 12px; bottom: max(12px, env(safe-area-inset-bottom)); z-index: 60;
        display: flex; align-items: center; gap: 12px; max-width: 460px; margin: 0 auto;
        padding: 12px 12px 12px 14px; border-radius: 16px;
        background: #0b1a17; color: #e3efea; border: 1px solid rgba(16, 185, 129, 0.35);
        box-shadow: 0 18px 40px -12px rgba(0, 0, 0, 0.55);
        font: 14px/1.4 system-ui, -apple-system, "Segoe UI", sans-serif;
        animation: eg-install-in 300ms ease both;
    }
    .eg-install[hidden] { display: none; }
    .eg-install img { flex: none; border-radius: 11px; }
    .eg-install-text { flex: 1; min-width: 0; display: grid; gap: 2px; }
    .eg-install-text strong { font-size: 14.5px; color: #fff; }
    .eg-install-text span { color: #9fb5ad; font-size: 13px; }
    .eg-install-btn {
        flex: none; padding: 9px 16px; border: 0; border-radius: 999px; cursor: pointer;
        background: #10b981; color: #052e26; font: 700 14px system-ui, sans-serif;
    }
    .eg-install-btn[hidden] { display: none; }
    .eg-install-close {
        flex: none; width: 32px; height: 32px; border: 0; border-radius: 50%; cursor: pointer;
        background: rgba(255, 255, 255, 0.08); color: #cfe3dc; font-size: 14px;
    }
    @keyframes eg-install-in { from { opacity: 0; transform: translateY(12px); } }
    @media (prefers-reduced-motion: reduce) { .eg-install { animation: none; } }
</style>

<script>
    (function () {
        var banner = document.querySelector('[data-eg-install]');
        if (!banner) return;

        // Desktop browsers: never offer the app.
        if (!window.egIsMobileDevice) return;

        var hint = banner.querySelector('[data-eg-install-hint]');
        var go = banner.querySelector('[data-eg-install-go]');
        var SNOOZE_KEY = 'eg-install-dismissed-at';
        var INSTALLED_KEY = 'eg-app-installed';
        var SNOOZE_MS = 7 * 24 * 60 * 60 * 1000;
        var deferred = null;
        var ua = navigator.userAgent;

        function read(key) { try { return localStorage.getItem(key); } catch (e) { return null; } }
        function write(key, value) { try { value === null ? localStorage.removeItem(key) : localStorage.setItem(key, value); } catch (e) {} }

        // Running as the installed app (head script also records this): nothing to offer.
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) return;

        function snoozed() { return Date.now() - Number(read(SNOOZE_KEY) || 0) < SNOOZE_MS; }
        function show() { if (!snoozed()) banner.hidden = false; }
        function hide() { banner.hidden = true; }

        // Chrome / Edge / Samsung Internet only fire this when the app is NOT installed on this phone,
        // so it also clears an old "installed" note (e.g. the app was uninstalled since).
        window.addEventListener('beforeinstallprompt', function (event) {
            event.preventDefault();
            deferred = event;
            write(INSTALLED_KEY, null);
            go.hidden = false;
            show();
        });

        go.addEventListener('click', function () {
            if (!deferred) return;
            deferred.prompt();
            deferred.userChoice.finally(function () { deferred = null; hide(); });
        });

        banner.querySelector('[data-eg-install-close]').addEventListener('click', function () {
            write(SNOOZE_KEY, String(Date.now()));
            hide();
        });

        window.addEventListener('appinstalled', function () {
            write(INSTALLED_KEY, '1');
            hide();
        });

        // No install offer from the browser: show manual steps, unless the app is already on this phone.
        window.addEventListener('load', function () {
            setTimeout(function () {
                if (deferred || !banner.hidden) return;
                if (read(INSTALLED_KEY) === '1') return; // installed app has been opened on this phone

                var checkInstalled = navigator.getInstalledRelatedApps
                    ? navigator.getInstalledRelatedApps().catch(function () { return []; })
                    : Promise.resolve([]);

                checkInstalled.then(function (apps) {
                    if (apps && apps.length) { write(INSTALLED_KEY, '1'); return; }
                    var isIOS = /iPhone|iPad|iPod/i.test(ua) || (/Macintosh/.test(ua) && navigator.maxTouchPoints > 1);
                    hint.textContent = isIOS
                        ? 'Tap Share, then “Add to Home Screen”.'
                        : /SamsungBrowser/i.test(ua)
                            ? 'Tap the menu ☰, then “Add page to” → “Home screen”.'
                            : 'Tap the menu ⋮, then “Install app” or “Add to Home screen”.';
                    go.hidden = true;
                    show();
                });
            }, 3500);
        });
    })();
</script>
