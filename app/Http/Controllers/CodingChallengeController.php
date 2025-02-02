<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CodingChallenge;
use App\Models\ChallengeSubmission;

class CodingChallengeController extends Controller
{
    public function getValidChallenges(Request $request) {
        $now = Carbon::now();
        $studentId = $request->user()->id; // Get authenticated student ID
    
        // Get challenge IDs the student has already submitted
        $submittedChallengeIds = ChallengeSubmission::where('student_id', $studentId)
                                                    ->pluck('challenge_id')
                                                    ->toArray();
    
        // Get valid challenges excluding submitted ones
        $validChallenges = CodingChallenge::where('startdatetime', '<=', $now)
                                          ->where('enddatetime', '>=', $now)
                                          ->whereNotIn('id', $submittedChallengeIds) // Exclude submitted challenges
                                          ->get();
    
        return response()->json($validChallenges, 200);
    }
    
    public function getChallenge(Request $request, $id) {
        $now = Carbon::now();
        $studentId = $request->user()->id; // Get authenticated student ID
    
        // Check if the student has already submitted
        $hasSubmitted = ChallengeSubmission::where('challenge_id', $id)
                                           ->where('student_id', $studentId)
                                           ->exists();
    
        // If submitted, return a 403 Forbidden response
        if ($hasSubmitted) {
            return response()->json(['message' => 'You have already submitted this challenge.'], 403);
        }
    
        // Get the challenge if it's valid
        $validChallenge = CodingChallenge::where('startdatetime', '<=', $now)
                                          ->where('enddatetime', '>=', $now)
                                          ->where('id', $id)
                                          ->first();
    
        return response()->json($validChallenge, 200);
    }
    
    

    public function submitChallenge(Request $request)
    {
        // Validate the request
        $request->validate([
            'challenge_id' => 'required|exists:coding_challenges,id',
            'code' => 'required|string',
        ]);

        // Get the authenticated student
        $student = $request->user();

        if (!$student) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        // Get the challenge to ensure it's active
        $challenge = CodingChallenge::findOrFail($request->challenge_id);
        $currentTime = now();

        if ($currentTime < $challenge->startdatetime || $currentTime > $challenge->enddatetime) {
            return response()->json(['error' => 'Challenge is not active'], 403);
        }

        // Create submission
        $submission = ChallengeSubmission::create([
            'student_id' => $student->id,
            'challenge_id' => $challenge->id,
            'code' => $request->code,
            'status' => 'pending', // Default status
            'execution_time' => null, // To be updated after evaluation
        ]);

        return response()->json([
            'message' => 'Challenge submitted successfully!',
            'submission' => $submission
        ], 201);
    }
}
