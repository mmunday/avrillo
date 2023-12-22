<?php
declare(strict_types = 1);

namespace App\Services\Quotes;

use App\Interfaces\QuoteDriver;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Driver for retrieving Kanye West quotes
 */
class KanyeRestDriver implements QuoteDriver
{
    /** @var string The URL to make requests to */
    private const URL = 'https://api.kanye.rest';

    /**
     * @inheritdoc
     */
    public function getQuotes(int $number): array
    {
        $quotes = Cache::get(self::URL, []);

        // If we have enough quotes cached, use them
        if (count($quotes) >= $number) {
            return array_slice($quotes, 0, $number);
        }

        // Otherwise, make requests to get the required number of quotes
        $responses = Http::pool(
            static function (Pool $pool) use ($number, $quotes) {
                $requests = $number - count($quotes);
                $responses = [];

                for ($i = 0; $i < $requests; $i++) {
                    $responses[] = $pool->get(self::URL);
                }

                return $responses;
            },
        );

        foreach ($responses as $response) {
            $quotes[] = $response->json('quote');
        }

        Cache::set(self::URL, $quotes);

        return $quotes;
    }

    /**
     * @inheritdoc
     */
    public function clearCache(): void
    {
        Cache::delete(self::URL);
    }
}
