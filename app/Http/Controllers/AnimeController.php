<?php
namespace App\Http\Controllers;

use App\Models\Anime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AnimeController extends Controller
{
    public function show($slug)
    {
        $anime = Anime::where('slug', $slug)->first();

        if (!$anime) {
            return response()->json(['error' => 'Anime not found'], 404);
        }

        return response()->json($anime);
    }
}