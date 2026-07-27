{{-- Future features planned for the dedicated Ministerial Fellowship page. --}}
@php
    $mfFeatures = [
        ['icon' => '📰', 'title' => 'Leadership Articles',              'desc' => 'Teaching and encouragement for pastors and ministry leaders.'],
        ['icon' => '🎥', 'title' => 'Leadership Training Videos',       'desc' => 'On-demand video teaching to grow leadership skills.'],
        ['icon' => '🎙️', 'title' => 'Empowerment Session Recordings',   'desc' => 'Replays of past empowerment and training sessions.'],
        ['icon' => '📥', 'title' => 'Downloadable Leadership Resources', 'desc' => 'Guides, studies, and practical ministry tools.'],
        ['icon' => '🗓️', 'title' => 'Event Calendar',                   'desc' => 'A full calendar of upcoming fellowship events.'],
        ['icon' => '🎤', 'title' => 'Future Leadership Conferences',    'desc' => 'In-person and virtual conferences for ministry leaders.'],
        ['icon' => '📝', 'title' => 'Minister Registration',            'desc' => 'Register as a minister within the fellowship network.'],
        ['icon' => '🙏', 'title' => 'Prayer Requests',                  'desc' => 'Submit prayer requests for the fellowship to lift up.'],
        ['icon' => '📸', 'title' => 'Photo Gallery',                    'desc' => 'Photos from weekly meetings and fellowship events.'],
        ['icon' => '💬', 'title' => 'Testimonies',                      'desc' => 'Stories of impact from pastors and leaders in the network.'],
        ['icon' => '❓', 'title' => 'Frequently Asked Questions',       'desc' => 'Answers about joining and participating in the fellowship.'],
    ];
@endphp

<section id="ministerial-fellowship-features" aria-labelledby="mf-features-title">
  <div class="container">
    <header class="ep-header reveal">
      <span class="section-label">What's Coming Next</span>
      <h2 class="section-title" id="mf-features-title">Growing With the Fellowship</h2>
      <p class="body-text">
        As the Johnny Davis Ministerial Fellowship grows into a global leadership network, this page will
        expand to include:
      </p>
    </header>

    <div class="mf-features-grid reveal">
      @foreach ($mfFeatures as $feature)
        <div class="mf-feature-card">
          <span class="mf-feature-icon" aria-hidden="true">{{ $feature['icon'] }}</span>
          <h3 class="mf-feature-title">{{ $feature['title'] }}</h3>
          <p class="mf-feature-desc">{{ $feature['desc'] }}</p>
          <span class="mf-feature-badge">Coming Soon</span>
        </div>
      @endforeach
    </div>
  </div>
</section>
