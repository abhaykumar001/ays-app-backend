@props(['id', 'name', 'value' => ''])

<input
    type="hidden"
    name="{{ $name }}"
    id="{{ $id }}_input"
    value="{{ $value }}"
/>

<trix-toolbar
    class="[&_.trix-button]:bg-white [&_.trix-button.trix-active]:bg-gray-300"
    id="{{ $id }}_toolbar"
></trix-toolbar>

<trix-editor
    id="{{ $id }}"
    toolbar="{{ $id }}_toolbar"
    input="{{ $id }}_input"
    {{ $attributes->merge(['class' => 'trix-content border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-1 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light rounded-md shadow-sm dark:[&_pre]:!bg-gray-700 dark:[&_pre]:rounded dark:[&_pre]:!text-white']) }}
></trix-editor>
