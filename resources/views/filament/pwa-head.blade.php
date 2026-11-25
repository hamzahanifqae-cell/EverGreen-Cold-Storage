{{--
    Makes the CRM installable as an app on phones and tablets only.
    The manifest is added by script on mobile devices, so desktop browsers never offer
    "Install app" (no address-bar icon, no menu item, no banner).
    See public/manifest.json, public/sw.js and filament/pwa-install.blade.php.
--}}
<meta name="theme-color" content="#0f766e">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="EverGreen">
<link rel="apple-touch-icon" href="{{ asset('images/evergreen-apple-touch.png') }}">
<script>
    (function () {
        var ua = navigator.userAgent;
        var isMobile = /Android|iPhone|iPad|iPod/i.test(ua)
            || (navigator.userAgentData && navigator.userAgentData.mobile === true)
            || (/Macintosh/.test(ua) && navigator.maxTouchPoints > 1); // iPadOS reports a Mac user agent
        window.egIsMobileDevice = isMobile;

        var standalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        if (standalone) {
            // Running as the installed app: remember it, so the browser on this phone stops offering the install.
            try { localStorage.setItem('eg-app-installed', '1'); } catch (e) {}
        }

        if (!isMobile) return; // desktop: not installable, no service worker

        var link = document.createElement('link');
        link.rel = 'manifest';
        link.href = '{{ asset('manifest.json') }}';
        document.head.appendChild(link);

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('{{ asset('sw.js') }}', { scope: '/' }).catch(function () {});
            });
        }
    })();
</script>
