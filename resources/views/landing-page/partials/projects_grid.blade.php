<div id="projects-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">
    @foreach($projects as $p)
        <div class="rounded-lg shadow p-4 flex flex-col items-center bg-base-100 border border-base-200">
            <a href="{{ $p['url'] }}">
                <img src="{{ $p['cover'] }}" alt="{{ $p['title'] }}" class="w-24 h-32 object-cover rounded mb-3">
            </a>
            <a href="{{ $p['url'] }}" class="font-semibold text-lg hover:underline text-center text-base-content">{{ $p['title'] }}</a>
            <div class="text-sm text-base-content/60 mt-1">Chapter {{ $p['latest_chapter'] ?? '-' }}</div>
            @if(!empty($p['chapters']) && count($p['chapters']))
                <ul class="mt-3 space-y-1 w-full text-sm">
                    @foreach($p['chapters'] as $ch)
                        <li class="w-full">
                            <a href="{{ $ch['url'] }}" class="block w-full text-left px-2 py-1 rounded hover:bg-base-200">Ch. {{ $ch['chapter_number'] }} - {{ 
                                \Illuminate\Support\Str::limit($ch['title'] ?? '-', 40) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endforeach
</div>
