<style>
    @php
        $hasSidebarTheme = filled($sidebarPrimary ?? null) && filled($sidebarSecondary ?? null);
    @endphp

    body.fi-panel-merchant,
    body.fi-panel-user {
        @foreach ($primary as $shade => $value)
            --primary-{{ $shade }}: {{ $value }};
            --fi-color-primary-{{ $shade }}: {{ $value }};
        @endforeach

        @foreach ($success as $shade => $value)
            --success-{{ $shade }}: {{ $value }};
            --fi-color-success-{{ $shade }}: {{ $value }};
        @endforeach

        @foreach ($warning as $shade => $value)
            --warning-{{ $shade }}: {{ $value }};
            --fi-color-warning-{{ $shade }}: {{ $value }};
        @endforeach

        @foreach ($danger as $shade => $value)
            --danger-{{ $shade }}: {{ $value }};
            --fi-color-danger-{{ $shade }}: {{ $value }};
        @endforeach

        @foreach ($secondary as $shade => $value)
            --secondary-{{ $shade }}: {{ $value }};
            --fi-color-secondary-{{ $shade }}: {{ $value }};
        @endforeach

        @foreach ($default as $shade => $value)
            --default-{{ $shade }}: {{ $value }};
            --fi-color-default-{{ $shade }}: {{ $value }};
        @endforeach

        @if ($hasSidebarTheme)
            --flowdesk-purple: {{ $primary[500] ?? $sidebarPrimary }};
            --flowdesk-accent-gradient: linear-gradient(135deg, {{ $primary[500] ?? $sidebarPrimary }} 0%, {{ $primary[600] ?? $sidebarPrimary }} 62%, {{ $primary[400] ?? ($sidebarSecondary ?? $sidebarPrimary) }} 100%);
            --flowdesk-sidebar-blue: {{ $primary[500] ?? $sidebarPrimary }};
            --flowdesk-sidebar-green: {{ $primary[600] ?? $sidebarPrimary }};
            --flowdesk-sidebar-teal: {{ $primary[400] ?? ($sidebarSecondary ?? $sidebarPrimary) }};
        @else
            --flowdesk-purple: {{ \App\Support\PanelBranding::get('colors.primary') }};
            --flowdesk-accent-gradient: linear-gradient(135deg, {{ \App\Support\PanelBranding::get('sidebar.gradient_start') }} 0%, {{ \App\Support\PanelBranding::get('sidebar.gradient_mid') }} 62%, {{ \App\Support\PanelBranding::get('sidebar.gradient_end') }} 100%);
            --flowdesk-sidebar-blue: {{ \App\Support\PanelBranding::get('sidebar.gradient_start') }};
            --flowdesk-sidebar-green: {{ \App\Support\PanelBranding::get('sidebar.gradient_mid') }};
            --flowdesk-sidebar-teal: {{ \App\Support\PanelBranding::get('sidebar.gradient_end') }};
        @endif
    }



    {{--/* LIGHT MODE */--}}
 {{--   body.fi-panel-merchant {--}}
 {{--       @foreach ($primaryLight as $shade => $value)--}}
 {{--              --fi-color-primary- {{ $shade }}: {{ $value }};--}}
 {{--       @endforeach--}}

 {{--       @foreach ($successLight as $shade => $value)--}}
 {{--              --fi-color-success- {{ $shade }}: {{ $value }};--}}
 {{--   @endforeach--}}

 {{--   }--}}

 {{--   /* DARK MODE */--}}
 {{--   html.dark body.fi-panel-merchant {--}}
 {{--       @foreach ($primaryDark as $shade => $value)--}}
 {{--            --fi-color-primary- {{ $shade }}: {{ $value }};--}}
 {{--       @endforeach--}}

 {{--       @foreach ($successDark as $shade => $value)--}}
 {{--            --fi-color-success- {{ $shade }}: {{ $value }};--}}
 {{--   @endforeach--}}

 {{--   }--}}
</style>
