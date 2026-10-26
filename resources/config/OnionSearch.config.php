<?php
declare(strict_types=1);

/**
 * Onion search backend config. The section 'OnionSearchConfig' is named
 * after the class; there is no OnionSearchConfig.config.php, so ModuleLoader
 * falls back to this file, named after the parent namespace segment (like
 * BotTrapConfig → BotTrap.config.php).
 *
 * base_url must be the local onioncrawler search server. Only its scheme and
 * host are used; the user's query and page are appended safely. Anything that
 * isn't http(s) is replaced by the localhost default. AstrX never speaks Tor
 * itself: the engine handles the SOCKS hop.
 */
return [
    'OnionSearchConfig' => [
        'base_url'        => 'http://127.0.0.1:8802', // onioncrawler `search` endpoint (localhost only)
        'timeout_seconds' => 3,                       // per-request network timeout (clamped 1–5)
        'per_page'        => 10,                       // results-per-page hint (engine paginates; clamped 1–50)
    ],
];
