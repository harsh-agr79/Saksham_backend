<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contribution;
use Carbon\Carbon;

class ContributionController extends Controller
{
    public function incrementContribution(Request $request)
    {
        // Get the authenticated student
        $student = $request->user();

        if (!$student) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get today's date
        $today = now()->toDateString();

        // Find or create a contribution entry for today
        $contribution = Contribution::firstOrCreate(
            ['student_id' => $student->id, 'date' => $today],
            ['count' => 0]
        );

        // Increment the count
        $contribution->increment('count');

        return response()->json([
            'message' => 'Contribution updated successfully',
            'contribution' => $contribution
        ], 200);
    }

    public function getContributions(Request $request)
    {
        // Get today's date and the date one year ago
        $today = Carbon::now();
        $oneYearAgo = $today->subYear(); // This gives you the date one year ago

        // Query contributions for the authenticated user within the last year, selecting only the 'date' and 'count' columns
        $contributions = Contribution::where('student_id', $request->user()->id)
                                    ->where('date', '>=', $oneYearAgo)
                                    ->select('date', 'count') // Select only the 'date' and 'count' columns
                                    ->get();

        // Return the contributions
        return response()->json($contributions);
    }
}
