<?php

namespace App\Controllers;

use App\Models\Game;
use Core\Controller;
use Exception;

class GameController extends Controller
{
    public function index()
    {
        // Code for index method
        $games = Game::all();
        
        return view('games', compact('games'));
    }

    public function show($id)
    {
        // return 
    }
}