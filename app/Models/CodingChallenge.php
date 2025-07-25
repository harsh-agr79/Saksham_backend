<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodingChallenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'startdatetime',
        'enddatetime',
        'testcase',
        'tc_answer',
    ];

    protected $casts = [
        'description' => 'string',
        'testcase' => 'string',
        'tc_answer' => 'string',
    ];

    public function challengeSubmissions()
    {
        return $this->hasMany(ChallengeSubmission::class, 'challenge_id');
    }
}
