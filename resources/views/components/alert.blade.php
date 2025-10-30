@props(['type' => 'success', 'id' => null, 'autoHide' => false, 'hideDelay' => 5000])

@php
    $id = $id ?? 'alert-' . uniqid();
    $autoHideClass = $autoHide ? 'auto-hide-alert' : '';
@endphp

@if($type === 'success')
    <div class="alert alert-success {{ $autoHideClass }}" id="{{ $id }}">
        {{ $slot }}
    </div>
@elseif($type === 'danger')
    <div class="alert alert-danger {{ $autoHideClass }}" id="{{ $id }}">
        {{ $slot }}
    </div>
@elseif($type === 'warning')
    <div class="alert alert-warning {{ $autoHideClass }}" id="{{ $id }}">
        {{ $slot }}
    </div>
@elseif($type === 'info')
    <div class="alert alert-info {{ $autoHideClass }}" id="{{ $id }}">
        {{ $slot }}
    </div>
@endif

@if($autoHide)
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('{{ $id }}');
        if (alert) {
            setTimeout(function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, {{ $hideDelay }});
        }
    });
    </script>
@endif

