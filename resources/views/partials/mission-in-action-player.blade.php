{{--
  One Mission in Action video. Plays the site-hosted MP4 when it has been uploaded to public/
  (for TikTok videos that can't be embedded), otherwise TikTok's embedded player.

  @param array{id: string, title: string, file?: string} $video
  @param int    $number      Position in the gallery (1–7)
  @param string $playerClass Extra class for the wrapper
--}}
@php
  $miaLocalFile = isset($video['file']) && file_exists(public_path($video['file'])) ? $video['file'] : null;
@endphp
<div class="mia-player {{ $playerClass ?? '' }}">
  @if($miaLocalFile)
    {{-- #t=0.1 shows the first frame as the thumbnail; nothing else downloads until play --}}
    <video src="{{ asset($miaLocalFile) }}?v={{ filemtime(public_path($miaLocalFile)) }}#t=0.1"
           title="Video {{ $number }}: {{ $video['title'] }}"
           controls
           playsinline
           preload="metadata"></video>
  @else
    <iframe src="https://www.tiktok.com/player/v1/{{ $video['id'] }}?music_info=0&description=0&rel=0&native_context_menu=0"
            title="Video {{ $number }}: {{ $video['title'] }}"
            allow="encrypted-media; fullscreen; picture-in-picture"
            allowfullscreen
            loading="lazy"></iframe>
  @endif
</div>
