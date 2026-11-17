@php
    use App\Support\DomainConfig;

    // The merchant panel lives on the app host when the domains are split.
    $appBase = DomainConfig::splitEnabled() ? DomainConfig::appUrl() : url('');
    $demoUrl = $appBase.'/demo/login';

    $steps = [
        ['box', 'Take goods in', 'When a customer brings goods, record who they are, what they brought, how many bags or how much weight, and which cold room it goes into. Save it, then post it.', 'Those goods now show as "in store" for that customer.'],
        ['truck', 'Keep and move goods', 'See what is still in each room. If you move bags from one room or rack to another, record the transfer. The customer still owns the same amount — only the place changes.', 'You always know where each customer\'s goods are sitting.'],
        ['thermo', 'Check temperature', 'Create a temperature reading. If it is outside the chamber\'s min/max, it appears on Action alerts. Acknowledge it after checking.', 'Out-of-range readings show up as action alerts.'],
        ['users', 'Hand goods back', 'When the customer wants their goods, pick what is still in store for them and how much to give back. You cannot give more than they still have.', 'Their "in store" amount goes down. This is not a shop sale.'],
        ['shield', 'Note damage or loss', 'If bags tear or goods are lost, record it with a short reason and how many were affected.', 'What the screen shows matches what is really in the room.'],
        ['receipt', 'Make a storage bill', 'After a return, use Storage bills → Bill a withdrawal (or Bill this withdrawal on the return). For stock still in store, use Bill from stock, preview, then post.', 'Rent is billed for the withdrawal or for the remaining stock.'],
        ['wallet', 'Take payment', 'Bill a withdrawal collects into cash in hand or bank when you create it. For other bills, record a payment against the due amount.', 'The bill shows what was collected, and dashboard cash balances update.'],
        ['chart', 'See one customer clearly', 'Open a customer to see how much they gave you, how much they took back and how much is still with you — with links to every related record.', 'You can explain a customer\'s position from one screen.'],
    ];
@endphp

@extends('landing.layout')

@section('title', 'How it works · Cold Storage CRM')
@section('description', 'A simple guide to Cold Storage CRM: take goods in, keep them safe, hand them back, and collect storage rent into cash in hand or bank.')

@section('content')
<main>
    {{-- Page hero --}}
    <section class="cs-hero cs-hero-page">
        <div class="cs-hero-frost" aria-hidden="true"></div>
        <canvas class="cs-snow" data-snow aria-hidden="true"></canvas>
        <div class="cs-shell cs-hero-grid">
            <div class="cs-hero-copy" data-hero-in>
                <nav class="cs-crumbs" aria-label="Breadcrumb">
                    <a href="{{ route('landing') }}">Home</a>
                    <span aria-hidden="true">/</span>
                    <span aria-current="page">How it works</span>
                </nav>
                <h1>How Cold Storage CRM <em>works</em>.</h1>
                <p class="cs-lead">
                    Customers keep their own goods with you. You receive them into a chamber, track where they sit,
                    return them when asked, and charge storage rent — without ever treating those goods as shop stock.
                </p>
                <div class="cs-actions">
                    <a class="cs-btn cs-btn-primary cs-btn-lg" href="{{ $demoUrl }}">Try the live demo</a>
                    <a class="cs-btn cs-btn-ghost cs-btn-lg" href="#story">Read the steps</a>
                </div>
            </div>

            {{-- 3D rotating ring of the daily loop --}}
            <div class="cs-loop" aria-hidden="true">
                <div class="cs-loop-core">
                    <img src="{{ asset('images/cold-storage-crm-icon.svg') }}" alt="">
                </div>
                <div class="cs-loop-orbit"></div>
                <div class="cs-loop-ring">
                    @foreach ([['box', 'Receive'], ['thermo', 'Store'], ['truck', 'Return'], ['receipt', 'Bill'], ['wallet', 'Collect']] as $i => [$icon, $label])
                        <div class="cs-loop-item" style="--i: {{ $i }}">
                            <span class="cs-ico">@include('landing.icon', ['name' => $icon])</span>
                            <div><small>Step {{ $i + 1 }}</small><strong>{{ $label }}</strong></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Overview --}}
    <section class="cs-section cs-section-tint cs-section-after-hero">
        <div class="cs-shell">
            <div class="cs-head" data-reveal>
                <p class="cs-eyebrow">Overview</p>
                <h2>What Cold Storage CRM helps you do.</h2>
                <p>Your customers own the goods. Your team runs the cold rooms and the rent from one place.</p>
            </div>
            <div class="cs-cards">
                @foreach ([
                    ['shield', 'The basics', 'Their goods, not yours', 'Goods stay customer-owned until they leave. This is storage — not buying and selling shop stock.'],
                    ['chart', 'The routine', 'The daily loop', 'Receive → track → return → Bill a withdrawal or Bill from stock → collect into cash in hand or bank.'],
                    ['thermo', 'Monitoring', 'Watch the cold rooms', 'See how full each room is, check temperatures, and get warned when something looks wrong.'],
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

    {{-- Setup --}}
    <section class="cs-section">
        <div class="cs-shell">
            <div class="cs-head" data-reveal>
                <p class="cs-eyebrow">Before you start</p>
                <h2>Set things up once.</h2>
                <p>Do this before the first customer drops off goods. After that, the daily work stays easy.</p>
            </div>
            <div class="cs-tiers cs-tiers-3">
                @foreach ([
                    ['Step A', 'Your place', 'Add your business, branch and cold rooms. Set how much each room can hold and the temperature it should stay at. Add racks if you use them.', '01'],
                    ['Step B', 'Your customers', 'Add the people or businesses who leave goods with you.', '02'],
                    ['Step C', 'Goods & prices', 'Add what you store (like potatoes or apples) and each customer\'s storage price — by bag, by weight or by days.', '03'],
                ] as [$tag, $title, $copy, $badge])
                    <article class="cs-tier" data-reveal data-tilt3d>
                        <div class="cs-tier-top">
                            <p class="cs-card-tag">{{ $tag }}</p>
                            <span class="cs-tier-badge">{{ $badge }}</span>
                        </div>
                        <h3>{{ $title }}</h3>
                        <p class="cs-tier-copy">{{ $copy }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Full story: timeline --}}
    <section class="cs-section cs-section-tint" id="story">
        <div class="cs-shell">
            <div class="cs-head" data-reveal>
                <p class="cs-eyebrow">Full story</p>
                <h2>From the door to payment.</h2>
                <p>Follow these steps in order. Each one leads to the next.</p>
            </div>
            <ol class="cs-timeline" data-timeline>
                <span class="cs-timeline-line" aria-hidden="true"><i></i></span>
                @foreach ($steps as $i => [$icon, $title, $copy, $result])
                    <li class="cs-timeline-item" data-reveal>
                        <span class="cs-timeline-dot">{{ sprintf('%02d', $i + 1) }}</span>
                        <article class="cs-timeline-card" data-tilt3d>
                            <div class="cs-timeline-head">
                                <span class="cs-ico">@include('landing.icon', ['name' => $icon])</span>
                                <h3>{{ $title }}</h3>
                            </div>
                            <p>{{ $copy }}</p>
                            <p class="cs-timeline-result"><strong>What happens:</strong> {{ $result }}</p>
                        </article>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Extra modules --}}
    <section class="cs-section">
        <div class="cs-shell">
            <div class="cs-head" data-reveal>
                <p class="cs-eyebrow">Extra modules</p>
                <h2>Other tools in the panel.</h2>
                <p>Use these when you need more detail on chambers or money.</p>
            </div>
            <div class="cs-roles">
                @foreach ([
                    ['chart', 'Capacity heatmap', 'See how full each chamber is before you take new goods in.'],
                    ['receipt', 'Reservations', 'Book space before goods arrive. Confirm, fulfil or cancel.'],
                    ['wallet', 'Cash and bank totals', 'The dashboard shows cash in hand and bank balances from collected payments.'],
                ] as [$icon, $title, $copy])
                    <article data-reveal data-tilt3d>
                        <span class="cs-ico cs-ico-lg">@include('landing.icon', ['name' => $icon])</span>
                        <h3>{{ $title }}</h3>
                        <p>{{ $copy }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Clarify (dark, with the 3D cold room) --}}
    <section class="cs-section cs-dark">
        <div class="cs-shell cs-why">
            @include('landing.cold-room', ['tag' => 'Customer goods', 'detail' => 'Owned by them · billed as rent'])
            <div data-reveal>
                <p class="cs-eyebrow cs-eyebrow-light">Important</p>
                <h2>Is this my shop stock?</h2>
                <p class="cs-no"><span>No.</span> Cold storage movements never create classic sales or purchase stock.</p>
                <p class="cs-muted-light">
                    The goods stay with the customer until they take them back. That's why Cold Storage CRM is built for
                    cold stores — not for a normal shop counter.
                </p>
                <a class="cs-btn cs-btn-primary" href="{{ $demoUrl }}">See it in the demo</a>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="cs-shell cs-cta-wrap cs-cta-wrap-gap">
        <div class="cs-cta" data-reveal>
            <div>
                <h2>Ready to try it with sample data?</h2>
                <p>Open the demo and walk through receiving, returning and billing in a few minutes.</p>
            </div>
            <div class="cs-actions">
                <a class="cs-btn cs-btn-light cs-btn-lg" href="{{ $demoUrl }}">Try the live demo</a>
                <a class="cs-btn cs-btn-outline-light cs-btn-lg" href="{{ route('landing') }}">Back to home</a>
            </div>
        </div>
    </section>
</main>
@endsection
