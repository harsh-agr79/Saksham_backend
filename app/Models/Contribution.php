<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'date', 'count'];

    public $timestamps = true;

    // Relationship with Student (Assuming Student Model Exists)
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
