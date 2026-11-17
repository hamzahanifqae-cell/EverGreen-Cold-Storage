@php
    $steps = [
        ['box', __('Take goods in'), __('When a customer brings goods, create a goods receipt: the customer, the product, the lot number, how many bags and the net weight, and which chamber, rack or bin it goes into. Save it as a draft, then post it.'), __('Those goods now show as "in store" for that customer.')],
        ['truck', __('Keep and move goods'), __('See what is still in each room. If you move bags from one room or rack to another, record the transfer. The customer still owns the same amount — only the place changes.'), __('You always know where each customer\'s goods are sitting.')],
        ['chart', __('Watch space and alerts'), __('Check the Capacity heatmap before taking new goods in, and Lot ageing for goods that have sat too long. Action alerts list unpaid bills and reservations arriving this week.'), __('Nothing slips: full chambers, old lots and unpaid bills all show up.')],
        ['users', __('Hand goods back'), __('When the customer wants their goods, pick what is still in store for them and how much to give back. You cannot give more than they still have.'), __('Their "in store" amount goes down. This is not a shop sale.')],
        ['shield', __('Note damage or loss'), __('If bags tear or goods are lost, record it with a short reason and how many were affected.'), __('What the screen shows matches what is really in the room.')],
        ['receipt', __('Make a storage bill'), __('After a return, use Storage bills → Bill a withdrawal (or Bill this withdrawal on the return). For stock still in store, use Bill from stock, preview, then post. The customer\'s storage rate decides the price.'), __('Rent is billed once per day of storage — a day already billed is never charged again.')],
        ['wallet', __('Take payment'), __('Bill a withdrawal collects into cash in hand or bank when you create it. For other bills, record a payment against the due amount.'), __('The bill shows what was collected, and dashboard cash balances update.')],
        ['chart', __('See one customer clearly'), __('Open a customer to see how much they gave you, how much they took back and how much is still with you — with links to every related record.'), __('You can explain a customer\'s position from one screen.')],
    ];
@endphp

@extends('landing.layout')

@section('title', __('How it works · EverGreen Cold Storage'))
@section('description', __('A simple guide to EverGreen Cold Storage: take goods in, keep them safe, hand them back, and collect storage rent into cash in hand or bank.'))

@section('content')
<main>
    {{-- Page hero --}}
    <section class="cs-hero cs-hero-page">
        <div class="cs-hero-frost" aria-hidden="true"></div>
        <canvas class="cs-snow" data-snow aria-hidden="true"></canvas>
        <div class="cs-shell cs-hero-grid">
            <div class="cs-hero-copy" data-hero-in>
                <nav class="cs-crumbs" aria-label="{{ __('Breadcrumb') }}">
                    <a href="{{ route('landing') }}">{{ __('Home') }}</a>
                    <span aria-hidden="true">/</span>
                    <span aria-current="page">{{ __('How it works') }}</span>
                </nav>
                <h1>{!! __('How EverGreen Cold Storage :em.', ['em' => '<em>'.e(__('works')).'</em>']) !!}</h1>
                <p class="cs-lead">
                    {{ __('Customers keep their own goods with you. You receive them into a chamber, track where they sit, return them when asked, and charge storage rent — without ever treating those goods as shop stock.') }}
                </p>
                <div class="cs-actions">
                    <a class="cs-btn cs-btn-primary cs-btn-lg" href="#story">{{ __('Read the steps') }}</a>
                    <a class="cs-btn cs-btn-ghost cs-btn-lg" href="{{ route('landing') }}#features">{{ __('See all modules') }}</a>
                </div>
            </div>

            {{-- 3D rotating ring of the daily loop --}}
            <div class="cs-loop" aria-hidden="true">
                <div class="cs-loop-core">
                    <img src="{{ asset('images/evergreen-favicon.svg') }}" alt="">
                </div>
                <div class="cs-loop-orbit"></div>
                <div class="cs-loop-ring">
                    @foreach ([['box', __('Receive')], ['chart', __('Store')], ['truck', __('Return')], ['receipt', __('Bill')], ['wallet', __('Collect')]] as $i => [$icon, $label])
                        <div class="cs-loop-item" style="--i: {{ $i }}">
                            <span class="cs-ico">@include('landing.icon', ['name' => $icon])</span>
                            <div><small>{{ __('Step :n', ['n' => $i + 1]) }}</small><strong>{{ $label }}</strong></div>
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
                <p class="cs-eyebrow">{{ __('Overview') }}</p>
                <h2>{{ __('What EverGreen Cold Storage helps you do.') }}</h2>
                <p>{{ __('Your customers own the goods. Your team runs the cold rooms and the rent from one place.') }}</p>
            </div>
            <div class="cs-cards">
                @foreach ([
                    ['shield', __('The basics'), __('Their goods, not yours'), __('Goods stay customer-owned until they leave. This is storage — not buying and selling shop stock.')],
                    ['chart', __('The routine'), __('The daily loop'), __('Receive → track → return → Bill a withdrawal or Bill from stock → collect into cash in hand or bank.')],
                    ['chart', __('Monitoring'), __('Watch the cold rooms'), __('See how full each room is, how long each lot has been in store, and which bills or bookings need follow-up.')],
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
                <p class="cs-eyebrow">{{ __('Before you start') }}</p>
                <h2>{{ __('Set things up once.') }}</h2>
                <p>{{ __('Do this before the first customer drops off goods. After that, the daily work stays easy.') }}</p>
            </div>
            <div class="cs-tiers cs-tiers-3">
                @foreach ([
                    [__('Step A'), __('Your place'), __('Add your business, branch and cold rooms. Set how much each room can hold — in bags, pallets, kilograms or tonnes. Add racks and bins if you use them.'), '01'],
                    [__('Step B'), __('Your customers'), __('Add the people or businesses who leave goods with you.'), '02'],
                    [__('Step C'), __('Goods & prices'), __('Add what you store (like potatoes or apples) and each customer\'s storage rate — per bag, kilogram, tonne or pallet, charged daily, monthly or by season.'), '03'],
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
                <p class="cs-eyebrow">{{ __('Full story') }}</p>
                <h2>{{ __('From the door to payment.') }}</h2>
                <p>{{ __('Follow these steps in order. Each one leads to the next.') }}</p>
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
                            <p class="cs-timeline-result"><strong>{{ __('What happens:') }}</strong> {{ $result }}</p>
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
                <p class="cs-eyebrow">{{ __('Extra modules') }}</p>
                <h2>{{ __('Other tools in the panel.') }}</h2>
                <p>{{ __('Use these when you need more detail on chambers, stock or money.') }}</p>
            </div>
            <div class="cs-roles">
                @foreach ([
                    ['chart', __('Capacity heatmap'), __('See how full each chamber is before you take new goods in.')],
                    ['receipt', __('Reservations'), __('Book space before goods arrive. Confirm, fulfil or cancel.')],
                    ['wallet', __('Cash and bank totals'), __('The dashboard shows cash in hand and bank balances from collected payments.')],
                    ['receipt', __('Stock register'), __('Every lot still in store, by customer and chamber, with packages and net weight.')],
                    ['print', __('Rent collection register'), __('Who was billed, how much they paid and what is still due — ready to print.')],
                    ['box', __('Lot ageing'), __('How many days each lot has been in store, so slow-moving goods stand out.')],
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
            @include('landing.cold-room', ['tag' => __('Customer goods'), 'detail' => __('Owned by them · billed as rent')])
            <div data-reveal>
                <p class="cs-eyebrow cs-eyebrow-light">{{ __('Important') }}</p>
                <h2>{{ __('Is this my shop stock?') }}</h2>
                <p class="cs-no"><span>{{ __('No.') }}</span> {{ __('Cold storage movements never create classic sales or purchase stock.') }}</p>
                <p class="cs-muted-light">
                    {{ __('The goods stay with the customer until they take them back. That\'s why EverGreen Cold Storage is built for cold stores — not for a normal shop counter.') }}
                </p>
                <a class="cs-btn cs-btn-primary" href="{{ route('landing') }}#features">{{ __('See all modules') }}</a>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="cs-shell cs-cta-wrap cs-cta-wrap-gap">
        <div class="cs-cta" data-reveal>
            <div>
                <h2>{{ __('Ready to run your cold store this way?') }}</h2>
                <p>{{ __('Receiving, returning, billing and collecting — all in one EverGreen panel.') }}</p>
            </div>
            <div class="cs-actions">
                <a class="cs-btn cs-btn-light cs-btn-lg" href="{{ route('landing') }}#features">{{ __('See all modules') }}</a>
                <a class="cs-btn cs-btn-outline-light cs-btn-lg" href="{{ route('landing') }}">{{ __('Back to home') }}</a>
            </div>
        </div>
    </section>
</main>
@endsection
