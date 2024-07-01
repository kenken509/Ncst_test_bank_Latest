<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectCode extends Model
{
    use HasFactory;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function problemSets()
    {
        return $this->hasMany(ProblemSet::class);
    }
}
