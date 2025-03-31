<?php

namespace App\Http\Controllers;

use App\Models\Games;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Games::all();
        return view('games.index',compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('games.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'levels'=>'required|numeric',
            'release'=>'required|date',
            'image'=>'required|image',
        ]);

        $game = Games::create($request->all());

        if($request->hasFile('image')){
            $nombre = $game->id.'.'.$request->file('image')->getClientOriginalExtension();
            $img = $request->file('image')->storeAs('img', $nombre, 'public');
            $game->image = 'img/'.$nombre;
            $game->save();
        }
                
        return redirect()->route('games.index')->with('success', 'Juego creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(Games $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Games $game)
    {
        return view('games.edit',compact('game'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Games $game)
    {
        $request->validate([
            'name'=>'required',
            'levels'=>'required|numeric',
            'release'=>'required|date',
        ]);

        if($request->hasFile('image')){
            Storage::disk('public')->delete($game->image);
            $nombre = $game->id.'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('public/img', $nombre);
            $game->image = '/img/'.$nombre;
            $game->save();
        }

        $game->update($request->input());
        return redirect()->route('games.index')->with('success', 'Juego actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Games $game)
    {
        Storage::disk('public')->delete($game->image);
        $game->delete();
        return redirect()->route('games.index')->with('success', 'Juego eliminado');
    }
}