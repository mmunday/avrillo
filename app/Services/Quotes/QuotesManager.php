<?php
declare(strict_types = 1);

namespace App\Services\Quotes;

use App\Interfaces\QuoteDriver;
use Illuminate\Support\Manager;

/**
 * Manager for determining where to fetch quotes from
 */
class QuotesManager extends Manager
{
    /**
     * @inheritdoc
     */
    public function getDefaultDriver(): string
    {
        return 'kanye-rest';
    }

    /**
     * Create a driver for fetching Kanye West quotes
     *
     * @return QuoteDriver
     */
    public function createKanyeRestDriver(): QuoteDriver
    {
        return new KanyeRestDriver();
    }
}
