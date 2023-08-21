@php
    $setting = \App\Models\Utility::settings();
@endphp
@if ($setting['cookie_consent'] == 'on')
    @include('layouts.cookie_consent')
@endif
<footer class="dash-footer">
    <div class="footer-wrapper">
        <div class="py-1">
            <span class="text-muted">  {{(Utility::getValByName('footer_text')) ? Utility::getValByName('footer_text') :  __('Copyright RotaGo') }} {{ date('Y') }}</span>
        </div>
    </div>
</footer>
