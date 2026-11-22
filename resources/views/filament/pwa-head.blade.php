{{-- Makes the CRM installable as an app (Android "Install app" / home screen). See public/manifest.json and public/sw.js. --}}
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#0f766e">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="EverGreen">
<link rel="apple-touch-icon" href="{{ asset('images/evergreen-apple-touch.png') }}">
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('{{ asset('sw.js') }}', { scope: '/' }).catch(function () {});
        });
    }
</script>
