<?php
/**
 * Abstract base class for all site pages.
 *
 * Subclasses define $title, $description, $cssFile, $jsFile and
 * implement renderBody() to assemble their specific sections.
 */
abstract class Page {
    protected string $base;
    protected string $title       = '';
    protected string $description = '';
    protected string $cssFile     = '';
    protected ?string $jsFile     = null;

    public function __construct(string $base = '') {
        $this->base = $base;
    }

    /** Render the complete HTML document. */
    final public function render(): void {
        echo "<!DOCTYPE html>\n<html lang=\"en\">\n";
        $this->renderHead();
        echo "<body>\n";
        $this->renderBody();
        $this->renderScripts();
        echo "\n</body>\n</html>";
    }

    /** Render the <head> block via a shared layout. */
    protected function renderHead(): void {
        $base        = $this->base;
        $title       = $this->title;
        $description = $this->description;
        $cssFile     = $this->cssFile;
        include __DIR__ . '/../layouts/head.php';
    }

    /** Subclasses build their page body here. */
    abstract protected function renderBody(): void;

    /** Append page-specific JS just before </body>. */
    protected function renderScripts(): void {
        if ($this->jsFile) {
            $src = htmlspecialchars($this->base . $this->jsFile);
            echo "\n<script src=\"{$src}\"></script>";
        }
    }

    /**
     * Include a component partial, exposing $base and any extra $data.
     *
     * @param string  $name  Filename without .php (e.g. 'hero')
     * @param array   $data  Extra variables to extract into component scope
     */
    protected function component(string $name, array $data = []): void {
        $base = $this->base;
        extract($data, EXTR_SKIP);
        include __DIR__ . '/../components/' . $name . '.php';
    }
}
