<?php

namespace App\Models;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Division extends Model
{
    use HasFactory;

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function faculties()
    {
        return $this->hasMany(User::class);
    }
    

    public function divSubjectCodes()
    {
        return $this->hasMany(SubJectCode::class);
    }
}
