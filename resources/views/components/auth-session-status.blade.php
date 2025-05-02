{{-- resources/views/components/auth-session-status.blade.php --}}
@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
        {{ $status }}
    </div>
@endif

{{-- resources/views/components/auth-validation-errors.blade.php --}}
@props(['errors'])

@if ($errors->any())
    <div {{ $attributes }}>
        <div class="font-medium text-red-600">
            {{ __('Whoops! Something went wrong.') }}
        </div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- resources/views/components/application-logo.blade.php --}}
<div class="flex items-center">
    <div class="h-10 w-10 bg-secondary-600 rounded-full"></div>
    <span class="ml-2 text-xl font-bold text-secondary-600">pangoQ</span>
</div>

{{-- resources/views/components/label.blade.php --}}
@props(['value', 'for'])

<label for="{{ $for }}" {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>

{{-- resources/views/components/input.blade.php --}}
@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-secondary-500 focus:ring focus:ring-secondary-200 focus:ring-opacity-50']) !!}>

{{-- resources/views/components/button.blade.php --}}
@props(['type' => 'submit'])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-secondary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-secondary-700 active:bg-secondary-900 focus:outline-none focus:border-secondary-900 focus:ring ring-secondary-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>