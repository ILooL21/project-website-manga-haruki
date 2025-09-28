<div class="mt-12" x-data="{ openReplyId: null }">

    {{-- FORM KOMENTAR UTAMA --}}
    <div x-show="openReplyId === null" x-transition>
        @auth
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <div class="bg-base-100 p-4 rounded-lg shadow-md">
                    <textarea name="body" class="textarea textarea-bordered w-full mb-4" placeholder="Tulis komentar..." required></textarea>
                    <button type="submit" class="btn btn-primary w-full">Kirim</button>
                </div>
                <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
            </form>
        @else
            <div class="bg-base-100 p-4 rounded-lg shadow-md text-center">
                <p>Silakan <a href="#" onclick="login_modal.showModal()" class="link link-primary font-semibold">login</a> untuk meninggalkan komentar.</p>
            </div>
        @endauth
    </div>

    {{-- DAFTAR KOMENTAR --}}
    <h2 class="text-2xl font-bold text-base-content mb-4 mt-7">{{ $chapter->comments->count() }} Komentar</h2>

    <div class="mt-6">
        @forelse ($comments as $comment)
            {{-- Partial ini akan memanipulasi 'openReplyId' yang sudah kita definisikan di atas --}}
            @include('landing-page.partials._comment_thread', ['comment' => $comment])
        @empty
            <p class="text-base-content/60">Belum ada komentar. Jadilah yang pertama memberikan komentar!</p>
        @endforelse
    </div>
</div>
