<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class BookControllerUnitTest extends TestCase
{
    private BookController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new BookController();
    }

    /**
     * Test that the controller correctly transforms API data
     */
    public function test_controller_transforms_api_data_correctly(): void
    {
        // Arrange
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'docs' => [
                    [
                        '_id' => '5cf5805fb53e011a64671582',
                        'name' => 'The Fellowship Of The Ring',
                        'author' => 'J.R.R. Tolkien',
                        'year' => 1954
                    ],
                    [
                        '_id' => '5cf58077b53e011a64671583',
                        'name' => 'The Two Towers',
                        'author' => 'J.R.R. Tolkien',
                        'year' => 1954
                    ],
                ]
            ], 200)
        ]);

        // Act
        $response = $this->controller->index();

        // Assert
        $data = json_decode($response->getContent(), true);
        
        $this->assertCount(2, $data);
        $this->assertEquals('The Fellowship Of The Ring', $data[0]['name']);
        $this->assertEquals('The Two Towers', $data[1]['name']);
        
        // Ensure only 'name' field is present
        $this->assertArrayHasKey('name', $data[0]);
        $this->assertArrayNotHasKey('_id', $data[0]);
        $this->assertArrayNotHasKey('author', $data[0]);
        $this->assertArrayNotHasKey('year', $data[0]);
    }

    /**
     * Test controller returns 500 on API failure
     */
    public function test_controller_returns_error_on_api_failure(): void
    {
        // Arrange
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([], 500)
        ]);

        // Act
        $response = $this->controller->index();

        // Assert
        $this->assertEquals(500, $response->status());
        
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Failed to fetch books', $data['error']);
    }

    /**
     * Test controller handles exception properly
     */
    public function test_controller_handles_exception(): void
    {
        // Arrange: Force an exception
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => function () {
                throw new \Exception('Network error occurred');
            }
        ]);

        // Act
        $response = $this->controller->index();

        // Assert
        $this->assertEquals(500, $response->status());
        
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Network error occurred', $data['error']);
    }

    /**
     * Test that array_map is used correctly to extract names
     */
    public function test_controller_extracts_only_names_from_books(): void
    {
        // Arrange
        $testBooks = [
            ['_id' => '1', 'name' => 'Book 1', 'extra' => 'data1'],
            ['_id' => '2', 'name' => 'Book 2', 'extra' => 'data2'],
            ['_id' => '3', 'name' => 'Book 3', 'extra' => 'data3'],
        ];

        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'docs' => $testBooks
            ], 200)
        ]);

        // Act
        $response = $this->controller->index();

        // Assert
        $data = json_decode($response->getContent(), true);
        
        foreach ($data as $book) {
            $this->assertCount(1, array_keys($book), 'Each book should only have the name key');
            $this->assertArrayHasKey('name', $book);
        }
    }

    /**
     * Test controller response is valid JSON
     */
    public function test_controller_returns_valid_json(): void
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
        $response = $this->controller->index();

        // Assert
        $this->assertJson($response->getContent());
        
        $decoded = json_decode($response->getContent(), true);
        $this->assertNotNull($decoded);
        $this->assertIsArray($decoded);
    }

    /**
     * Test handling of null or missing docs array
     */
    public function test_controller_handles_missing_docs_array(): void
    {
        // Arrange: API response without 'docs' key
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'message' => 'Success',
                'count' => 0
            ], 200)
        ]);

        // Act
        $response = $this->controller->index();

        // Assert: Should return empty array due to ?? [] fallback
        $this->assertEquals(200, $response->status());
        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }

    /**
     * Test that HTTP client uses GET method
     */
    public function test_controller_uses_http_get_method(): void
    {
        // Arrange
        Http::fake();

        // Act
        $this->controller->index();

        // Assert
        Http::assertSent(function ($request) {
            return $request->method() === 'GET' &&
                   $request->url() === 'https://the-one-api.dev/v2/book?limit=100';
        });
    }

    /**
     * Test controller preserves book name order from API
     */
    public function test_controller_preserves_book_order(): void
    {
        // Arrange
        Http::fake([
            'https://the-one-api.dev/v2/book?limit=100' => Http::response([
                'docs' => [
                    ['name' => 'Third Book'],
                    ['name' => 'First Book'],
                    ['name' => 'Second Book'],
                ]
            ], 200)
        ]);

        // Act
        $response = $this->controller->index();

        // Assert: Order should be preserved as returned by API
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Third Book', $data[0]['name']);
        $this->assertEquals('First Book', $data[1]['name']);
        $this->assertEquals('Second Book', $data[2]['name']);
    }
}
