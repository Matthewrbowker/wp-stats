<?php

namespace App\Http\Controllers;

use App\Models\Performer;

class IndexController extends Controller
{
    public function index()
    {
        $totalPerformers = Performer::count();

        $mostNominations = Performer::withCount('years')->orderBy('years_count', 'desc')->first();

        $mostWins = Performer::withCount(
            ['years'=> function($query) {$query->where('won', true);}])->orderBy('years_count', 'desc')->first();;

        return view('index', [
            'totalPerformers' => $totalPerformers,
            'mostNominations' => $mostNominations,
            'mostWins' => $mostWins
        ]);
    }
}
