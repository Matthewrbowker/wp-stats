<h1>Basic Stats</h1>
Total Performers: {{ $totalPerformers }}<br />
Most nominations: {{ $mostNominations->name }} - {{ $mostNominations->years_count }}<br />
Most Wins: {{ $mostWins->name }} - {{ $mostWins->years_count }}

<h1>Performers nominated multiple years</h1>
@foreach($performersWithMultipleYears as $performer)
    <li>{{ $performer->name }} ({{ $performer->year }})</li>
@endforeach

<h1>Performer breakdown</h1>
@foreach($performers as $performer)
    <h2>{{ $performer->name }} ({{ $performer->years_count }})</h2>
    <ul>
        @foreach($performer->years as $year)
            <li>
                {{ $year->year }}
                @if($year->won)
                    *
                @endif
            </li>
        @endforeach
    </ul>

@endforeach
