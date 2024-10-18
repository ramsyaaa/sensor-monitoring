<div class="relative flex flex-col gap-2 mb-5">
    {{-- <label class="font-bold text-[16px]" for="{{ $id }}">{{ $label }}</label> --}}
    <input type="{{ $type ?? 'text' }}" id="{{ $id }}" name="{{ $name }}" value="{{ isset($value) ? old($name, $value[$name]) : old($name) }}" class="w-full border rounded-lg h-[40px] px-4" placeholder="{{ $placeholder ?? 'Masukkan data' }}">
    @error($name)
        <div class="absolute -bottom-5 left-2 text-[12px] text-red-500">{{ $message }}</div>
    @enderror
</div>
