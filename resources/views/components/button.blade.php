{{-- resources/views/components/button.blade.php --}}
@props(['type' => 'submit'])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-secondary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-secondary-700 active:bg-secondary-900 focus:outline-none focus:border-secondary-900 focus:ring ring-secondary-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>