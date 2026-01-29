<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

/**
 * Deals with books about the Lord of the Rings.
 */
class BookController extends Controller
{
    /**
     * Returns the Lord of the Ring book titles from the the-one-api.dev.
     *
     * @return JsonResponse
     */
    final public function index(): JsonResponse
    {
        try {
            $response = Http::get('https://the-one-api.dev/v2/book?limit=100');

            // Returns the names of the Lord of the Rings books from the api.
            if ($response->successful()) {
                $books = $response->json()['docs'] ?? [];
                $bookNames = array_map(function($book) {
                    return ['name' => $book['name']];
                }, $books);
                
                return response()->json($bookNames);
            }

            // Handles API errors.
            return response()->json(['error' => 'Failed to fetch books'], 500);
        } catch (\Exception $e) {

            // Handles code errors.
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
