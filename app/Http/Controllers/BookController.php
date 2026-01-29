<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    public function index()
    {
        try {
            $response = Http::get('https://the-one-api.dev/v2/book?limit=100');
            
            if ($response->successful()) {
                $books = $response->json()['docs'] ?? [];
                $bookNames = array_map(function($book) {
                    return ['name' => $book['name']];
                }, $books);
                
                return response()->json($bookNames);
            }
            
            return response()->json(['error' => 'Failed to fetch books'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
