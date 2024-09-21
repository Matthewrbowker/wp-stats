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

        $performers = Performer::withCount('years')->orderBy('years_count', 'desc')->get();

        $performersWithMultipleYears = Performer::join('years', 'performers.id', '=', 'years.performer_id')->select('performers.*', 'years.year')->groupBy('performers.id', 'years.year')->havingRaw('COUNT(*) > 1')->get();

        return view('index', [
            'totalPerformers' => $totalPerformers,
            'mostNominations' => $mostNominations,
            'mostWins' => $mostWins,
            'performersWithMultipleYears' => $performersWithMultipleYears,
            'performers' => $performers
        ]);
    }
}
