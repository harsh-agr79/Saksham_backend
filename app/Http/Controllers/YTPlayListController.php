<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ytPlayLists;

class YTPlayListController extends Controller
{
    public function getytplaylists() {
        $playlists = ytPlayLists::all();
    
        // Modify the URL field for each playlist to extract only the playlist ID
        $playlists->transform(function ($playlist) {
            if (preg_match('/list=([^&]+)/', $playlist->url, $matches)) {
                $playlistId = $matches[1];
                $playlist->url = urlencode($playlistId); // Encode it for use in Next.js
            }
    
            return $playlist;
        });
    
        return response()->json($playlists, 200);
    }    
    
}
