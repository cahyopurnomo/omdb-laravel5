<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MovieController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OMDB_API_KEY');
        $this->client = new Client([
            'base_uri' => 'http://www.omdbapi.com/',
            'timeout'  => 8.0,
        ]);

    }

    public function index()
    {
        // Halaman pertama, kita load dari AJAX nantinya
        return view('movies.index');
    }

    public function loadMore(Request $request)
    {
        $page = $request->get('page', 1);
        $search = $request->get('search', 'Avengers'); // default pencarian

        $response = $this->client->get('', [
            'query' => [
                'apikey' => $this->apiKey,
                's'      => $search,
                'page'   => $page
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        return response()->json([
            'movies' => $data['Search'] ?? [],
            'totalResults' => $data['totalResults'] ?? 0
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', 'batman');
        $year  = $request->input('y', '');
        $type  = $request->input('type', '');
        $page  = $request->input('page', 1);

        $params = [
            'apikey' => $this->apiKey,
            's'      => $query,
            'page'   => $page
        ];

        if (!empty($year)) {
            $params['y'] = $year;
        }

        if (!empty($type)) {
            $params['type'] = $type;
        }

        $response = $this->client->get('', [
            'query' => $params
        ]);

        return response($response->getBody(), 200)->header('Content-Type', 'application/json');
    }

    public function show($id)
    {
        $response = $this->client->get('', [
            'query' => [
                'apikey' => $this->apiKey,
                'i'      => $id,
                'plot'   => 'full'
            ]
        ]);

        $movie = json_decode($response->getBody(), true);

        if (isset($movie['Response']) && $movie['Response'] === 'True') {
            return view('movies.show', compact('movie'));
        }

        abort(404);
    }
}
