<div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
</div>@props([
    'label' => '',
    'name',
    'options' => [],
    'value' => null,
    'placeholder' => 'Select an option',
    'select2' => false,
])


    <select 
        id="{{ $name }}" 
        name="{{ $name }}{{ $attributes->get('multiple') ? '[]' : '' }}" 
        class="border-r-8 border-transparent text-sm  w-full mt-1 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary-light dark:focus:ring-primary-light px-4 py-2 rounded-md shadow-sm {{ $select2 ? 'select2' : '' }}"
        {{ $attributes }}
    >
        @if(!$attributes->get('multiple'))
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ ($value == $optionValue || (is_array($value) && in_array($optionValue, $value))) ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

@if($select2)
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#{{ $name }}').select2({
                width: '100%',
                placeholder: '{{ $placeholder }}',
                allowClear: true
            });
        });
    </script>
    @endpush
@endif
