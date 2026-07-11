{{-- Renders a parsed Elevation Prayer caption. Expects $blocks and $hashtags (see elevation-prayer-spotlight.blade.php). --}}
@foreach ($blocks as $block)
  @if ($block['type'] === 'heading')
    <h3 class="ep-caption-heading">{{ $block['text'] }}</h3>
  @elseif ($block['type'] === 'citation')
    <p class="ep-caption-citation">{{ $block['text'] }}</p>
  @elseif ($block['type'] === 'quote')
    <p class="ep-caption-quote">{{ $block['text'] }}</p>
  @elseif ($block['type'] === 'meta')
    <p class="ep-caption-meta">{{ $block['text'] }}</p>
  @elseif ($block['type'] === 'divider')
    <hr class="ep-caption-divider">
  @else
    <p class="ep-caption-text">{{ $block['text'] }}</p>
  @endif
@endforeach

@if (count($hashtags))
  <div class="ep-hashtag-row">
    @foreach ($hashtags as $tag)
      <span class="ep-hashtag">{{ $tag }}</span>
    @endforeach
  </div>
@endif
