<?php
require_once __DIR__ . '/Page.php';
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/Footer.php';

/**
 * Assembles and renders the Johnny Davis Ministries page.
 *
 * Sections: Hero Slider → About → Daily Push → Podcast →
 *           Events → Hunger CTA → Inspire → Upcoming Events →
 *           Video Modal → Footer
 */
class MinistryPage extends Page {
    private Header $header;
    private Footer $footer;

    public function __construct(string $base = '') {
        parent::__construct($base);

        $this->title       = 'Johnny Davis Ministries — Transforming Lives';
        $this->description = 'Johnny Davis Ministries — Transforming Lives, Empowering Communities, '
                           . 'Expanding the Kingdom of God. Explore the ministry, Daily Push videos, '
                           . 'podcast, and upcoming events.';
        $this->cssFile     = 'style/for_ministry.css';
        $this->jsFile      = 'js/for_ministry.js';

        $this->header = new Header($base, 'ministry', 'ministry');
        $this->footer = new Footer($base, 'ministry');
    }

    protected function renderHead(): void {
        $base        = $this->base;
        $title       = $this->title;
        $description = $this->description;
        $cssFile     = $this->cssFile;
        // Ministry uses the Playfair italic variant — separate head layout
        include __DIR__ . '/../layouts/head-ministry.php';
    }

    protected function renderBody(): void {
        $this->header->render();

        // Sticky floating donate button (outside any section)
        $base = $this->base;
        echo "\n<a id=\"stickyDonate\" class=\"btn btn-primary\" href=\"{$base}donationpage.php\" aria-label=\"Donate Now\">Donate Now</a>\n";

        $this->component('hero');
        $this->component('about');
        $this->component('daily-push');
        $this->component('podcast');
        $this->component('events');
        $this->component('hunger-cta');
        $this->component('inspire');
        $this->component('upcoming-events');
        $this->component('video-modal');

        $this->footer->render();
    }
}
