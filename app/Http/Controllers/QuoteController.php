<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Facades\Quotes;
use Illuminate\Http\JsonResponse;

/**
 * Controller for interacting with quotes
 */
class QuoteController extends Controller
{
    /** @var int The number of quotes to return */
    private const NUM_QUOTES = 5;

    /**
     * Endpoint for getting random quotes
     *
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        $quotes = Quotes::getQuotes(self::NUM_QUOTES);

        return response()->json($quotes);
    }

    /**
     * Endpoint for refreshing the random quotes
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        Quotes::clearCache();

        return $this->get();
    }
}
