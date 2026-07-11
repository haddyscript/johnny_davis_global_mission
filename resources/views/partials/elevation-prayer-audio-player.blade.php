{{-- Renders the audio player for one Elevation Prayer episode. Expects $audioFile and $audioLabel. --}}
<p class="ep-audio-label">{{ $audioLabel }}</p>
<audio controls preload="none" class="ep-audio-player">
  <source src="{{ asset($audioFile) }}" type="audio/mp4">
  Your browser does not support audio playback.
  <a href="{{ asset($audioFile) }}">Download the audio file</a> instead.
</audio>
<a class="ep-audio-fallback" href="{{ asset($audioFile) }}" target="_blank" rel="noopener noreferrer">
  Open in new tab / Listen Now ↗
</a>
