{{--
  UGANDA LEADERSHIP MEETING — WEEKLY UPDATE + ARCHIVE
  ============================================================
  To publish next week's update:
    1. Copy the array item at the top of $mfUpdates (the newest
       week must always be item [0]) and fill in its values.
    2. Push last week's item down — it will automatically move
       into the "Meeting Archive" accordion below.
  Nothing else on the page needs to change.

  Each update needs:
    - date          shown in the badge over the poster (e.g. "Monday, August 3, 2026")
    - topic         this week's leadership topic / focus
    - poster        path to the poster image (public/images/...)
    - announcement  weekly announcement / meeting recap text, plain lines
                     (ALL CAPS lines become headings, lines starting with
                     " or — become quotes, lines starting with an emoji
                     become meta lines, a lone ⸻ becomes a divider)
    - photos        optional array of image paths from the meeting (public/images/...)
--}}
@php
    $mfUpdates = [
        [
            'date'   => 'Every Monday Evening',
            'topic'  => 'Uganda Leadership Meeting — Now Meeting Weekly',
            'poster' => 'images/johnny-davis-ministry/ministerial-fellowship.jfif',
            'announcement' => <<<'TEXT'
JOHNNY DAVIS MINISTERIAL FELLOWSHIP
UGANDA LEADERSHIP MEETING
EMPOWERING TO LEAD. MAXIMIZING VISION.
⸻
THE PURPOSE OF OUR FELLOWSHIP
To empower leaders, strengthen ministries, and equip believers through the Word of God, prayer, fellowship, and practical tools to expand the Kingdom of God and maximize vision.
WHO ARE WE?
We are a global non-denominational outreach ministry and fellowship that partners with other ministries to help equip, empower, and maximize their vision and mission.
Join us every Monday evening as pastors and ministry leaders across Uganda gather together for worship, biblical teaching, leadership training, and fellowship — connecting live over WhatsApp.
🌐 Learn More:
JohnnyDavisMinistries.org
Hosted by:
Evangelist Johnny Davis
TEXT,
            'photos' => [],
        ],
    ];

    // Classify each line of every announcement so it reads well on the page
    // without altering any of the original words. (Same approach used by
    // the Elevation Prayer Gathering spotlight.)
    foreach ($mfUpdates as &$mfUpdate) {
        $blocks   = [];
        $hashtags = [];

        foreach (preg_split('/\r\n|\r|\n/', trim($mfUpdate['announcement'])) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            if (str_starts_with($line, '#')) {
                $hashtags[] = $line;
                continue;
            }

            $lettersOnly = preg_replace('/[^A-Za-z]/', '', $line);
            $isDivider   = preg_match('/^[⸻\-–—]+$/u', $line) === 1;
            $isHeading   = $lettersOnly !== '' && $lettersOnly === strtoupper($lettersOnly) && strlen($lettersOnly) > 2;
            $isQuote     = str_starts_with($line, '"') || str_starts_with($line, '"') || str_starts_with($line, '—');

            $type = 'text';
            if ($isDivider) {
                $type = 'divider';
            } elseif ($isHeading) {
                $type = 'heading';
            } elseif ($isQuote) {
                $type = 'quote';
            } elseif (preg_match('/^[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $line)) {
                $type = 'meta';
            }

            $blocks[] = ['type' => $type, 'text' => $line];
        }

        $mfUpdate['blocks']   = $blocks;
        $mfUpdate['hashtags'] = $hashtags;
    }
    unset($mfUpdate);

    $mfCurrent = $mfUpdates[0];
    $mfArchive = array_slice($mfUpdates, 1);

    $mfExpect = [
        ['icon' => '🙏', 'label' => 'Worship & Prayer'],
        ['icon' => '📖', 'label' => 'Biblical Teaching'],
        ['icon' => '🧭', 'label' => 'Leadership Development'],
        ['icon' => '📈', 'label' => 'Ministry Growth'],
        ['icon' => '🎯', 'label' => 'Vision & Mission Empowerment'],
        ['icon' => '🤝', 'label' => 'Fellowship with Ministry Leaders'],
        ['icon' => '🌍', 'label' => 'International Ministry Collaboration'],
    ];
@endphp

<section id="ministerial-fellowship-meeting" aria-labelledby="mf-meeting-title">
  <div class="container">
    <header class="ep-header reveal">
      <span class="section-label">Weekly Leadership Meeting</span>
      <h2 class="section-title" id="mf-meeting-title">Uganda Leadership Meeting</h2>
      <p class="body-text">Every Monday &middot; 7:00 PM Uganda Time &middot; Live via WhatsApp</p>
    </header>

    <div class="ep-grid reveal">
      <div class="ep-poster-col">
        <span class="ep-episode-badge">{{ $mfCurrent['date'] }}</span>
        <img
          src="{{ asset($mfCurrent['poster']) }}"
          alt="Johnny Davis Ministerial Fellowship — Uganda Leadership Meeting"
          class="ep-poster-img"
        />
      </div>

      <div class="ep-caption-col">
        <h3 class="ep-caption-heading" style="margin-top: 0;">{{ $mfCurrent['topic'] }}</h3>
        @include('partials.elevation-prayer-caption', ['blocks' => $mfCurrent['blocks'], 'hashtags' => $mfCurrent['hashtags']])
      </div>
    </div>

    <div class="reveal">
      <h3 class="section-title mf-expect-heading">What You Can Expect</h3>
      <div class="vision-list mf-expect-grid" role="list" aria-label="What to expect at the weekly meeting">
        @foreach ($mfExpect as $item)
          <div class="vision-item" role="listitem">
            <span class="vision-icon" aria-hidden="true">{{ $item['icon'] }}</span>
            {{ $item['label'] }}
          </div>
        @endforeach
      </div>
    </div>

    @if (count($mfArchive))
      <div class="ep-archive reveal">
        <button type="button" class="ep-audio-toggle ep-archive-toggle" aria-expanded="false" aria-controls="mf-archive-panel">
          <span>🗓️ Meeting Archive</span>
          <svg class="ep-audio-chevron" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="ep-archive-panel" id="mf-archive-panel" hidden>
          @foreach ($mfArchive as $mfIndex => $mfItem)
            @php $mfItemPanelId = 'mf-archive-item-' . $mfIndex; @endphp
            <div class="ep-archive-item">
              <button type="button" class="ep-audio-toggle ep-archive-item-toggle" aria-expanded="false" aria-controls="{{ $mfItemPanelId }}">
                <span>{{ $mfItem['date'] }} &ndash; {{ $mfItem['topic'] }}</span>
                <svg class="ep-audio-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
              </button>
              <div class="ep-archive-item-panel" id="{{ $mfItemPanelId }}" hidden>
                <div class="ep-archive-item-grid">
                  <div class="ep-archive-item-poster-col">
                    <img src="{{ asset($mfItem['poster']) }}" alt="Uganda Leadership Meeting — {{ $mfItem['topic'] }}" class="ep-archive-item-img" loading="lazy" />
                  </div>
                  <div class="ep-archive-item-caption-col">
                    @include('partials.elevation-prayer-caption', ['blocks' => $mfItem['blocks'], 'hashtags' => $mfItem['hashtags']])
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </div>
</section>
