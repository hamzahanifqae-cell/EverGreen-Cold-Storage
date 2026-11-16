<!DOCTYPE html>
<html lang="en" data-lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>How it works · EverGreen Cold Storage</title>
    <meta name="description" content="Simple guide to EverGreen Cold Storage: take goods in, keep them safe, hand them back, and collect storage rent.">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:650,700,760|manrope:400,600,700|noto-sans-arabic:400,500,600,700" rel="stylesheet">
    <script>
        (() => {
            try {
                const storedTheme = localStorage.getItem('evergreen-landing-theme');
                const theme = storedTheme === 'light' || storedTheme === 'dark'
                    ? storedTheme
                    : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);

                const storedLang = localStorage.getItem('evergreen-landing-lang');
                const lang = storedLang === 'ur' ? 'ur' : 'en';
                document.documentElement.setAttribute('data-lang', lang);
                document.documentElement.setAttribute('lang', lang === 'ur' ? 'ur' : 'en');
                document.documentElement.setAttribute('dir', lang === 'ur' ? 'rtl' : 'ltr');
            } catch (_) {
                document.documentElement.setAttribute('data-theme', 'light');
                document.documentElement.setAttribute('data-lang', 'en');
                document.documentElement.setAttribute('lang', 'en');
                document.documentElement.setAttribute('dir', 'ltr');
            }
        })();
    </script>
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body class="landing-body" data-landing-page="guide">
    <div class="landing landing-guide-page">
        <header class="landing-nav-wrap">
            <div class="landing-shell landing-nav">
                <a class="landing-brand" href="{{ route('landing') }}" data-i18n-aria="nav.home" aria-label="EverGreen Cold Storage home">
                    <img class="landing-brand-logo-light" src="{{ asset('images/evergreen-logo.svg') }}" alt="EverGreen Cold Storage">
                    <img class="landing-brand-logo-dark" src="{{ asset('images/evergreen-logo-dark.svg') }}" alt="EverGreen Cold Storage">
                </a>
                <div class="landing-nav-actions">
                    <button
                        type="button"
                        class="landing-lang-toggle"
                        data-lang-toggle
                        aria-label="Switch to Urdu"
                        aria-pressed="false"
                        title="Switch to Urdu"
                    >
                        <span data-i18n="lang.label">اردو</span>
                    </button>
                    <button
                        type="button"
                        class="landing-theme-toggle"
                        data-theme-toggle
                        aria-label="Switch to dark mode"
                        aria-pressed="false"
                        title="Switch to dark mode"
                    >
                        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M21 14.3A8.2 8.2 0 0 1 9.7 3 7.4 7.4 0 1 0 21 14.3Z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <circle cx="12" cy="12" r="4"/>
                            <path d="M12 2.5v2.2M12 19.3v2.2M4.7 4.7l1.6 1.6M17.7 17.7l1.6 1.6M2.5 12h2.2M19.3 12h2.2M4.7 19.3l1.6-1.6M17.7 6.3l1.6-1.6" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <a class="landing-nav-cta" href="{{ route('landing') }}" data-i18n="guide.back">Back to home</a>
                </div>
            </div>
        </header>

        <main>
            <section class="landing-shell landing-guide-hero">
                <p class="landing-eyebrow" data-i18n="guide.eyebrow">Guide</p>
                <h1 class="landing-guide-title" data-i18n="guide.title">How EverGreen works</h1>
                <p class="landing-guide-lead" data-i18n="guide.lead">
                    Customers keep their own goods with you. You receive them into a chamber, track where they sit, return them when asked, and charge storage rent — without treating those goods as shop stock.
                </p>
                <div class="landing-actions">
                    <a class="landing-btn landing-btn-primary" href="{{ route('landing') }}" data-i18n="guide.back">Back to home</a>
                </div>
            </section>

            <section class="landing-band landing-band-alt">
                <div class="landing-shell landing-section">
                    <div class="landing-section-head landing-reveal" data-reveal>
                        <p class="landing-eyebrow" data-i18n="guide.overview.eyebrow">Overview</p>
                        <h2 data-i18n="guide.overview.title">What EverGreen helps you do</h2>
                        <p data-i18n="guide.overview.lead">Your customers own the goods. Your team runs the cold room and the rent from one place.</p>
                    </div>
                    <div class="landing-guide-overview">
                        <article class="landing-guide-point landing-reveal" data-reveal>
                            <h3 data-i18n="guide.overview.1.title">Their goods, not yours</h3>
                            <p data-i18n="guide.overview.1.copy">Goods stay customer-owned until they leave. This is storage — not buying and selling shop stock.</p>
                        </article>
                        <article class="landing-guide-point landing-reveal" data-reveal>
                            <h3 data-i18n="guide.overview.2.title">The daily loop</h3>
                            <p data-i18n="guide.overview.2.copy">Receive → track → return → Bill a withdrawal or Bill from stock → collect into cash in hand or bank.</p>
                        </article>
                        <article class="landing-guide-point landing-reveal" data-reveal>
                            <h3 data-i18n="guide.overview.3.title">Watch the cold rooms</h3>
                            <p data-i18n="guide.overview.3.copy">See how full each room is, check temperature, and get warned when something looks wrong.</p>
                        </article>
                    </div>
                </div>
            </section>

            <section class="landing-band">
                <div class="landing-shell landing-section">
                    <div class="landing-section-head landing-reveal" data-reveal>
                        <p class="landing-eyebrow" data-i18n="guide.setup.eyebrow">Before you start</p>
                        <h2 data-i18n="guide.setup.title">Set things up once</h2>
                        <p data-i18n="guide.setup.lead">Do this before the first customer drops goods. After that, daily work stays easy.</p>
                    </div>
                    <div class="landing-setup">
                        <div class="landing-setup-item landing-reveal" data-reveal>
                            <h3 class="landing-setup-title" data-i18n="guide.setup.1.title">Your place</h3>
                            <p class="landing-setup-copy" data-i18n="guide.setup.1.copy">Add your business, branch, and cold rooms. Say how much each room can hold and what temperature it should stay at. You can also add racks if you use them.</p>
                        </div>
                        <div class="landing-setup-item landing-reveal" data-reveal>
                            <h3 class="landing-setup-title" data-i18n="guide.setup.2.title">Your customers</h3>
                            <p class="landing-setup-copy" data-i18n="guide.setup.2.copy">Add the people or businesses who leave goods with you.</p>
                        </div>
                        <div class="landing-setup-item landing-reveal" data-reveal>
                            <h3 class="landing-setup-title" data-i18n="guide.setup.3.title">Goods &amp; prices</h3>
                            <p class="landing-setup-copy" data-i18n="guide.setup.3.copy">Add what you store (like potatoes or apples) and the storage price for each customer — by bag, by weight, or by days.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="landing-band landing-band-alt">
                <div class="landing-shell landing-section">
                    <div class="landing-section-head landing-reveal" data-reveal>
                        <p class="landing-eyebrow" data-i18n="guide.flow.eyebrow">Full story</p>
                        <h2 data-i18n="guide.flow.title">From the door to payment</h2>
                        <p data-i18n="guide.flow.lead">Follow these steps in order. Each one leads to the next.</p>
                    </div>

                    <div class="landing-guide-flow">
                        <article class="landing-guide-step landing-reveal" data-reveal>
                            <div class="landing-guide-step-index">01</div>
                            <div class="landing-guide-step-body">
                                <h3 data-i18n="guide.step.1.title">Take goods in</h3>
                                <p data-i18n="guide.step.1.copy">When a customer brings goods, write down who they are, what they brought, how many bags or how much weight, and which cold room it goes into. Save it, then confirm it.</p>
                                <p class="landing-guide-result" data-i18n="guide.step.1.result">What happens: those goods now show as “in store” for that customer.</p>
                            </div>
                        </article>

                        <article class="landing-guide-step landing-reveal" data-reveal>
                            <div class="landing-guide-step-index">02</div>
                            <div class="landing-guide-step-body">
                                <h3 data-i18n="guide.step.2.title">Keep and move goods</h3>
                                <p data-i18n="guide.step.2.copy">See what is still in each room. If you need to move bags from one room or rack to another, record the move. The customer still owns the same amount — only the place changes.</p>
                                <p class="landing-guide-result" data-i18n="guide.step.2.result">What happens: you always know where each customer’s goods are sitting.</p>
                            </div>
                        </article>

                        <article class="landing-guide-step landing-reveal" data-reveal>
                            <div class="landing-guide-step-index">03</div>
                            <div class="landing-guide-step-body">
                                <h3 data-i18n="guide.step.3.title">Check temperature</h3>
                                <p data-i18n="guide.step.3.copy">Create a temperature reading. If it is outside the chamber min/max, it appears on Action alerts. Acknowledge after checking.</p>
                                <p class="landing-guide-result" data-i18n="guide.step.3.result">What happens: out-of-range readings show as action alerts.</p>
                            </div>
                        </article>

                        <article class="landing-guide-step landing-reveal" data-reveal>
                            <div class="landing-guide-step-index">04</div>
                            <div class="landing-guide-step-body">
                                <h3 data-i18n="guide.step.4.title">Hand goods back</h3>
                                <p data-i18n="guide.step.4.copy">When the customer wants their goods, pick what is still in store for them and how much to give back. You cannot give more than they still have.</p>
                                <p class="landing-guide-result" data-i18n="guide.step.4.result">What happens: their “in store” amount goes down. This is not a shop sale.</p>
                            </div>
                        </article>

                        <article class="landing-guide-step landing-reveal" data-reveal>
                            <div class="landing-guide-step-index">05</div>
                            <div class="landing-guide-step-body">
                                <h3 data-i18n="guide.step.5.title">Note damage or loss</h3>
                                <p data-i18n="guide.step.5.copy">If bags tear or goods are lost, write it down with a short reason and how many were affected.</p>
                                <p class="landing-guide-result" data-i18n="guide.step.5.result">What happens: what the screen shows matches what is really in the room.</p>
                            </div>
                        </article>

                        <article class="landing-guide-step landing-reveal" data-reveal>
                            <div class="landing-guide-step-index">06</div>
                            <div class="landing-guide-step-body">
                                <h3 data-i18n="guide.step.6.title">Make a storage bill</h3>
                                <p data-i18n="guide.step.6.copy">After a return, use Storage bills → Bill a withdrawal (or Bill this withdrawal on the return) and choose cash in hand or bank. For stock still in store, use Bill from stock, preview, then post.</p>
                                <p class="landing-guide-result" data-i18n="guide.step.6.result">What happens: rent is billed for the withdrawal or for remaining stock.</p>
                            </div>
                        </article>

                        <article class="landing-guide-step landing-reveal" data-reveal>
                            <div class="landing-guide-step-index">07</div>
                            <div class="landing-guide-step-body">
                                <h3 data-i18n="guide.step.7.title">Take payment</h3>
                                <p data-i18n="guide.step.7.copy">Bill a withdrawal collects into cash in hand or bank when you create it. For other bills, record payment against the due amount into cash in hand or bank.</p>
                                <p class="landing-guide-result" data-i18n="guide.step.7.result">What happens: the bill shows collected amounts, and dashboard cash accounts update.</p>
                            </div>
                        </article>

                        <article class="landing-guide-step landing-reveal" data-reveal>
                            <div class="landing-guide-step-index">08</div>
                            <div class="landing-guide-step-body">
                                <h3 data-i18n="guide.step.8.title">See one customer clearly</h3>
                                <p data-i18n="guide.step.8.copy">Open a customer to see how much they gave you, how much they took back, and how much is still with you — plus links to the related records.</p>
                                <p class="landing-guide-result" data-i18n="guide.step.8.result">What happens: you can explain their position in one place.</p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section class="landing-band">
                <div class="landing-shell landing-section">
                    <div class="landing-section-head landing-reveal" data-reveal>
                        <p class="landing-eyebrow" data-i18n="guide.extra.eyebrow">Extra modules</p>
                        <h2 data-i18n="guide.extra.title">Other tools in the panel</h2>
                        <p data-i18n="guide.extra.lead">Use these when you need more detail on chambers or money.</p>
                    </div>
                    <div class="landing-flow landing-flow-caps">
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">FULL</div>
                            <h3 class="landing-flow-title" data-i18n="guide.extra.1.title">Capacity heatmap</h3>
                            <p class="landing-flow-copy" data-i18n="guide.extra.1.copy">See how full each chamber is before you take new goods in.</p>
                        </div>
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">BOOK</div>
                            <h3 class="landing-flow-title" data-i18n="guide.extra.2.title">Reservations</h3>
                            <p class="landing-flow-copy" data-i18n="guide.extra.2.copy">Book space before goods arrive. Confirm, fulfill, or cancel.</p>
                        </div>
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">CASH</div>
                            <h3 class="landing-flow-title" data-i18n="guide.extra.3.title">Cash and bank totals</h3>
                            <p class="landing-flow-copy" data-i18n="guide.extra.3.copy">The dashboard shows cash in hand and bank balances from collected payments.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="landing-band landing-band-alt">
                <div class="landing-shell landing-section">
                    <div class="landing-clarify landing-reveal" data-reveal>
                        <div class="landing-clarify-copy">
                            <p class="landing-eyebrow" data-i18n="guide.clarify.eyebrow">Important</p>
                            <h2 data-i18n="guide.clarify.title">Is this my shop stock?</h2>
                            <p class="landing-clarify-answer"><span data-i18n="guide.clarify.no">No.</span> <span data-i18n="guide.clarify.answer">Cold storage movements never create classic sales or purchases product stock.</span></p>
                        </div>
                        <aside class="landing-about-panel">
                            <p class="landing-about-label" data-i18n="guide.clarify.label">Why that matters</p>
                            <p data-i18n="guide.clarify.aside">
                                The goods stay with the customer until they take them back. That is why EverGreen is built for cold stores — not for a normal shop counter.
                            </p>
                        </aside>
                    </div>
                </div>
            </section>

            <section class="landing-band">
                <div class="landing-shell landing-section landing-section-cta">
                    <div class="landing-cta landing-reveal" data-reveal>
                        <p class="landing-eyebrow" data-i18n="guide.cta.eyebrow">Next</p>
                        <h2 data-i18n="guide.cta.title">Ready to learn more?</h2>
                        <p data-i18n="guide.cta.copy">Go back to the home page to see what EverGreen covers for cold store teams.</p>
                        <div class="landing-actions landing-actions-center">
                            <a class="landing-btn landing-btn-primary" href="{{ route('landing') }}" data-i18n="guide.back">Back to home</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="landing-shell landing-footer">
            <div class="landing-footer-rule" aria-hidden="true"></div>
            <p data-i18n="footer.guide">EverGreen Cold Storage · Built for cold stores that hold customer goods</p>
        </footer>
    </div>
</body>
</html>
