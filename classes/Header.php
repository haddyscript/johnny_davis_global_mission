<?php
/**
 * Renders the site navigation header.
 *
 * @param string $base       URL base prefix ('' for root, '../' from views/)
 * @param string $variant    Layout variant: 'ministry' | 'donation'
 * @param string $activePage Slug of the active nav item (e.g. 'ministry', 'donation')
 */
class Header {
    private string $base;
    private string $variant;
    private string $activePage;

    public function __construct(
        string $base       = '',
        string $variant    = 'ministry',
        string $activePage = ''
    ) {
        $this->base       = $base;
        $this->variant    = $variant;
        $this->activePage = $activePage;
    }

    public function render(): void {
        $base       = $this->base;
        $activePage = $this->activePage;
        include __DIR__ . '/../layouts/header-' . $this->variant . '.php';
    }
}
