<?php

namespace Tests\Unit;

use App\Services\Quotes\KanyeRestDriver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Tests to exercise the KanyeRestDriver
 */
class KanyeRestDriverTest extends TestCase
{
    /**
     * Test to exercise the `getQuotes` method
     *
     * @param string[]|null $cachedQuotes        The quotes already cached
     * @param int           $numberOfQuotes      The number of quotes to fetch
     * @param int           $expectedApiRequests The expected number of API requests made
     * @param string[]      $expectedQuotes      The quotes expected to be returned
     *
     * @return void
     * @dataProvider data_get_quotes
     */
    public function test_get_quotes(
        ?array $cachedQuotes,
        int $numberOfQuotes,
        int $expectedApiRequests,
        array $expectedQuotes
    ): void {
        Cache::spy();
        Cache::shouldReceive('get')
             ->once()
             ->with('https://api.kanye.rest', [])
             ->andReturn($cachedQuotes ?? []);

        Http::fake(
            [
                'https://api.kanye.rest' => Http::sequence()
                                                ->push(['quote' => 'Quote 1'])
                                                ->push(['quote' => 'Quote 2'])
                                                ->push(['quote' => 'Quote 3'])
                                                ->push(['quote' => 'Quote 4'])
                                                ->push(['quote' => 'Quote 5']),
            ],
        );

        $driver = new KanyeRestDriver();

        self::assertEquals(
            $expectedQuotes,
            $driver->getQuotes($numberOfQuotes),
        );
    }

    /**
     * Data provider for `test_get_quotes`
     *
     * @return iterable
     */
    public static function data_get_quotes(): iterable
    {
        yield 'With no cache, all quotes should be fetched from the Api' => [
            'cachedQuotes'        => null,
            'numberOfQuotes'      => 2,
            'expectedApiRequests' => 2,
            'expectedQuotes'      => ['Quote 1', 'Quote 2'],
        ];

        yield 'Fetch all quotes from the cache where possible' => [
            'cachedQuotes'        => ['Cached Quote 1', 'Cached Quote 2', 'Cached Quote 3', 'Cached Quote 4'],
            'numberOfQuotes'      => 3,
            'expectedApiRequests' => 0,
            'expectedQuotes'      => ['Cached Quote 1', 'Cached Quote 2', 'Cached Quote 3'],
        ];

        yield 'Fetch remaining quotes from the Api if the cache doesn\'t have enough' => [
            'cachedQuotes'        => ['Cached Quote 1', 'Cached Quote 2', 'Cached Quote 3'],
            'numberOfQuotes'      => 5,
            'expectedApiRequests' => 2,
            'expectedQuotes'      => ['Cached Quote 1', 'Cached Quote 2', 'Cached Quote 3', 'Quote 1', 'Quote 2'],
        ];
    }
}
