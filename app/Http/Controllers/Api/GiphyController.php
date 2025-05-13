<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Favorite;

class GiphyController extends Controller
{
    /**
     * Handle an incoming search request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // Fetch the GIFs from Giphy API
        $response = Http::get('https://api.giphy.com/v1/gifs/search', [
            'api_key' => env('GIPHY_KEY'),
            'q' => $request->input('query'),
            'limit' => $request->input('limit', 10),
            'offset' => $request->input('offset', 0)
        ]);
        // Check if the response is successful
        if ($response->successful())
        {
            // Return the GIFs as a JSON response
            return response()->json($response->json());
        }
        // If the response is not successful, return an error response
        return response()->json(['error' => 'Failed to fetch data from Giphy'], $response->status());
    }

    /**
     * Handle an incoming search by ID request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function searchById($id)
    {
        // Fetch the GIF by ID from Giphy API
        $response = Http::get('https://api.giphy.com/v1/gifs/' . $id, [
            'api_key' => env('GIPHY_KEY'),
        ]);
        // Check if the response is successful
        if ($response->successful())
        {
            return response()->json($response->json());
        }
        // If the response is not successful, return an error response
        return response()->json(['error' => 'Failed to fetch data from Giphy'], $response->status());
    }

    /**
     * Handle an incoming trending request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveFavorite(Request $request)
    {
        // Validate the request
        $request->validate([
            'gif_id' => 'required|string',
            'alias' => 'required|string'
        ]);

        // Save the favorite GIF to the database
        $favorite = Favorite::create([
            'gif_id' => $request->gif_id,
            'user_id' => auth()->id(), //Get the authenticated user ID
            'alias' => $request->alias
        ]);
        // Check if the favorite was saved successfully
        if(!$favorite)
        {
            // If saving the favorite fails, return an error response
            return response()->json(['message' => 'Failed to save favorite GIF'], 500);
        }
        // Return a success response
        return response()->json(['message' => 'GIF saved as favorite successfully', 'favorite' => $favorite], 201);
    }
}
