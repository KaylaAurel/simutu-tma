@props([
    'photo' => null,
    'title' => 'Dokumentasi',
    'caption' => null,
])

@if($photo)

    @php
        $photoPath = is_string($photo)
            ? $photo
            : ($photo->path ?? $photo->file_path ?? null);
    @endphp

    @if($photoPath)

        <div class="photo-preview">

            <div class="photo-preview-header">

                <div>
                    <h4>
                        {{ $title }}
                    </h4>

                    @if($caption)
                        <span>
                            {{ $caption }}
                        </span>
                    @endif
                </div>

            </div>


            <div class="photo-preview-image-wrapper">

                <img
                    src="{{ asset('storage/' . $photoPath) }}"
                    alt="{{ $title }}"
                    class="photo-preview-image"
                    loading="lazy"
                    onclick="openImageModal(this.src, this.alt)"
                >

                <button
                    type="button"
                    class="photo-preview-expand"
                    onclick="openImageModal(
                        '{{ asset('storage/' . $photoPath) }}',
                        '{{ $title }}'
                    )"
                    title="Perbesar foto"
                >
                    ⛶
                </button>

            </div>

        </div>

    @endif

@else

    <div class="photo-empty">

        <div class="photo-empty-icon">
            📷
        </div>

        <span>
            Belum ada dokumentasi
        </span>

    </div>

@endif