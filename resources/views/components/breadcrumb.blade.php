<div class="text-gray-400">
    <a href="{{ route('home') }}">Home</a> @foreach ($lists as $list) / <a href="{{ $list['route'] ?? '#' }}" class="@if($list['is_active']) font-bold text-white @endif">{{ $list['title'] ?? '' }}</a>@endforeach
</div>
