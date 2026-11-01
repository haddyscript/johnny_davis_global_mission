{{--
  One Mission in Action video. Plays the site-hosted MP4 when it has been uploaded to public/
  (for TikTok videos that can't be embedded). Otherwise shows a thumbnail with a play button and
  only loads TikTok's player when it is pressed (js/mission-in-action.js), keeping the page fast.

  Thumbnails: public/images/mission-in-action/thumbs/{TikTok video id}.jpg (Johnny's posters, shown
  whole over a blurred copy of themselves). thumb 'frame' = a photo that fills the card;
  'titled' = a photo with the video title over it. Videos without one get a branded title card.

  @param array{id: string, title: string, file?: string, thumb?: 'frame'|'titled'} $video
  @param int    $number      Position in the gallery (1–7)
  @param string $playerClass Extra class for the wrapper
--}}
@php
  $miaLocalFile = isset($video['file']) && file_exists(public_path($video['file'])) ? $video['file'] : null;
  $miaThumb = 'images/mission-in-action/thumbs/'.$video['id'].'.jpg';
  $miaThumb = file_exists(public_path($miaThumb)) ? $miaThumb : null;
@endphp
<div class="mia-player {{ $playerClass ?? '' }}">
  @if($miaLocalFile)
    {{-- #t=0.1 shows the first frame as the thumbnail; nothing else downloads until play --}}
    <video src="{{ asset($miaLocalFile) }}?v={{ filemtime(public_path($miaLocalFile)) }}#t=0.1"
           title="Video {{ $number }}: {{ $video['title'] }}"
           controls
           playsinline
           preload="metadata"
           @if($miaThumb) poster="{{ asset($miaThumb) }}" @endif></video>
  @else
    <button type="button"
            class="mia-facade {{ $miaThumb ? 'mia-facade--poster' : 'mia-facade--branded' }} @if(isset($video['thumb'])) mia-facade--frame @endif @if(($video['thumb'] ?? null) === 'titled') mia-facade--titled @endif"
            @if($miaThumb) style="--mia-thumb: url('{{ asset($miaThumb) }}')" @endif
            data-mia-play
            data-src="https://www.tiktok.com/player/v1/{{ $video['id'] }}?autoplay=1&music_info=0&description=0&rel=0&native_context_menu=0"
            data-title="Video {{ $number }}: {{ $video['title'] }}"
            aria-label="Play video {{ $number }}: {{ $video['title'] }}">
      @if($miaThumb)
        <img class="mia-thumb" src="{{ asset($miaThumb) }}" alt="" loading="lazy" decoding="async" />
      @endif
      @if(! $miaThumb || ($video['thumb'] ?? null) === 'titled')
        <span class="mia-facade-brand" aria-hidden="true">
          <span class="mia-facade-num">Video {{ $number }}</span>
          <span class="mia-facade-title">{{ $video['title'] }}</span>
        </span>
      @endif
      <span class="mia-play" aria-hidden="true">
        <svg viewBox="0 0 24 24" width="30" height="30" fill="currentColor"><path d="M8 5.5v13a1 1 0 0 0 1.52.85l10.4-6.5a1 1 0 0 0 0-1.7L9.52 4.65A1 1 0 0 0 8 5.5z"/></svg>
      </span>
      <span class="mia-spinner" aria-hidden="true"></span>
    </button>
  @endif
</div>
