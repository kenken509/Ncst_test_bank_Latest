<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        $adminsCount    = User::where('role','admin')->orWhere('role','co-admin')->count();
        $depHeadCount   = User::where('role','department head')->count();
        $facultyCount   = User::where('role', 'faculty')->count();
        $questionsCount = Question::all()->count();
        
        $userQuestionCount = User::withCount('questions')
                                ->orderBy('questions_count','asc')
                                ->take(5)
                                ->get();
        
        return inertia('Dashboard/Dashboard/Dashboard',[
            'adminsCount'   => $adminsCount,
            'depHeadCount'  => $depHeadCount,
            'facultyCount'  => $facultyCount,
            'questionsCount'=> $questionsCount,
            'userQuestionCount' => $userQuestionCount
        ]);
    }

    
}
