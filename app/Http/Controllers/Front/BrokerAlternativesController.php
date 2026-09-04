<?php

namespace App\Http\Controllers\Front;

use App\Services\BrokerAlternativesService;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BrokerAlternativesController extends FrontController
{
    public function index(BrokerAlternativesService $alternatives): View
    {
        $this->bootFront();

        $pages = $alternatives->publishedPages();

        return view('front.brokers.alternatives.index', compact('pages'));
    }

    public function show(string $slug, BrokerAlternativesService $alternatives): View|RedirectResponse
    {
        $this->bootFront();

        $canonical = $alternatives->canonicalBrokerSlug($slug);
        abort_if($canonical === null, 404);

        $page = $alternatives->findPublishedPage($canonical);
        abort_if($page === null || ! $page->broker, 404);

        if ($slug !== $page->broker->slug) {
            return redirect()->route('broker.alternatives.show', ['slug' => $page->broker->slug], 301);
        }

        $payload = $alternatives->buildShowPayload($page);
        $payload['jsonLd'] = $this->jsonLd($payload);

        return view('front.brokers.alternatives.show', $payload);
    }

    /** @param array<string, mixed> $payload */
    protected function jsonLd(array $payload): array
    {
        $broker = $payload['broker'];
        $canonical = route('broker.alternatives.show', ['slug' => $broker->slug]);
        $graph = [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Broker Alternatives', 'item' => route('broker.alternatives.index')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => 'Best alternatives to '.$broker->name, 'item' => $canonical],
                ],
            ],
        ];

        if ($payload['faqs'] !== []) {
            $graph[] = [
                '@type' => 'FAQPage',
                '@id' => $canonical.'#faq',
                'mainEntity' => array_map(fn (array $faq) => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ],
                ], $payload['faqs']),
            ];
        }

        if ($payload['alternatives']->isNotEmpty()) {
            $graph[] = [
                '@type' => 'ItemList',
                '@id' => $canonical.'#alternatives',
                'name' => 'Best alternatives to '.$broker->name,
                'itemListElement' => $payload['alternatives']->values()->map(function ($alt, $index) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $alt->name,
                        'url' => route('broker_detail', ['slug' => \App\Http\Controllers\Front\BrokerController::reviewSlugFor($alt)]),
                    ];
                })->all(),
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    }
}
