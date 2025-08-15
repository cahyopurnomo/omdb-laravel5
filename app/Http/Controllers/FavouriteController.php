<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FavouriteModel;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function index()
    {
        $favourites = FavouriteModel::updateOrChere('user_id', Auth::id())->get();
        return view('favourites.index', compact('favourites'));
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
