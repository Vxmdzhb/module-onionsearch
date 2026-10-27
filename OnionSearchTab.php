<?php
declare(strict_types=1);

namespace AstrX\OnionSearch;

use AstrX\I18n\Translator;
use AstrX\Routing\UrlGenerator;
use AstrX\Search\Tab\SearchHit;
use AstrX\Search\Tab\SearchTab;
use AstrX\Search\Tab\SearchTabPage;

/**
 * The Search page's "Onion" tab: Onion services, through the onioncrawler engine. The client has already cleaned every
 * field and reduced each link to http(s).
 */
final class OnionSearchTab implements SearchTab
{
    public function __construct(
        private readonly OnionSearchClient $client,
        private readonly Translator $t,
        private readonly UrlGenerator $urlGen,
    ) {}

    public function key(): string
    {
        return 'onion';
    }

    public function label(): string
    {
        $this->t->load('OnionSearch');
        return $this->t->t('onionsearch.tab');
    }

    public function order(): int
    {
        return 20;
    }

    public function search(string $query, int $page): SearchTabPage
    {
        $this->t->load('OnionSearch');
        $more = $this->urlGen->toPage($this->t->t('WORDING_ONIONSEARCH'), ['q' => $query]);
        $resp = $this->client->search($query, $page);
        if (!$resp['ok']) {
            return SearchTabPage::unavailable($more);
        }
        $hits = [];
        foreach ($resp['results'] as $r) {
            $hits[] = new SearchHit(
                title:      $r['title'] !== '' ? $r['title'] : $r['url'],
                url:        $r['href'],
                displayUrl: $r['url'],
                snippet:    $r['snippet'],
            );
        }
        $pages = max(1, intdiv($resp['total'] + max(1, $resp['page_size']) - 1, max(1, $resp['page_size'])));
        return new SearchTabPage(true, $hits, $resp['total'], $resp['page'] < $pages, $more);
    }
}
