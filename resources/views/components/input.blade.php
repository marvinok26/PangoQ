{{-- resources/views/components/input.blade.php --}}
@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-secondary-500 focus:ring focus:ring-secondary-200 focus:ring-opacity-50']) !!}>