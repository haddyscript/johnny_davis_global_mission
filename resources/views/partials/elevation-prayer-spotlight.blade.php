{{--
  ELEVATION PRAYER GATHERING — WEEKLY SPOTLIGHT
  ============================================================
  To publish next week's update, only the values in the PHP
  block below need to change — the layout/markup stays the same:

    - $epPoster       path to this week's poster image (public/images/...)
    - $epEpisode      episode number shown in the badge
    - $epTitle        theme/title shown in the header
    - $epAudioFile    path to this week's audio file (public/audio/...)
    - $epAudioLabel   label shown on the audio player
    - $epCaption      full caption text, copied as-is from the Facebook post
--}}
@php
    $epPoster     = 'images/johnny-davis-ministry/elevation-new-prayer.jpg';
    $epEpisode    = '226';
    $epTitle      = 'From Grief to Joy';
    $epAudioFile  = 'audio/elevation-prayer/episode-226-from-grief-to-joy.m4a';
    $epAudioLabel = 'Episode 226 — From Grief to Joy';

    $epCaption = <<<'TEXT'
FROM GRIEF TO JOY-ELEVATION VIRTUAL PRAYER GATHERING
Episode 226
ONE VOICE. ONE PRAYER. ONE ACCORD.
Inspired by Acts 1:14
"These all continued with one accord in prayer and supplication..."
— Acts 1:14 (KJV)
FROM GRIEF TO JOY
A Night of Healing and Hope
Are you carrying the weight of grief, loss, disappointment, or emotional pain? You are not alone.
Join us this Thursday night as we gather online to seek God's Presence, find encouragement, and experience the healing power of prayer.
"Verily, verily, I say unto you, That ye shall weep and lament... and ye shall be sorrowful, but your sorrow shall be turned into joy."
— John 16:20 (KJV)
No matter what you are facing today, come expecting:
✅ Healing for broken hearts
✅ Comfort for families
✅ Emotional restoration
✅ Renewed hope and joy
✅ Strength during difficult seasons
✅ God's peace and presence
📅 Thursday, June 25, 2026
🕖 7:00 PM EST
🎵 Worship Music Begins at 6:45 PM EST
💻 Join Us on Zoom
Meeting ID: 788 154 3458
Passcode: dzW3WL
(Passcode is case-sensitive)
Invite your family, friends, coworkers, church members, and anyone who could use prayer, encouragement, and hope. Together, let's believe God for healing, restoration, breakthrough, and joy.
🌐 Learn More:
JohnnyDavisMinistries.org
❤️ Support Our Mission
For just $7.99 per month, you can help provide meals for children and families in need through Johnny Davis Global Missions.
🌐 JohnnyDavisGlobalMissions.org
Hosted by:
Evangelist Johnny Davis
FOUNDATION SCRIPTURES: GRIEF TO JOY
John 16:20 (KJV)
"Verily, verily, I say unto you, That ye shall weep and lament, but the world shall rejoice: and ye shall be sorrowful, but your sorrow shall be turned into joy."
Acts 1:14 (KJV)
"These all continued with one accord in prayer and supplication, with the women, and Mary the mother of Jesus, and with his brethren."
COMFORT IN GRIEF
Psalm 34:18 (KJV)
"The Lord is nigh unto them that are of a broken heart; and saveth such as be of a contrite spirit."
Matthew 5:4 (KJV)
"Blessed are they that mourn: for they shall be comforted."
Psalm 147:3 (KJV)
"He healeth the broken in heart, and bindeth up their wounds."
HOPE IN DIFFICULT TIMES
Psalm 46:1 (KJV)
"God is our refuge and strength, a very present help in trouble."
Isaiah 41:10 (KJV)
"Fear thou not; for I am with thee: be not dismayed; for I am thy God: I will strengthen thee; yea, I will help thee; yea, I will uphold thee with the right hand of my righteousness."
John 14:27 (KJV)
"Peace I leave with you, my peace I give unto you: not as the world giveth, give I unto you. Let not your heart be troubled, neither let it be afraid."
CASTING YOUR CARES ON GOD
Psalm 55:22 (KJV)
"Cast thy burden upon the Lord, and he shall sustain thee: he shall never suffer the righteous to be moved."
1 Peter 5:7 (KJV)
"Casting all your care upon him; for he careth for you."
Matthew 11:28 (KJV)
"Come unto me, all ye that labour and are heavy laden, and I will give you rest."
FROM MOURNING TO JOY
Psalm 30:5 (KJV)
"For his anger endureth but a moment; in his favour is life: weeping may endure for a night, but joy cometh in the morning."
Isaiah 61:3 (KJV)
"To appoint unto them that mourn in Zion, to give unto them beauty for ashes, the oil of joy for mourning, the garment of praise for the spirit of heaviness; that they might be called trees of righteousness, the planting of the Lord, that he might be glorified."
GOD'S COMFORT AND HEALING
2 Corinthians 1:3-4 (KJV)
"Blessed be God, even the Father of our Lord Jesus Christ, the Father of mercies, and the God of all comfort; Who comforteth us in all our tribulation, that we may be able to comfort them which are in any trouble, by the comfort wherewith we ourselves are comforted of God."
Psalm 73:26 (KJV)
"My flesh and my heart faileth: but God is the strength of my heart, and my portion for ever."
Psalm 61:2 (KJV)
"From the end of the earth will I cry unto thee, when my heart is overwhelmed: lead me to the rock that is higher than I."
ETERNAL HOPE
Revelation 21:4 (KJV)
"And God shall wipe away all tears from their eyes; and there shall be no more death, neither sorrow, nor crying, neither shall there be any more pain: for the former things are passed away."
Elevation Virtual Prayer Gathering
ONE VOICE. ONE PRAYER. ONE ACCORD.
Inspired by Acts 1:14
#ElevationVirtualPrayerGathering
#FromGriefToJoy
#OneVoiceOnePrayerOneAccord
#Acts114
#John1620
#PrayerChangesThings
#HealingForTheBrokenhearted
#FaithOverFear
#ChristianCommunity
#VirtualPrayer
#HopeInChrist
#JohnnyDavisMinistries
#JohnnyDavisGlobalMissions
TEXT;

    // Classify each line of the caption so it reads well on the page
    // without altering any of the original words.
    $epBlocks   = [];
    $epHashtags = [];

    foreach (preg_split('/\r\n|\r|\n/', trim($epCaption)) as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        if (str_starts_with($line, '#')) {
            $epHashtags[] = $line;
            continue;
        }

        $lettersOnly = preg_replace('/[^A-Za-z]/', '', $line);
        $isHeading   = $lettersOnly !== '' && $lettersOnly === strtoupper($lettersOnly) && strlen($lettersOnly) > 2;
        $isQuote     = str_starts_with($line, '"') || str_starts_with($line, '—');
        $isCitation  = str_contains($line, '(KJV)');

        $type = 'text';
        if ($isHeading) {
            $type = 'heading';
        } elseif ($isCitation) {
            $type = 'citation';
        } elseif ($isQuote) {
            $type = 'quote';
        } elseif (preg_match('/^[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $line)) {
            $type = 'meta';
        }

        $epBlocks[] = ['type' => $type, 'text' => $line];
    }
@endphp

<section id="elevation-prayer-spotlight" aria-labelledby="ep-spotlight-title">
  <div class="container">
    <header class="ep-header reveal">
      <span class="section-label">This Week's Gathering</span>
      <h2 class="section-title" id="ep-spotlight-title">Elevation Prayer Gathering</h2>
    </header>

    <div class="ep-grid reveal">
      <div class="ep-poster-col">
        <span class="ep-episode-badge">Episode {{ $epEpisode }}</span>
        <img src="{{ asset($epPoster) }}" alt="Elevation Prayer Gathering — {{ $epTitle }}" class="ep-poster-img" />

        {{-- 🎧 Listen to This Week's Prayer Replay --}}
        <div class="ep-audio-accordion">
          <button type="button" class="ep-audio-toggle" aria-expanded="false" aria-controls="ep-audio-panel">
            <span>🎧 Listen to This Week's Prayer Replay</span>
            <svg class="ep-audio-chevron" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="ep-audio-panel" id="ep-audio-panel" hidden>
            <p class="ep-audio-label">{{ $epAudioLabel }}</p>
            <audio controls preload="none" class="ep-audio-player">
              <source src="{{ asset($epAudioFile) }}" type="audio/mp4">
              Your browser does not support audio playback.
              <a href="{{ asset($epAudioFile) }}">Download the audio file</a> instead.
            </audio>
            <a class="ep-audio-fallback" href="{{ asset($epAudioFile) }}" target="_blank" rel="noopener noreferrer">
              Open in new tab / Listen Now ↗
            </a>
          </div>
        </div>
      </div>

      <div class="ep-caption-col">
        @foreach ($epBlocks as $block)
          @if ($block['type'] === 'heading')
            <h3 class="ep-caption-heading">{{ $block['text'] }}</h3>
          @elseif ($block['type'] === 'citation')
            <p class="ep-caption-citation">{{ $block['text'] }}</p>
          @elseif ($block['type'] === 'quote')
            <p class="ep-caption-quote">{{ $block['text'] }}</p>
          @elseif ($block['type'] === 'meta')
            <p class="ep-caption-meta">{{ $block['text'] }}</p>
          @else
            <p class="ep-caption-text">{{ $block['text'] }}</p>
          @endif
        @endforeach

        @if (count($epHashtags))
          <div class="ep-hashtag-row">
            @foreach ($epHashtags as $tag)
              <span class="ep-hashtag">{{ $tag }}</span>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
