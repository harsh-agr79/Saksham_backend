<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeSubmission extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'challenge_id', 'code', 'status', 'execution_time'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function challenge()
    {
        return $this->belongsTo(CodingChallenge::class, 'challenge_id');
    }
}
