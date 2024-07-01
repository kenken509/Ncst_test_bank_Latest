<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function subjectCode()
    {
        return $this->belongsTo(SubjectCode::class);
    }

    
}
