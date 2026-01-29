<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookControllerTest extends TestCase
{
    /**
     * Test that the books endpoint returns successfully with valid data
     */
    public function test_index_returns_successful_response_with_books(): void
    {
        // Arrange: Mock the external API response
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'docs' => [
                    ['_id' => '1', 'name' => 'The Fellowship Of The Ring'],
                    ['_id' => '2', 'name' => 'The Two Towers'],
                    ['_id' => '3', 'name' => 'The Return Of The King'],
                ]
            ], 200)
        ]);

        // Act: Make request to our endpoint
        $response = $this->get('/api/books');

        // Assert: Check response structure and data
        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJson([
                     ['name' => 'The Fellowship Of The Ring'],
                     ['name' => 'The Two Towers'],
                     ['name' => 'The Return Of The King'],
                 ]);
    }

    /**
     * Test that only the 'name' field is returned (not full book data)
     */
    public function test_index_returns_only_book_names(): void
    {
        // Arrange
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'docs' => [
                    [
                        '_id' => '5cf5805fb53e011a64671582',
                        'name' => 'The Fellowship Of The Ring',
                        'author' => 'J.R.R. Tolkien',
                        'year' => 1954,
                    ],
                ]
            ], 200)
        ]);

        // Act
        $response = $this->get('/api/books');

        // Assert: Only 'name' should be in response
        // Test the JSON
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     ['name']
                 ])
                 ->assertJsonMissing(['author', 'year', '_id']);

        // Test the decoded Array
        $data = $response->json();
        $this->assertArrayHasKey('name', $data[0]);
        $this->assertArrayNotHasKey('_id', $data[0]);
        $this->assertArrayNotHasKey('author', $data[0]);
    }

    /**
     * Test handling of empty response from API
     */
    public function test_index_handles_empty_book_list(): void
    {
        // Arrange: API returns no books
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'docs' => []
            ], 200)
        ]);

        // Act
        $response = $this->get('/api/books');

        // Assert: Should return empty array
        $response->assertStatus(200)
                 ->assertJson([]);
    }

    /**
     * Test handling when API returns malformed data (missing 'docs' key)
     */
    public function test_index_handles_malformed_api_response(): void
    {
        // Arrange: API returns data without 'docs' key
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'error' => 'Something went wrong'
            ], 200)
        ]);

        // Act
        $response = $this->get('/api/books');

        // Assert: Should return empty array (due to ?? [] fallback)
        $response->assertStatus(200)
                 ->assertJson([]);
    }

    /**
     * Test handling of API server error (500 response)
     */
    public function test_index_handles_api_server_error(): void
    {
        // Arrange: External API returns 500 error
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'error' => 'Internal Server Error'
            ], 500)
        ]);

        // Act
        $response = $this->get('/api/books');

        // Assert: Our endpoint should return 500 with error message
        // Does not expose too much info
        $response->assertStatus(500)
                 ->assertJson([
                     'error' => 'Failed to fetch books'
                 ]);
    }

    /**
     * Test handling of API timeout/network error
     */
    public function test_index_handles_network_failure(): void
    {
        // Arrange: Simulate network failure
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => function () {
                throw new \Exception('Connection timeout');
            }
        ]);

        // Act
        $response = $this->get('/api/books');

        // Assert: Should return error response
        $response->assertStatus(500)
                 ->assertJsonStructure(['error']);
    }

    /**
     * Test that the correct API URL is called
     */
    public function test_index_calls_correct_api_endpoint(): void
    {
        // Arrange
        Http::fake();

        // Act
        $this->get('/api/books');

        // Assert: Verify the correct URL was called with limit parameter
        Http::assertSent(function ($request) {
            return $request->url() === 'https://the-one-api.dev/v2/book?limit=100';
        });
    }

    /**
     * Test response headers are JSON
     */
    public function test_index_returns_json_content_type(): void
    {
        // Arrange
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'docs' => [
                    ['name' => 'Test Book']
                ]
            ], 200)
        ]);

        // Act
        $response = $this->get('/api/books');

        // Assert
        $response->assertHeader('Content-Type', 'application/json');
    }

    /**
     * Test with large dataset
     */
    public function test_index_handles_large_dataset(): void
    {
        // Arrange: Create 100 books
        $books = [];
        for ($i = 1; $i <= 100; $i++) {
            $books[] = ['_id' => (string)$i, 'name' => "Book $i"];
        }

        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'docs' => $books
            ], 200)
        ]);

        // Act
        $response = $this->get('/api/books');

        // Assert
        $response->assertStatus(200)
                 ->assertJsonCount(100);
        
        $data = $response->json();
        $this->assertEquals('Book 1', $data[0]['name']);
        $this->assertEquals('Book 100', $data[99]['name']);
    }

    /**
     * Test that books with special characters in names are handled correctly
     */
    public function test_index_handles_special_characters_in_names(): void
    {
        // Arrange
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'docs' => [
                    ['name' => 'The Silmarillion & Other Tales'],
                    ['name' => "Farmer Giles of Ham's Adventure"],
                    ['name' => 'Book with "Quotes"'],
                ]
            ], 200)
        ]);

        // Act
        $response = $this->get('/api/books');

        // Assert
        $response->assertStatus(200)
                 ->assertJson([
                     ['name' => 'The Silmarillion & Other Tales'],
                     ['name' => "Farmer Giles of Ham's Adventure"],
                     ['name' => 'Book with "Quotes"'],
                 ]);
    }
}
