@props(['disabled' => false, 'type' => 'text'])

@if ($type === 'file')
       <input type="file"
        @disabled($disabled)
        {{ $attributes->merge([
            'class' => 'block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file:mr-4 file:py-2 file:ml-0 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-light file:text-primary hover:file:bg-primary-light',
        ]) }}
    >
@elseif ($type === 'tel')
    <input 
        type="tel" 
        pattern="[0-9+\s]*" 
        inputmode="tel"
        oninput="this.value = this.value.replace(/[^0-9+\s]/g, '')"
        @disabled($disabled)
        {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm']) }}>
@else
    <input 
        type="{{ $type }}" 
        @disabled($disabled)
        {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm']) }}>
@endif
