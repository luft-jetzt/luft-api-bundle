<?php declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        // The codebase intentionally uses the inline `<?php declare(strict_types=1);`
        // header, so keep that style instead of forcing a blank line after the tag.
        'blank_line_after_opening_tag' => false,
    ])
    ->setFinder($finder);
