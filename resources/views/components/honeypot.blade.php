@props([
    'name' => 'extra_field_protection',
])

<div style="display:none !important; visibility:hidden !important; position:absolute !important; left:-9999px !important;" aria-hidden="true">
    <label for="{{ $name }}">Please leave this field blank</label>
    <input
        type="text"
        id="{{ $name }}"
        name="{{ $name }}"
        tabindex="-1"
        autocomplete="off"
        {{ $attributes }}
    />
</div>
