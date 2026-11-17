{{-- 3D cold room (pure CSS) --}}
<div class="cs-room" data-reveal aria-hidden="true">
    <div class="cs-room-stage">
        <div class="cs-room-floor"></div>
        <div class="cs-room-wall cs-room-wall-back"></div>
        <div class="cs-room-wall cs-room-wall-left"></div>
        @php
            // [x, z, levels] for each stack of crates, on a 1-unit grid.
            $stacks = [[-3, -2, 3], [-2, -2, 2], [-1, -2, 3], [1, -2, 3], [2, -2, 1], [3, -2, 2], [-3, -1, 2], [-2, -1, 1], [1, -1, 2], [3, -1, 3], [-3, 1, 1], [-2, 1, 2], [2, 1, 1], [3, 1, 2]];
        @endphp
        @foreach ($stacks as [$x, $z, $levels])
            @for ($level = 0; $level < $levels; $level++)
                <div class="cs-crate" style="--x: {{ $x }}; --z: {{ $z }}; --y: {{ $level }}">
                    <i class="cs-face-top"></i><i class="cs-face-front"></i><i class="cs-face-side"></i>
                </div>
            @endfor
        @endforeach
        {{-- Crates that keep being loaded in and taken out --}}
        <div class="cs-crate cs-crate-in" style="--x: 2; --z: -2; --y: 1">
            <i class="cs-face-top"></i><i class="cs-face-front"></i><i class="cs-face-side"></i>
        </div>
        <div class="cs-crate cs-crate-out" style="--x: -2; --z: 1; --y: 2">
            <i class="cs-face-top"></i><i class="cs-face-front"></i><i class="cs-face-side"></i>
        </div>
        @for ($i = 0; $i < 16; $i++)
            <span class="cs-room-flake" style="--fx: {{ (($i * 53) % 70) / 10 - 3.5 }}; --fz: {{ (($i * 31) % 50) / 10 - 2.5 }}; --delay: {{ ($i % 8) * -0.55 }}s; --dur: {{ 3.2 + ($i % 4) * 0.6 }}s"></span>
        @endfor
        <div class="cs-room-tag">
            <strong>{{ $tag ?? 'Chamber A' }}</strong>
            <span>{{ $detail ?? '-18.0°C · 86% full' }}</span>
        </div>
    </div>
</div>
