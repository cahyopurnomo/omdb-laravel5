<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FavouriteModel;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class FavouriteController extends Controller
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
        // Ambil semua data favorit dari DB
        $favourites = FavouriteModel::all();

        $favouritesDetails = [];

        foreach ($favourites as $fav) {
            try {
                // Panggil OMDb API untuk setiap IMDb ID
                $response = $this->client->get('', [
                    'query' => [
                        'apikey' => $this->apiKey,
                        'i' => $fav->imdb_id,
                    ]
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                $favouritesDetails[] = [
                    'id' => $fav->id,
                    'imdb_id' => $fav->imdb_id,
                    'title' => $fav->title,
                    'year' => $fav->year,
                    'poster' => $fav->poster,
                    'omdb_detail' => $data
                ];
            } catch (\Exception $e) {
                // Jika gagal request, simpan data favorit saja
                $favouritesDetails[] = [
                    'id' => $fav->id,
                    'imdb_id' => $fav->imdb_id,
                    'title' => $fav->title,
                    'year' => $fav->year,
                    'poster' => $fav->poster,
                    'omdb_detail' => null,
                    'error' => $e->getMessage()
                ];
            }
        }

        return view('favourites.index', ['favourites' => $favouritesDetails]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'imdb_id' => 'required|string',
            'title'   => 'required|string',
            'year'    => 'nullable|string',
            'poster'  => 'nullable|string',
        ]);

        $fav = FavouriteModel::updateOrCreate(
            ['user_id' => Auth::id(), 'imdb_id' => $request->imdb_id],
            ['title' => $request->title, 'year' => $request->year, 'poster' => $request->poster]
        );

        return response()->json(['success' => true, 'data' => $fav]);
    }

    public function destroy($id)
    {
        FavouriteModel::where('user_id', Auth::id())
                ->where('imdb_id', $id)
                ->delete();

        return response()->json(['success' => true]);
    }

    public function list()
    {
        $uids = FavouriteModel::where('user_id', Auth::id())->pluck('imdb_id')->toArray();
        return response()->json(['success' => true, 'ids' => $uids]);
    }
}
