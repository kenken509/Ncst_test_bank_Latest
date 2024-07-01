<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\ProblemSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProblemSetController extends Controller
{
    public function store(Request $request)
    {
        // content:'',
        // subject_code_id:'',
        // term:'',

        $existingProblemSet = ProblemSet::where('subject_code_id',$request->subject_code_id)->where('term',$request->term)->first();

        $request->validate([
            'content' => 'required|string|max:225',
        ]);
        
        try
        {
            DB::beginTransaction();

            if($existingProblemSet)
            {
                $existingProblemSet->content            = $request->content;
                $existingProblemSet->subject_code_id    = $request->subject_code_id;
                $existingProblemSet->term               = $request->term;
                $existingProblemSet->save();
            }
            else
            {
                $problemSet = new ProblemSet();

                $problemSet->content            = $request->content;
                $problemSet->subject_code_id    = $request->subject_code_id;
                $problemSet->term               = $request->term;
                $problemSet->save();
            }

            //throw new \Exception('simulated error');
            DB::commit();
            return redirect()->back()->with('success', 'Successfully stored new problem set.');
        }
        catch(Exception $e)
        {
            DB::rollback();

            Log::error('error creating problem set: '.$e->getMessage() );
            return redirect()->back()->with('error', 'Failed to store new problem set.');
        }
        
    }
}
