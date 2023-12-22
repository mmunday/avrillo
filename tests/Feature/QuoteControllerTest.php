<?php
declare(strict_types = 1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Tests to exercise the QuoteController
 */
class QuoteControllerTest extends TestCase
{
    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        Http::fake(
            [
                'https://api.kanye.rest' => Http::sequence()
                                                ->push(['quote' => 'Quote 1'])
                                                ->push(['quote' => 'Quote 2'])
                                                ->push(['quote' => 'Quote 3'])
                                                ->push(['quote' => 'Quote 4'])
                                                ->push(['quote' => 'Quote 5'])
                                                ->push(['quote' => 'Quote 6'])
                                                ->push(['quote' => 'Quote 7'])
                                                ->push(['quote' => 'Quote 8'])
                                                ->push(['quote' => 'Quote 9'])
                                                ->push(['quote' => 'Quote 10']),
            ]
        );
    }

    /**
     * Verify that requests fail when
     *
     * @param array $headers          The HTTP headers to include in the request
     * @param int   $expectedStatus   The expected response HTTP status code
     * @param array $expectedResponse The expected response data
     *
     * @dataProvider data_get_quote
     */
    public function test_get_quote(array $headers, int $expectedStatus, array $expectedResponse): void
    {
        $response = $this->get('/api/quotes', $headers);

        $response->assertStatus($expectedStatus);
        $response->assertExactJson($expectedResponse);
    }

    /**
     * Data provider for `test_get_quote`
     *
     * @return iterable
     */
    public static function data_get_quote(): iterable
    {
        yield 'No token header provided' => [
            'headers'          => [],
            'expectedStatus'   => 403,
            'expectedResponse' => ['message' => 'Forbidden'],
        ];

        yield 'Invalid token header provided' => [
            'headers'          => ['X-TOKEN' => 'invalid'],
            'expectedStatus'   => 403,
            'expectedResponse' => ['message' => 'Forbidden'],
        ];

        yield 'Valid token provided returns 5 quotes' => [
            'headers'          => ['X-TOKEN' => 'my-not-so-secret-token'],
            'expectedStatus'   => 200,
            'expectedResponse' => ['Quote 1', 'Quote 2', 'Quote 3', 'Quote 4', 'Quote 5'],
        ];
    }

    /**
     * Test to verify quotes are cached between requests and can be refreshed
     *
     * @return void
     */
    public function test_get_quotes_from_cache(): void
    {
        // Initial request should populate cache and send requests
        $response = $this->get('/api/quotes', ['X-TOKEN' => 'my-not-so-secret-token']);

        $response->assertStatus(200);
        $response->assertExactJson(['Quote 1', 'Quote 2', 'Quote 3', 'Quote 4', 'Quote 5']);
        Http::assertSentCount(5);

        // Subsequent request should use the cache, returning the same values and not sending additional requests
        $response = $this->get('/api/quotes', ['X-TOKEN' => 'my-not-so-secret-token']);

        $response->assertStatus(200);
        $response->assertExactJson(['Quote 1', 'Quote 2', 'Quote 3', 'Quote 4', 'Quote 5']);
        Http::assertSentCount(5);

        // Triggering a refresh should load the next 5 quotes, incrementing the sent count
        $response = $this->post('/api/quotes/refresh', [], ['X-TOKEN' => 'my-not-so-secret-token']);

        $response->assertStatus(200);
        $response->assertExactJson(['Quote 6', 'Quote 7', 'Quote 8', 'Quote 9', 'Quote 10']);
        Http::assertSentCount(10);

        // Subsequent request should use the newly cached values, and not sending additional requests
        $response = $this->get('/api/quotes', ['X-TOKEN' => 'my-not-so-secret-token']);

        $response->assertStatus(200);
        $response->assertExactJson(['Quote 6', 'Quote 7', 'Quote 8', 'Quote 9', 'Quote 10']);
        Http::assertSentCount(10);
    }
}
