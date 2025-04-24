<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WorkoutController extends Controller
{
    public function create()
    {
        $clients = User::where('role', 'user')->get();
        return view('workouts.create', compact('clients'));
    }

    public function store(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:users,id',
        'date' => 'required|date',
        'start_time' => 'required|date_format:H:i',
    ]);

    $start = Carbon::parse($request->start_time);
    $end = $start->copy()->addHour();

    $repeat = $request->has('repeat') ? 4 : 1;

    for ($i = 0; $i < $repeat; $i++) {
        $date = Carbon::parse($request->date)->addWeeks($i);

        $overlap = Workout::where('client_id', $request->client_id)
            ->where('date', $date)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('start_time', '<=', $start)
                            ->where('end_time', '>=', $end);
                      });
            })->exists();

        if ($overlap) {
            return back()->withErrors([
                'error' => "Overlap on " . $date->toDateString() . ". No workouts were scheduled."
            ]);
        }

        Workout::create([
            'trainer_id' => auth()->id(),
            'client_id' => $request->client_id,
            'date' => $date->toDateString(),
            'start_time' => $start,
            'end_time' => $end,
        ]);
    }

    return back()->with('success', $repeat > 1 ? 'Workout repeated for 4 weeks!' : 'Workout scheduled successfully.');
}

}
