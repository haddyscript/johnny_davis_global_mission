<?php
/**
 * Renders the site footer.
 *
 * @param string $base    URL base prefix
 * @param string $variant Layout variant: 'ministry' | 'donation'
 */
class Footer {
    private string $base;
    private string $variant;

    public function __construct(string $base = '', string $variant = 'ministry') {
        $this->base    = $base;
        $this->variant = $variant;
    }

    public function render(): void {
        $base = $this->base;
        include __DIR__ . '/../layouts/footer-' . $this->variant . '.php';
    }
}
