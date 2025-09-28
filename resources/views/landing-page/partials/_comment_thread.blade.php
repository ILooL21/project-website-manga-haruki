{{-- resources/views/partials/_comment_thread.blade.php --}}

{{-- Hapus 'openReply' dari x-data lokal --}}
<div x-data="{ showReplies: false }" class="{{ $comment->parent_id ? 'mt-6 ml-6 md:ml-12 pl-4 border-l-2 border-base-300' : 'mt-6' }}">
    <div class="flex items-start gap-4 mb-4">
        {{-- Avatar Pengguna --}}
        <div class="avatar">
            <div class="w-12 h-12 rounded-full">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=random" alt="{{ $comment->user->name }}'s avatar" />
            </div>
        </div>

        <div class="w-full">
            {{-- Nama dan Waktu --}}
            <div class="flex items-center gap-x-3">
                <h3 class="text-base-content font-semibold">{{ $comment->user->name }}</h3>
                <p class="text-base-content/60 text-sm">{{ $comment->created_at->diffForHumans() }}</p>
            </div>

            {{-- Isi Komentar --}}
            <p class="text-base-content/80 mt-2">{{ $comment->body }}</p>

            {{-- Tombol Aksi --}}
            @auth
                <div class="mt-2">
                    {{-- 2. Ubah logika @click untuk mengubah state global 'openReplyId' --}}
                    <button @click="openReplyId = (openReplyId === {{ $comment->id }} ? null : {{ $comment->id }})" class="text-xs font-semibold text-primary hover:underline">Balas</button>
                </div>
            @endauth

            {{-- 3. Ubah kondisi x-show untuk memeriksa state global 'openReplyId' --}}
            <div x-show="openReplyId === {{ $comment->id }}" x-cloak x-transition class="mt-3">
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <textarea name="body" class="textarea textarea-bordered w-full mb-2" rows="2" placeholder="Tulis balasan untuk {{ $comment->user->name }}..." required></textarea>
                    <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <div class="flex justify-end gap-2">
                        {{-- 4. Ubah tombol Batal untuk mereset state global 'openReplyId' --}}
                        <button type="button" @click="openReplyId = null" class="btn btn-ghost btn-sm">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Kirim Balasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ... sisa kode untuk menampilkan/menyembunyikan dan perulangan balasan ... --}}
    @if($comment->replies->isNotEmpty())
        <div class="ml-16">
            <button @click="showReplies = !showReplies" class="flex items-center gap-2 text-sm font-semibold text-blue-500 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" :class="{'rotate-180': showReplies}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                <span x-show="!showReplies">Lihat {{ $comment->replies->count() }} balasan</span>
                <span x-show="showReplies" x-cloak>Sembunyikan balasan</span>
            </button>
        </div>
    @endif
    <div x-show="showReplies" x-cloak x-transition class="mt-4">
        @foreach($comment->replies as $reply)
            @include('landing-page.partials._comment_thread', ['comment' => $reply])
        @endforeach
    </div>
</div>
