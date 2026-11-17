@php
    use App\Support\DomainConfig;

    // The merchant panel lives on the app host when the domains are split.
    $appBase = DomainConfig::splitEnabled() ? DomainConfig::appUrl() : url('');
    $demoUrl = $appBase.'/demo/login';
@endphp

@extends('landing.layout')

@section('title', 'Cold Storage CRM — Run your cold store from receipt to rent')
@section('description', 'Cold Storage CRM is the merchant panel for cold stores: goods receipts, returns, storage billing, cash in hand or bank, chamber occupancy, temperature readings and action alerts.')

@section('content')
<main>
    {{-- Hero --}}
    <section class="cs-hero">
        <div class="cs-hero-frost" aria-hidden="true"></div>
        <canvas class="cs-snow" data-snow aria-hidden="true"></canvas>
        <div class="cs-shell cs-hero-grid">
            <div class="cs-hero-copy" data-hero-in>
                <p class="cs-pill"><span class="cs-dot"></span> Cold storage management software</p>
                <h1>Track every lot from <em>receipt</em> to <em>rent collection</em>.</h1>
                <p class="cs-lead">
                    Cold Storage CRM is the merchant panel for cold stores. Receive customers' goods into a chamber,
                    see exactly where they sit, hand them back on request, and bill storage rent into cash in hand or bank.
                </p>
                <div class="cs-actions">
                    <a class="cs-btn cs-btn-primary cs-btn-lg" href="{{ $demoUrl }}">Try the live demo</a>
                    <a class="cs-btn cs-btn-ghost cs-btn-lg" href="{{ route('how-it-works') }}">See how it works</a>
                </div>
                <ul class="cs-hero-points">
                    <li>No sign-up for the demo</li>
                    <li>Printable ledgers &amp; registers</li>
                    <li>Per-lot stock safety checks</li>
                </ul>
            </div>

            {{-- 3D product scene (illustrative sample data) --}}
            <div class="cs-scene" data-scene aria-hidden="true">
                <div class="cs-scene-rig" data-rig>
                    <div class="cs-scene-glow"></div>

                    <div class="cs-mock">
                        <div class="cs-mock-bar">
                            <span></span><span></span><span></span>
                            <p>Dashboard</p>
                            <em class="cs-live"><i></i> Live</em>
                        </div>
                        <div class="cs-mock-body">
                            <div class="cs-mock-kpis cs-z1">
                                <div><small>In store</small><strong data-count="12480">12,480</strong><em>packages</em></div>
                                <div><small>Rent due</small><strong>Rs <span data-count="386">386</span>k</strong>
                                    <svg class="cs-spark" viewBox="0 0 80 22" preserveAspectRatio="none"><path d="M0 18 L10 15 L20 16 L30 11 L40 13 L50 8 L60 9 L70 4 L80 5"/></svg>
                                </div>
                                <div><small>Alerts</small><strong class="is-warn">2</strong><em>need action</em></div>
                            </div>
                            <p class="cs-mock-label">Chamber occupancy</p>
                            <ul class="cs-mock-chambers cs-z2">
                                <li><span>Chamber A</span><b style="--fill: 86%"></b><i data-temp="-18">-18.0°C</i></li>
                                <li><span>Chamber B</span><b style="--fill: 64%"></b><i data-temp="2">2.0°C</i></li>
                                <li><span>Chamber C</span><b style="--fill: 41%"></b><i data-temp="4">4.0°C</i></li>
                                <li><span>Chamber D</span><b style="--fill: 93%" class="is-full"></b><i data-temp="-22">-22.0°C</i></li>
                            </ul>
                            <div class="cs-mock-feed cs-z3" data-feed>
                                <p><span class="cs-tag cs-tag-in">IN</span> Goods receipt · 240 bags · Chamber A</p>
                                <p><span class="cs-tag cs-tag-out">OUT</span> Return · 60 bags · Chamber B</p>
                                <p><span class="cs-tag cs-tag-bill">BILL</span> Withdrawal billed · paid to bank</p>
                            </div>
                        </div>
                    </div>

                    {{-- Floating 3D cards --}}
                    <div class="cs-float cs-float-temp">
                        <svg class="cs-gauge" viewBox="0 0 44 44">
                            <circle cx="22" cy="22" r="18" class="cs-gauge-track"/>
                            <circle cx="22" cy="22" r="18" class="cs-gauge-fill"/>
                        </svg>
                        <div>
                            <small>Chamber A</small>
                            <strong data-temp="-18">-18.0°C</strong>
                            <em><i></i> Stable</em>
                        </div>
                    </div>

                    <div class="cs-float cs-float-pay">
                        <span class="cs-float-check">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>
                        </span>
                        <div>
                            <small>Payment received</small>
                            <strong>Rs 24,500 → Bank</strong>
                        </div>
                    </div>

                    <div class="cs-float cs-float-crates">
                        <div class="cs-crates">
                            @foreach ([[0, 0, 0], [1, 0, 0], [0, 1, 0], [1, 1, 0], [0, 0, 1], [1, 0, 1], [0, 1, 1]] as [$cx, $cy, $cz])
                                <div class="cs-cube" style="--cx: {{ $cx }}; --cy: {{ $cy }}; --cz: {{ $cz }}">
                                    <i class="cs-face-top"></i><i class="cs-face-front"></i><i class="cs-face-side"></i>
                                </div>
                            @endforeach
                        </div>
                        <div>
                            <small>Lot A-1042</small>
                            <strong>240 bags</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Highlights strip (overlaps hero) --}}
    <section class="cs-shell cs-highlights" aria-label="Highlights">
        <article>
            <span class="cs-ico">@include('landing.icon', ['name' => 'box'])</span>
            <div><h3>Per-lot tracking</h3><p>Packages, weight and chamber for every lot.</p></div>
        </article>
        <article>
            <span class="cs-ico">@include('landing.icon', ['name' => 'thermo'])</span>
            <div><h3>Chamber health</h3><p>Temperature readings, capacity and alerts.</p></div>
        </article>
        <article>
            <span class="cs-ico">@include('landing.icon', ['name' => 'receipt'])</span>
            <div><h3>Storage billing</h3><p>Rate cards, withdrawals and stock billing.</p></div>
        </article>
        <article>
            <span class="cs-ico">@include('landing.icon', ['name' => 'wallet'])</span>
            <div><h3>Cash or bank</h3><p>Every payment routed, balances live.</p></div>
        </article>
    </section>

    {{-- About --}}
    <section class="cs-section">
        <div class="cs-shell cs-about">
            <div class="cs-about-visual" data-reveal data-tilt3d>
                <div class="cs-about-card">
                    <p class="cs-mock-label">Customer overview · sample</p>
                    <h4>Malik Traders</h4>
                    <div class="cs-meter"><span>Given for storage</span><b style="--fill: 100%"></b><em>1,200</em></div>
                    <div class="cs-meter"><span>Returned</span><b style="--fill: 35%"></b><em>420</em></div>
                    <div class="cs-meter"><span>Still in store</span><b style="--fill: 65%" class="is-ice"></b><em>780</em></div>
                </div>
                <div class="cs-about-badge">
                    <strong>0</strong>
                    <span>shop sales or purchases created by storage movements</span>
                </div>
            </div>
            <div class="cs-about-copy" data-reveal>
                <p class="cs-eyebrow">About the product</p>
                <h2>Built for cold stores, not retail shops.</h2>
                <p>
                    Your customers keep their own goods with you — bags, crates, weight. The goods stay theirs until they
                    leave. Cold Storage CRM records every receipt, transfer, return and damage adjustment against the
                    customer's lots, so the stock you hold is never confused with stock you sell.
                </p>
                <ul class="cs-checks">
                    <li>Return partial or full lots — quantity can never exceed what's available</li>
                    <li>Transfer stock between chambers, racks and bins</li>
                    <li>Record damage or lost packages with a reason, so the ledger stays accurate</li>
                </ul>
                <a class="cs-btn cs-btn-dark" href="{{ route('how-it-works') }}">Read the full guide</a>
            </div>
        </div>
    </section>

    {{-- Modules --}}
    <section class="cs-section cs-section-tint" id="features">
        <div class="cs-shell">
            <div class="cs-head" data-reveal>
                <p class="cs-eyebrow">Modules</p>
                <h2>Everything the cold store desk needs, in one panel.</h2>
                <p>Six modules that cover the whole operating day, from the gate to the cash book.</p>
            </div>
            <div class="cs-cards">
                @foreach ([
                    ['box', 'Inbound', 'Goods receipts', 'Branch, customer, lot lines and chamber allocation, with a draft-to-posted workflow. Stock appears the moment you post.'],
                    ['truck', 'Outbound', 'Returns', 'Hand goods back from active lots in partial or full quantities, with built-in stock safety checks.'],
                    ['receipt', 'Billing', 'Storage billing', 'Rate cards per customer, Bill a withdrawal, Bill from stock, invoices, dues and collected payments.'],
                    ['wallet', 'Payments', 'Cash accounts', 'Route each payment to cash in hand or bank. Balances update on the dashboard straight away.'],
                    ['users', 'Customers', 'Customer overview', 'For each customer: packages given for storage, returned and still in store — with links to every list.'],
                    ['thermo', 'Monitoring', 'Chamber health', 'Temperature readings, action alerts, a capacity heatmap and optional reservations.'],
                ] as [$icon, $tag, $title, $copy])
                    <article class="cs-card" data-reveal data-tilt3d>
                        <span class="cs-ico cs-ico-lg">@include('landing.icon', ['name' => $icon])</span>
                        <p class="cs-card-tag">{{ $tag }}</p>
                        <h3>{{ $title }}</h3>
                        <p>{{ $copy }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why choose --}}
    <section class="cs-section cs-dark">
        <div class="cs-shell cs-why">
            @include('landing.cold-room', ['tag' => 'Chamber A', 'detail' => '-18.0°C · 86% full'])
            <div>
                <div data-reveal>
                    <p class="cs-eyebrow cs-eyebrow-light">Why Cold Storage CRM</p>
                    <h2>Where clear stock records meet simple billing.</h2>
                    <p class="cs-muted-light">
                        A normal shop POS treats everything on the shelf as yours. A cold store holds other people's goods
                        and charges rent for the space. Cold Storage CRM is built around that difference.
                    </p>
                </div>
                <div class="cs-why-list">
                    @foreach ([
                        ['shield', 'Customer-owned stock', 'Goods stay on the customer\'s account until they leave. Storage movements never touch your sales or purchase stock.'],
                        ['check', 'Stock you can trust', 'Returns can\'t exceed what\'s in store, and every damage adjustment needs a reason.'],
                        ['print', 'Print-ready documents', 'Customer ledgers, stock registers, rent registers and invoices — ready to print or hand over.'],
                    ] as [$icon, $title, $copy])
                        <article data-reveal>
                            <span class="cs-ico cs-ico-dark">@include('landing.icon', ['name' => $icon])</span>
                            <div><h3>{{ $title }}</h3><p>{{ $copy }}</p></div>
                        </article>
                    @endforeach
                </div>
                <a class="cs-btn cs-btn-primary cs-why-cta" href="{{ $demoUrl }}">Explore the demo</a>
            </div>
        </div>
    </section>

    {{-- Process --}}
    <section class="cs-section" id="process">
        <div class="cs-shell">
            <div class="cs-head cs-head-split" data-reveal>
                <div>
                    <p class="cs-eyebrow">The daily flow</p>
                    <h2>One operating loop, from the door to the bill.</h2>
                </div>
                <a class="cs-btn cs-btn-dark" href="{{ route('how-it-works') }}">Step-by-step guide</a>
            </div>
            <ol class="cs-steps">
                @foreach ([
                    ['Receive', 'Post inbound lots with product, packages, weight and chamber. Stock shows on the dashboard after posting.'],
                    ['Store', 'Track stock on hand by lot, chamber occupancy, transfers, temperature readings and alerts.'],
                    ['Return', 'Dispatch partial or full lots to the same customer. Available quantity is enforced.'],
                    ['Bill & collect', 'Bill a withdrawal after a return, or bill from stock still in store. Preview, post, record payment.'],
                ] as $i => [$title, $copy])
                    <li data-reveal data-tilt3d>
                        <span class="cs-step-num">{{ sprintf('%02d', $i + 1) }}</span>
                        <h3>{{ $title }}</h3>
                        <p>{{ $copy }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Setup --}}
    <section class="cs-section cs-section-tint" id="setup">
        <div class="cs-shell">
            <div class="cs-head" data-reveal>
                <p class="cs-eyebrow">Setup</p>
                <h2>Set up once, operate every day.</h2>
                <p>Describe your facility and your customers once. After that the daily work stays simple.</p>
            </div>
            <div class="cs-tiers">
                @foreach ([
                    ['Facility', 'Businesses & branches', ['Businesses and branches', 'One panel for every site'], '01'],
                    ['Chambers', 'Capacity & temperature', ['Capacity limits per chamber', 'Temperature limits per chamber'], '°C'],
                    ['Locations', 'Racks & bins', ['Optional racks and bins', 'Transfers between locations'], 'A-12'],
                    ['Customers', 'Products & rate cards', ['Goods owners and their products', 'Storage rates per customer'], 'Rs'],
                ] as [$tag, $title, $points, $badge])
                    <article class="cs-tier" data-reveal data-tilt3d>
                        <div class="cs-tier-top">
                            <p class="cs-card-tag">{{ $tag }}</p>
                            <span class="cs-tier-badge">{{ $badge }}</span>
                        </div>
                        <h3>{{ $title }}</h3>
                        <ul>
                            @foreach ($points as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Who it's for --}}
    <section class="cs-section">
        <div class="cs-shell">
            <div class="cs-head" data-reveal>
                <p class="cs-eyebrow">Who it's for</p>
                <h2>One panel for the whole team.</h2>
            </div>
            <div class="cs-roles">
                <article data-reveal data-tilt3d>
                    <span class="cs-ico cs-ico-lg">@include('landing.icon', ['name' => 'box'])</span>
                    <h3>Cold store teams</h3>
                    <p>Receive goods into a chamber or rack, keep stock on hand accurate, and return goods when the customer asks.</p>
                </article>
                <article data-reveal data-tilt3d>
                    <span class="cs-ico cs-ico-lg">@include('landing.icon', ['name' => 'receipt'])</span>
                    <h3>Billing &amp; cash desks</h3>
                    <p>Bill a withdrawal or bill from stock, preview the calculation, then collect into cash in hand or bank.</p>
                </article>
                <article data-reveal data-tilt3d>
                    <span class="cs-ico cs-ico-lg">@include('landing.icon', ['name' => 'chart'])</span>
                    <h3>Owners &amp; managers</h3>
                    <p>See stock in store, chamber occupancy, dues and cash balances on one dashboard, with alerts that need action.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="cs-section cs-section-tint" id="faq">
        <div class="cs-shell cs-faq">
            <div data-reveal>
                <p class="cs-eyebrow">FAQ</p>
                <h2>Straight answers before you start.</h2>
                <p class="cs-muted">Still unsure? Open the demo and click around — it's the quickest way to see it.</p>
                <a class="cs-btn cs-btn-primary" href="{{ $demoUrl }}">Try the live demo</a>
            </div>
            <div class="cs-faq-list">
                @foreach ([
                    ['Is this a normal shop POS?', 'No. Customers own the goods you store. Cold storage movements never create classic sales or purchase stock, so your shop figures stay separate.'],
                    ['Can a customer take back only part of a lot?', 'Yes. Returns can be partial or full. The system checks the available quantity, so you can never return more than is in store.'],
                    ['How is storage rent billed?', 'Two ways: Bill a withdrawal after goods are returned, or Bill from stock for goods still in store. Both use the customer\'s rate card, and you preview the bill before posting.'],
                    ['Where do payments go?', 'You choose cash in hand or bank for each payment. Balances on the dashboard update straight away.'],
                    ['Can I print ledgers and registers?', 'Yes — customer ledgers, the stock register, the rent register and invoices all have print-ready versions.'],
                    ['How does the demo work?', 'Click "Try the live demo" and you get your own temporary account with sample data. No sign-up needed; demo data resets daily.'],
                ] as $i => [$q, $a])
                    <details class="cs-faq-item" @if ($i === 0) open @endif>
                        <summary>{{ $q }}</summary>
                        <p>{{ $a }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="cs-shell cs-cta-wrap">
        <div class="cs-cta" data-reveal>
            <div>
                <h2>Ready to run your cold store from one panel?</h2>
                <p>Dashboard, goods receipts, returns, storage bills, cash in hand or bank, chamber occupancy and alerts.</p>
            </div>
            <div class="cs-actions">
                <a class="cs-btn cs-btn-light cs-btn-lg" href="{{ $demoUrl }}">Try the live demo</a>
                <a class="cs-btn cs-btn-outline-light cs-btn-lg" href="{{ route('how-it-works') }}">See how it works</a>
            </div>
        </div>
    </section>
</main>
@endsection
