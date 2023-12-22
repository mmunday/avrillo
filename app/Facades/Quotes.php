<?php
declare(strict_types = 1);

namespace App\Facades;

use App\Services\Quotes\QuotesManager;
use Illuminate\Support\Facades\Facade;

/**
 * Facade for getting quotes from different sources
 *
 * @method static array getQuotes(int $number)
 * @method static void clearCache()
 */
class Quotes extends Facade
{
    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor(): string
    {
        return QuotesManager::class;
    }
}
