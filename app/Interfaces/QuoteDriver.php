<?php
declare(strict_types = 1);

namespace App\Interfaces;

/**
 * Describes a driver for getting quotes from different sources
 */
interface QuoteDriver
{
    /**
     * Get a quote
     *
     * @param int $number The number of quotes to retrieve
     *
     * @return string[]
     */
    public function getQuotes(int $number): array;

    /**
     * Clear the cache of quotes
     *
     * @return void
     */
    public function clearCache(): void;
}
