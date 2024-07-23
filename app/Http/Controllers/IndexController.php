<?php

namespace App\Http\Controllers;

use App\Models\Performer;

class IndexController extends Controller
{
    public function index()
    {
        $mostNominations = Performer::withCount('years')->orderBy('years_count', 'desc')->first();

        $mostWins = Performer::withCount(
            ['years'=> function($query) {$query->where('won', true);}])->orderBy('years_count', 'desc')->first();;

        return view('index', [
            'mostNominations' => $mostNominations,
            'mostWins' => $mostWins
        ]);
    }
}
