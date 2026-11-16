<!DOCTYPE html>
<html lang="en" data-lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EverGreen Cold Storage</title>
    <meta name="description" content="EverGreen Cold Storage — merchant panel for cold stores: goods receipts, returns, storage bills, cash in hand or bank, chamber occupancy, and action alerts.">
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
<body class="landing-body" data-landing-page="landing">
    <div class="landing">
        <header class="landing-nav-wrap">
            <div class="landing-shell landing-nav">
                <a class="landing-brand" href="{{ url('/') }}" data-i18n-aria="nav.home" aria-label="EverGreen Cold Storage home">
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
                    <a class="landing-nav-cta" href="{{ route('how-it-works') }}" data-i18n="nav.how">How it works</a>
                </div>
            </div>
        </header>

        <main>
            <section class="landing-hero">
                <canvas class="landing-frost" data-frost aria-hidden="true"></canvas>
                <div class="landing-mist-floor" aria-hidden="true">
                    <span class="landing-mist-band landing-mist-band-1"></span>
                    <span class="landing-mist-band landing-mist-band-2"></span>
                    <span class="landing-mist-band landing-mist-band-3"></span>
                </div>
                <div class="landing-shell landing-hero-inner">
                    <div class="landing-hero-grid">
                        <div class="landing-hero-copy" data-hero-copy>
                            <p class="landing-kicker">
                                EverGreen
                                <span data-i18n="hero.tag">COLD STORAGE</span>
                            </p>
                            <h1 class="landing-headline" data-i18n="hero.headline">Receive goods. Track stock. Return and collect rent.</h1>
                            <p class="landing-lead" data-i18n="hero.lead">
                                EverGreen is a merchant panel for cold stores. Customers keep their own goods with you — you receive them into a chamber, track where they sit, hand them back when asked, and charge storage rent. It is not a normal shop POS.
                            </p>
                        </div>

                        <div class="landing-stage" data-stage aria-hidden="true">
                            <div class="landing-stage-glow"></div>
                            <div class="landing-flow-board" data-chamber data-active-step="0">
                                <div class="landing-flow-board-face">
                                    <div class="landing-flow-board-top">
                                        <p class="landing-flow-board-eyebrow" data-i18n="hero.visual.eyebrow">Cold storage flow</p>
                                    </div>
                                    <div class="landing-flow-board-chamber">
                                        <span class="landing-flow-board-rack"></span>
                                        <span class="landing-flow-board-rack"></span>
                                        <span class="landing-flow-board-rack"></span>
                                        <span class="landing-flow-board-lot landing-flow-board-lot-a"></span>
                                        <span class="landing-flow-board-lot landing-flow-board-lot-b"></span>
                                        <span class="landing-flow-board-lot landing-flow-board-lot-c"></span>
                                    </div>
                                    <p class="landing-flow-board-status" data-flow-status data-i18n="hero.visual.1">Receive goods into a chamber — stock appears after post.</p>
                                    <ol class="landing-flow-cycle" data-flow-cycle>
                                        <li class="landing-flow-node is-active" data-flow-step="0">
                                            <span class="landing-flow-node-num">01</span>
                                            <span class="landing-flow-node-label" data-i18n="flow.1.title">Receive</span>
                                        </li>
                                        <li class="landing-flow-node" data-flow-step="1">
                                            <span class="landing-flow-node-num">02</span>
                                            <span class="landing-flow-node-label" data-i18n="flow.2.title">Store</span>
                                        </li>
                                        <li class="landing-flow-node" data-flow-step="2">
                                            <span class="landing-flow-node-num">03</span>
                                            <span class="landing-flow-node-label" data-i18n="flow.3.title">Return</span>
                                        </li>
                                        <li class="landing-flow-node" data-flow-step="3">
                                            <span class="landing-flow-node-num">04</span>
                                            <span class="landing-flow-node-label" data-i18n="flow.4.title">Bill &amp; collect</span>
                                        </li>
                                    </ol>
                                    <div class="landing-flow-path" aria-hidden="true">
                                        <span class="landing-flow-token" data-flow-token></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="about" class="landing-band">
                <div class="landing-shell landing-section">
                    <div class="landing-about">
                        <div class="landing-section-head landing-reveal" data-reveal>
                            <p class="landing-eyebrow" data-i18n="about.eyebrow">Product</p>
                            <h2 data-i18n="about.title">Built for cold stores, not retail POS.</h2>
                            <p data-i18n="about.copy">
                                EverGreen is a merchant panel for cold stores. Customers keep their own goods with you (bags, crates, weight). You receive goods into a chamber, track where they sit, return them when the customer asks, and charge storage rent and collect payment.
                            </p>
                        </div>
                        <aside class="landing-about-panel landing-reveal" data-reveal>
                            <p class="landing-about-label" data-i18n="about.label">Customer-owned storage</p>
                            <p data-i18n="about.aside">
                                Goods stay customer-owned until they leave. Your team posts goods receipts, returns, transfers, and damage adjustments. Cold storage movements never create classic sales or purchases product stock.
                            </p>
                        </aside>
                    </div>
                </div>
            </section>

            <section id="who" class="landing-band landing-band-alt">
                <div class="landing-shell landing-section">
                    <div class="landing-section-head landing-reveal" data-reveal>
                        <p class="landing-eyebrow" data-i18n="who.eyebrow">Audience</p>
                        <h2 data-i18n="who.title">Who it is for</h2>
                    </div>
                    <div class="landing-split">
                        <article class="landing-role landing-reveal" data-reveal>
                            <span class="landing-role-mark" aria-hidden="true">01</span>
                            <h3 class="landing-split-title" data-i18n="who.role1.title">Cold store teams</h3>
                            <p class="landing-split-copy" data-i18n="who.role1.copy">Receive goods into a chamber or rack, track stock on hand, and return goods when the customer asks. Quantity cannot exceed what is available.</p>
                        </article>
                        <article class="landing-role landing-reveal" data-reveal>
                            <span class="landing-role-mark" aria-hidden="true">02</span>
                            <h3 class="landing-split-title" data-i18n="who.role2.title">Billing &amp; cash desks</h3>
                            <p class="landing-split-copy" data-i18n="who.role2.copy">Bill a withdrawal after a return, or bill from remaining stock. Preview the calculation, then collect into cash in hand or bank so balances update on the dashboard.</p>
                        </article>
                    </div>
                </div>
            </section>

            <section class="landing-band">
                <div class="landing-shell landing-section">
                    <div class="landing-section-head landing-reveal" data-reveal>
                        <p class="landing-eyebrow" data-i18n="flow.eyebrow">Operations</p>
                        <h2 data-i18n="flow.title">The daily flow</h2>
                        <p data-i18n="flow.lead">One operating loop from the door to the bill.</p>
                    </div>

                    <div class="landing-flow landing-flow-steps">
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">01</div>
                            <h3 class="landing-flow-title" data-i18n="flow.1.title">Receive</h3>
                            <p class="landing-flow-copy" data-i18n="flow.1.copy">Post inbound lots with product, packages, weight, and chamber allocation. Stock appears on the dashboard after post.</p>
                        </div>
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">02</div>
                            <h3 class="landing-flow-title" data-i18n="flow.2.title">Store</h3>
                            <p class="landing-flow-copy" data-i18n="flow.2.copy">Track stock on hand by lot, chamber occupancy, transfers between locations, temperature readings, and action alerts.</p>
                        </div>
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">03</div>
                            <h3 class="landing-flow-title" data-i18n="flow.3.title">Return</h3>
                            <p class="landing-flow-copy" data-i18n="flow.3.copy">Dispatch partial or full lots for the same customer. Available quantity is enforced; no sale invoice is created.</p>
                        </div>
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">04</div>
                            <h3 class="landing-flow-title" data-i18n="flow.4.title">Bill &amp; collect</h3>
                            <p class="landing-flow-copy" data-i18n="flow.4.copy">After a return, use Bill a withdrawal (cash in hand or bank). For stock still in store, use Bill from stock, preview, post, then record payment.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="capabilities" class="landing-band landing-band-alt">
                <div class="landing-shell landing-section">
                    <div class="landing-section-head landing-reveal" data-reveal>
                        <p class="landing-eyebrow" data-i18n="caps.eyebrow">Workspace</p>
                        <h2 data-i18n="caps.title">What the panel covers</h2>
                        <p data-i18n="caps.lead">The cold storage modules in the merchant panel.</p>
                    </div>

                    <div class="landing-flow landing-flow-caps">
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">IN</div>
                            <h3 class="landing-flow-title" data-i18n="caps.1.title">Goods receipts</h3>
                            <p class="landing-flow-copy" data-i18n="caps.1.copy">Branch, customer, lot lines, chamber allocation, draft to posted workflow.</p>
                        </div>
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">OUT</div>
                            <h3 class="landing-flow-title" data-i18n="caps.2.title">Returns</h3>
                            <p class="landing-flow-copy" data-i18n="caps.2.copy">Hand stock back from active lots with partial quantities and stock safety checks.</p>
                        </div>
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">AR</div>
                            <h3 class="landing-flow-title" data-i18n="caps.3.title">Storage billing</h3>
                            <p class="landing-flow-copy" data-i18n="caps.3.copy">Rate cards, Bill a withdrawal, Bill from stock, invoices, due amounts, and collected payments.</p>
                        </div>
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">CA</div>
                            <h3 class="landing-flow-title" data-i18n="caps.4.title">Cash accounts</h3>
                            <p class="landing-flow-copy" data-i18n="caps.4.copy">Route each payment to cash in hand or bank; live balances on the dashboard.</p>
                        </div>
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">ID</div>
                            <h3 class="landing-flow-title" data-i18n="caps.5.title">Customer overview</h3>
                            <p class="landing-flow-copy" data-i18n="caps.5.copy">Per customer: packages given for storage, returned, still in store, with deep links to lists.</p>
                        </div>
                        <div class="landing-flow-row landing-reveal" data-reveal>
                            <div class="landing-flow-index">°C</div>
                            <h3 class="landing-flow-title" data-i18n="caps.6.title">Chamber health</h3>
                            <p class="landing-flow-copy" data-i18n="caps.6.copy">Temperature readings, action alerts, capacity heatmap, and optional reservations.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="landing-band">
                <div class="landing-shell landing-section">
                    <div class="landing-section-head landing-reveal" data-reveal>
                        <p class="landing-eyebrow" data-i18n="setup.eyebrow">Configuration</p>
                        <h2 data-i18n="setup.title">Setup once, operate every day</h2>
                        <p data-i18n="setup.lead">Configure the facility, then the daily work stays simple.</p>
                    </div>
                    <div class="landing-setup">
                        <div class="landing-setup-item landing-reveal" data-reveal>
                            <h3 class="landing-setup-title" data-i18n="setup.1.title">Facility</h3>
                            <p class="landing-setup-copy" data-i18n="setup.1.copy">Businesses, branches, chambers with capacity and temperature limits, optional racks and bins.</p>
                        </div>
                        <div class="landing-setup-item landing-reveal" data-reveal>
                            <h3 class="landing-setup-title" data-i18n="setup.2.title">Parties &amp; products</h3>
                            <p class="landing-setup-copy" data-i18n="setup.2.copy">Customers (goods owners), products (for example Potatoes), and storage rate cards for that customer.</p>
                        </div>
                        <div class="landing-setup-item landing-reveal" data-reveal>
                            <h3 class="landing-setup-title" data-i18n="setup.3.title">Exceptions</h3>
                            <p class="landing-setup-copy" data-i18n="setup.3.copy">Damage and adjustments for torn or lost packages, with a reason, so the ledger stays accurate.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="landing-band landing-band-alt">
                <div class="landing-shell landing-section">
                    <div class="landing-clarify landing-reveal" data-reveal>
                        <div class="landing-clarify-copy">
                            <p class="landing-eyebrow" data-i18n="clarify.eyebrow">Clarity</p>
                            <h2 data-i18n="clarify.title">Clear answer when someone asks “is this my shop stock?”</h2>
                            <p class="landing-clarify-answer"><span data-i18n="clarify.no">No.</span> <span data-i18n="clarify.answer">Cold storage movements never create classic sales or purchases product stock.</span></p>
                        </div>
                        <aside class="landing-about-panel">
                            <p class="landing-about-label" data-i18n="clarify.label">Why it matters</p>
                            <p data-i18n="clarify.aside">
                                Goods stay customer-owned until they leave. That is the difference from a normal shop POS.
                            </p>
                        </aside>
                    </div>
                </div>
            </section>

            <section class="landing-band">
                <div class="landing-shell landing-section landing-section-cta">
                    <div class="landing-cta landing-reveal" data-reveal>
                        <p class="landing-eyebrow" data-i18n="cta.eyebrow">Get started</p>
                        <h2 data-i18n="cta.title">Run the cold store from one merchant panel.</h2>
                        <p data-i18n="cta.copy">Dashboard, goods receipts, returns, storage bills, cash in hand or bank, chamber occupancy, and action alerts.</p>
                        <div class="landing-actions landing-actions-center">
                            <a class="landing-btn landing-btn-primary" href="{{ route('how-it-works') }}" data-i18n="hero.cta.how">How it works</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="landing-shell landing-footer">
            <div class="landing-footer-rule" aria-hidden="true"></div>
            <p data-i18n="footer.landing">EverGreen Cold Storage · Merchant operations for customer-owned goods</p>
        </footer>
    </div>
</body>
</html>
