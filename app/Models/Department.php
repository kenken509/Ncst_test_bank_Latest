<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function departmentHeads()
    {
        return $this->hasMany(User::class);
    }

    public function faculty()
    {
        return $this->hasMany(User::class);
    }

    public function divisions()
    {
        return $this->hasMany(Division::class);
    }

    public function subjectCodes()
    {
        return $this->hasMany(SubjectCode::class);
    }
}
