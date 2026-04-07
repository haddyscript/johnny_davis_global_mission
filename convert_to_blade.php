<?php
/**
 * Converts legacy PHP views to Blade templates.
 * Run: php convert_to_blade.php
 */

function convertToBladeAssets(string $content): string
{
    $patterns = [
        // CSS
        "<?= \$base ?>style/for_index.css"        => "{{ asset('css/for_index.css') }}",
        "<?= \$base ?>style/for_news.css"          => "{{ asset('css/for_news.css') }}",
        "<?= \$base ?>style/for_contact.css"       => "{{ asset('css/for_contact.css') }}",
        "<?= \$base ?>style/for_donationpage.css"  => "{{ asset('css/for_donationpage.css') }}",
        "<?= \$base ?>style/for_ministry.css"      => "{{ asset('css/for_ministry.css') }}",
        "<?= \$base ?>style/for_who_we_are.css"    => "{{ asset('css/for_who_we_are.css') }}",

        // JS
        "<?= \$base ?>js/for_index.js"             => "{{ asset('js/for_index.js') }}",
        "<?= \$base ?>js/for_news.js"              => "{{ asset('js/for_news.js') }}",
        "<?= \$base ?>js/for_contact.js"           => "{{ asset('js/for_contact.js') }}",
        "<?= \$base ?>js/for_donationpage.js"      => "{{ asset('js/for_donationpage.js') }}",
        "<?= \$base ?>js/for_ministry.js"          => "{{ asset('js/for_ministry.js') }}",
        "<?= \$base ?>js/for_who_we_are.js"        => "{{ asset('js/for_who_we_are.js') }}",
        "./js/for_who_we_are.js"                   => "{{ asset('js/for_who_we_are.js') }}",

        // Routes
        "<?= \$base ?>news.php"                    => "{{ route('news') }}",
        "<?= \$base ?>who-we-are.php"              => "{{ route('who-we-are') }}",
        "<?= \$base ?>johnny-davis-ministry.php"   => "{{ route('ministry') }}",
        "<?= \$base ?>donationpage.php"            => "{{ route('donate') }}",
        "<?= \$base ?>contact.php"                 => "{{ route('contact') }}",
        "<?= \$base ?>index.php"                   => "{{ route('home') }}",

        // Static paths without $base (used in who-we-are.php)
        'href="index.php'                          => 'href="{{ route(\'home\') }}',
        'href="news.php'                           => 'href="{{ route(\'news\') }}',
        'href="who-we-are.php'                     => 'href="{{ route(\'who-we-are\') }}',
        'href="johnny-davis-ministry.php'          => 'href="{{ route(\'ministry\') }}',
        'href="donationpage.php'                   => 'href="{{ route(\'donate\') }}',
        'href="contact.php'                        => 'href="{{ route(\'contact\') }}',

        // Meta tags
        "<?= htmlspecialchars(\$page_title) ?>"     => "{{ \$title }}",
        "<?= htmlspecialchars(\$page_description) ?>" => "{{ \$description }}",

        // PHP date
        "<?= date('Y') ?>"                         => "{{ date('Y') }}",

        // Leftover $base
        "<?= \$base ?>"                             => "",
    ];

    return str_replace(array_keys($patterns), array_values($patterns), $content);
}

function convertImages(string $content): string
{
    // Replace <?= $base ?>images/... patterns
    $content = preg_replace_callback(
        '/\<\?= \$base \?>(images\/[^"\'\\s\\)]+)/',
        fn($m) => "{{ asset('" . $m[1] . "') }}",
        $content
    );
    // Static image paths (no $base prefix, e.g. in who-we-are.php)
    $content = preg_replace_callback(
        '/(?<=["\'])images\/([^"\'\\s]+)(?=["\'])/',
        fn($m) => "{{ asset('images/" . $m[1] . "') }}",
        $content
    );
    return $content;
}

function convertNewsBlocks(string $content): string
{
    $patterns = [
        '<?= $base . htmlspecialchars($post[\'image\']) ?>'  => "{{ asset(\$post['image']) }}",
        "<?= \$base . htmlspecialchars(\$post['image']) ?>"  => "{{ asset(\$post['image']) }}",
        "<?= htmlspecialchars(\$post['categories']) ?>"      => "{{ \$post['categories'] }}",
        "<?= htmlspecialchars(\$post['country']) ?>"         => "{{ \$post['country'] }}",
        "<?= htmlspecialchars(\$post['title']) ?>"           => "{{ \$post['title'] }}",
        "<?= htmlspecialchars(\$post['excerpt']) ?>"         => "{{ \$post['excerpt'] }}",
        "<?= htmlspecialchars(\$post['cta_label']) ?>"       => "{{ \$post['cta_label'] }}",
        "<?= htmlspecialchars(\$post['img_alt']) ?>"         => "{{ \$post['img_alt'] }}",
        "<?= htmlspecialchars(\$post['read_time']) ?>"       => "{{ \$post['read_time'] }}",
        "<?= htmlspecialchars(\$post['date']) ?>"            => "{{ \$post['date'] }}",
        "<?= htmlspecialchars(\$post['category']) ?>"        => "{{ \$post['category'] }}",
        "<?= \$post['flag'] ?>"                              => "{{ \$post['flag'] }}",
        "<?= \$post['cat_class'] ?>"                         => "{{ \$post['cat_class'] }}",
        "<?= \$post['delay'] ?>"                             => "{{ \$post['delay'] }}",
        "<?= count(\$posts) ?>"                              => "{{ count(\$posts) }}",
        // Complex href
        "<?= htmlspecialchars(\$post['cta_href']) ?>"        => "{{ \$post['cta_href'] }}",
        // The complex conditional href
        "<?= \$base . htmlspecialchars(\$post['cta_href'] !== '#' ? \$post['cta_href'] : '#') ?>"
            => "{{ \$post['cta_href'] !== '#' ? url(\$post['cta_href']) : '#' }}",

        // PHP control structures
        '<?php foreach ($posts as $post): ?>'                => '@foreach($posts as $post)',
        '<?php if ($post[\'featured\']): ?>'                 => "@if(\$post['featured'])",
        '<?php else: ?>'                                     => '@else',
        '<?php endif; ?>'                                    => '@endif',
        '<?php endforeach; ?>'                              => '@endforeach',
    ];
    return str_replace(array_keys($patterns), array_values($patterns), $content);
}

// ----- Convert home.blade.php -----
echo "Converting home.blade.php...\n";
$src = file_get_contents('views/index.php');
// Remove PHP header block (lines 1-8)
$lines = explode("\n", $src);
$lines = array_slice($lines, 8);
$content = implode("\n", $lines);
$content = convertToBladeAssets($content);
$content = convertImages($content);
file_put_contents('resources/views/home.blade.php', $content);
echo "Done: resources/views/home.blade.php\n";

// ----- Convert news.blade.php -----
echo "Converting news.blade.php...\n";
$src = file_get_contents('views/news.php');
// Remove PHP header block (lines 1-116)
$lines = explode("\n", $src);
$lines = array_slice($lines, 116);
$content = implode("\n", $lines);
$content = convertToBladeAssets($content);
$content = convertImages($content);
$content = convertNewsBlocks($content);
file_put_contents('resources/views/news.blade.php', $content);
echo "Done: resources/views/news.blade.php\n";

echo "\nAll conversions complete!\n";
