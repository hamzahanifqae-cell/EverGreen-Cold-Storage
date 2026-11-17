@extends('landing.layout')

@section('title', __('EverGreen Cold Storage — Your goods, safe with us'))
@section('description', __('Store your goods at EverGreen Cold Storage. Every bag recorded, a printed receipt for every delivery, take back part or all any time, and fair rent per bag, kg, tonne or pallet.'))

@section('content')
<main>
    {{-- Hero --}}
    <section class="cs-hero">
        <div class="cs-hero-frost" aria-hidden="true"></div>
        <canvas class="cs-snow" data-snow aria-hidden="true"></canvas>
        <div class="cs-shell cs-hero-grid">
            <div class="cs-hero-copy" data-hero-in>
                <p class="cs-pill"><span class="cs-dot"></span> {{ __('Trusted cold storage for your goods') }}</p>
                <h1>{!! __('Your goods, :em.', ['em' => '<em>'.e(__('safe with us')).'</em>']) !!}</h1>
                <p class="cs-lead">
                    {{ __('We record every bag you bring, keep it safe in our cold rooms and give it back whenever you ask.') }}
                </p>
                <div class="cs-actions">
                    <a class="cs-btn cs-btn-primary cs-btn-lg" href="{{ route('how-it-works') }}">{{ __('See how it works') }}</a>
                    <a class="cs-btn cs-btn-ghost cs-btn-lg" href="#features">{{ __('Our services') }}</a>
                </div>
                <ul class="cs-hero-points">
                    <li>{{ __('Printed receipt for every delivery') }}</li>
                    <li>{{ __('Pay per bag, kg, tonne or pallet') }}</li>
                    <li>{{ __('Take back part or all, any time') }}</li>
                </ul>
            </div>

            {{-- 3D facility scene (illustrative) --}}
            <div class="cs-scene" data-scene aria-hidden="true">
                <div class="cs-scene-rig" data-rig>
                    <div class="cs-scene-glow"></div>
                    <div class="cs-facility">
                        @include('landing.facility')
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Highlights strip (overlaps hero) --}}
    <section class="cs-shell cs-highlights" aria-label="{{ __('Highlights') }}">
        <article>
            <span class="cs-ico">@include('landing.icon', ['name' => 'box'])</span>
            <div><h3>{{ __('Every bag recorded') }}</h3><p>{{ __('Bags and weight noted on arrival.') }}</p></div>
        </article>
        <article>
            <span class="cs-ico">@include('landing.icon', ['name' => 'shield'])</span>
            <div><h3>{{ __('Safe cold rooms') }}</h3><p>{{ __('We know which room holds your goods.') }}</p></div>
        </article>
        <article>
            <span class="cs-ico">@include('landing.icon', ['name' => 'receipt'])</span>
            <div><h3>{{ __('Fair rent') }}</h3><p>{{ __('No day is ever charged twice.') }}</p></div>
        </article>
        <article>
            <span class="cs-ico">@include('landing.icon', ['name' => 'wallet'])</span>
            <div><h3>{{ __('Cash or bank') }}</h3><p>{{ __('Pay the way that suits you.') }}</p></div>
        </article>
    </section>

    {{-- Services --}}
    <section class="cs-section cs-section-tint" id="features">
        <div class="cs-shell">
            <div class="cs-head" data-reveal>
                <p class="cs-eyebrow">{{ __('Our services') }}</p>
                <h2>{{ __('Everything you need to store your goods.') }}</h2>
            </div>
            @php
                $services = [
                    ['box', __('Delivery'), __('Printed receipt'), __('Get a receipt with your lot number, bags and weight for every delivery.')],
                    ['shield', __('Storage'), __('Safe cold rooms'), __('Your goods are kept in our cold rooms, recorded by room and rack.')],
                    ['truck', __('Collection'), __('Take back any time'), __('Take back part or all of your goods whenever you need them.')],
                    ['receipt', __('Rent'), __('Fair rent'), __('Pay per bag, kg, tonne or pallet — daily, monthly or by season.')],
                    ['wallet', __('Payment'), __('Cash or bank'), __('Pay in cash or by bank. Every payment shows on your bill.')],
                    ['print', __('Records'), __('Your statement'), __('A printed statement of what you stored, took back and still have with us.')],
                    ['users', __('Booking'), __('Book space ahead'), __('Reserve room space before your goods arrive.')],
                ];
            @endphp
        </div>
        {{-- Auto-scrolling carousel: the list is rendered twice so the loop is seamless --}}
        <div class="cs-carousel" data-reveal aria-label="{{ __('Our services') }}">
            <div class="cs-carousel-track">
                @foreach ([false, true] as $isCopy)
                    @foreach ($services as [$icon, $tag, $title, $copy])
                        <article class="cs-card" data-tilt3d @if ($isCopy) aria-hidden="true" @endif>
                            <span class="cs-ico cs-ico-lg">@include('landing.icon', ['name' => $icon])</span>
                            <p class="cs-card-tag">{{ $tag }}</p>
                            <h3>{{ $title }}</h3>
                            <p>{{ $copy }}</p>
                        </article>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why choose --}}
    <section class="cs-section cs-dark">
        <div class="cs-shell cs-why">
            @include('landing.cold-room', ['tag' => __('Chamber A'), 'detail' => __('86% full · 1,240 bags')])
            <div>
                <div data-reveal>
                    <p class="cs-eyebrow cs-eyebrow-light">{{ __('Why EverGreen') }}</p>
                    <h2>{{ __('Your goods stay yours.') }}</h2>
                    <p class="cs-muted-light">{{ __('We only store them. Nothing is sold or used — you get back exactly what you left with us.') }}</p>
                </div>
                <div class="cs-why-list">
                    @foreach ([
                        ['check', __('Accurate records'), __('Every bag in and out is recorded, so your stock is always right.')],
                        ['receipt', __('Clear bills'), __('See exactly what you are paying for. No day is charged twice.')],
                        ['print', __('Paperwork on hand'), __('Printed receipts, bills and statements whenever you need them.')],
                    ] as [$icon, $title, $copy])
                        <article data-reveal>
                            <span class="cs-ico cs-ico-dark">@include('landing.icon', ['name' => $icon])</span>
                            <div><h3>{{ $title }}</h3><p>{{ $copy }}</p></div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Process --}}
    <section class="cs-section" id="process">
        <div class="cs-shell">
            <div class="cs-head" data-reveal>
                <p class="cs-eyebrow">{{ __('How it works') }}</p>
                <h2>{{ __('Four simple steps.') }}</h2>
            </div>
            <ol class="cs-steps">
                @foreach ([
                    [__('Bring your goods'), __('We count them and give you a printed receipt.')],
                    [__('We store them'), __('Your goods are kept safe in our cold rooms.')],
                    [__('Take them back'), __('Collect part or all, whenever you need.')],
                    [__('Pay your rent'), __('Get a clear bill and pay in cash or by bank.')],
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

    {{-- FAQ --}}
    <section class="cs-section cs-section-tint" id="faq">
        <div class="cs-shell cs-faq">
            <div data-reveal>
                <p class="cs-eyebrow">{{ __('FAQ') }}</p>
                <h2>{{ __('Common questions.') }}</h2>
                <p class="cs-muted">{{ __('Quick answers before you store with us.') }}</p>
                <a class="cs-btn cs-btn-primary" href="{{ route('how-it-works') }}">{{ __('See how it works') }}</a>
            </div>
            <div class="cs-faq-list">
                @foreach ([
                    [__('Will I get a receipt?'), __('Yes. You get a printed receipt for every delivery, showing your lot number, bags and weight.')],
                    [__('Can I take back only part of my goods?'), __('Yes. Take back part or all of your goods whenever you need them.')],
                    [__('How is rent charged?'), __('Per bag, kg, tonne or pallet — daily, monthly or by season. No day is charged twice.')],
                    [__('How can I pay?'), __('In cash or by bank. Every payment is shown on your bill.')],
                    [__('Can I book space in advance?'), __('Yes. You can reserve room space before your goods arrive.')],
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
                <h2>{{ __('Store your goods with EverGreen.') }}</h2>
                <p>{{ __('Safe cold rooms, clear records and fair rent.') }}</p>
            </div>
            <div class="cs-actions">
                <a class="cs-btn cs-btn-light cs-btn-lg" href="{{ route('how-it-works') }}">{{ __('See how it works') }}</a>
                <a class="cs-btn cs-btn-outline-light cs-btn-lg" href="#features">{{ __('Our services') }}</a>
            </div>
        </div>
    </section>
</main>
@endsection
