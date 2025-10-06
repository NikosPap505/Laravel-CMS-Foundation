{{-- Flash Messages Component - Auto-displays as toast notifications --}}

@if (session('success'))
    <div data-flash-success="{{ session('success') }}" class="hidden"></div>
@endif

@if (session('error'))
    <div data-flash-error="{{ session('error') }}" class="hidden"></div>
@endif

@if (session('warning'))
    <div data-flash-warning="{{ session('warning') }}" class="hidden"></div>
@endif

@if (session('info'))
    <div data-flash-info="{{ session('info') }}" class="hidden"></div>
@endif

@if ($errors->any())
    <div data-flash-error="{{ $errors->first() }}" class="hidden"></div>
@endif
