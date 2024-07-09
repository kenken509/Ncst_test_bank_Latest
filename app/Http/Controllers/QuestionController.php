<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\ProblemSet;
use App\Models\SubjectCode;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class QuestionController extends Controller
{
    public function showQuestions()
    {
        // user rolse admin, co-admin, department head, faculty
        $loggedUser = Auth::user();
        $problemSet = [];
        $questions = Question::with(['options', 'author', 'subjectCode'])->latest()->get();

        $currentDate = Carbon::today()->toDateString();
        
        $announcements = DB::table('announcements')
                    ->whereRaw('STR_TO_DATE(start_date, "%Y-%m-%d") >= ?', [$currentDate])
                    ->whereRaw('STR_TO_DATE(end_date, "%Y-%m-%d") >= ?', [$currentDate])
                    ->get();

        if($loggedUser->role == 'admin' || $loggedUser->role == 'co-admin')
        {
            $subjectCodes = SubjectCode::with(['questions' => function ($query){
                $query->with(['author','options']);
            }])->latest()->get();
            
            $problemSet = ProblemSet::all();

            $announcements = Announcement::orderBy('marking','desc')->get();
        }

        if($loggedUser->role == 'department head')
        {
            
            $subjectCodes = SubjectCode::with(['questions' => function ($query){
                $query->with(['author','options']);
            }])->where('department_id', $loggedUser->department_id)->latest()->get();
            
            
        }

        if($loggedUser->role == 'faculty')
        {
            $hasDivision = $loggedUser->division_id;
            
            if($hasDivision)
            {
                $subjectCodes = SubjectCode::with(['questions' => function ($query){
                    $query->with(['author','options']);
                }])->where('division_id', $loggedUser->division_id)->latest()->get();
            }
            else
            {
                
                $subjectCodes = SubjectCode::with(['questions' => function ($query){
                    $query->with(['author','options']);
                }])->where('department_id', $loggedUser->department_id)->latest()->get();

                
            }
            
        }
        
        return inertia('Dashboard/Questions/QuestionAll', [
            'subjectCodes'      => $subjectCodes,
            'problemSets'       => $problemSet,
            'announcements'     => $announcements,
        ]);
    }

    public function showAddQuestion()
    {
        $loggedUser = Auth::user();
        
        if($loggedUser->role == 'admin' || $loggedUser->role == 'co-admin')
        {
            $subjectCodes = SubjectCode::orderBy('id','asc')->get();
        }
        
        if($loggedUser->role == 'department head')
        {
            $subjectCodes = SubjectCode::where('department_id', $loggedUser->department_id)->orderBy('id','asc')->get();
        }

        if($loggedUser->role == 'faculty')
        {
            $hasDivision = $loggedUser->division_id;

            if($hasDivision)
            {
                $subjectCodes = SubjectCode::where('division_id', $loggedUser->division_id)->orderBy('id','asc')->get();
            }
            else
            {
                $subjectCodes = SubjectCode::where('department_id', $loggedUser->department_id)->orderBy('id','asc')->get();
            }
            
        }

        return inertia('Dashboard/Questions/QuestionAdd',[
            'subjectCodes' => $subjectCodes,
        ]);
    }

    public function storeQuestion(Request $request)
    {
        
        $randomNumber = rand(1,10000000);
        $time = now();
        
        //return redirect()->back()->with('success', 'successfull post.'.$randomNumber.$time);
        // const form = useForm({
        //     question:'',
        //     type:'text',
        //     term: '',
        //     attached_image:'',
        //     term:'',
        //     subject_code_id:'',
        //     author_id:user.id,
        //     options:[],
        // })
       
        if($request->type == 'text')
        {
            // check if there is an attached image file, if yes then save the to images storage link and save the data to database else save the data to database, save each option
            // to related options
            //dd($request);

            try{
                DB::beginTransaction();

                if ($request->hasFile('attached_image')) {
                    $file = $request->file('attached_image');
                    $path = $file->store('Images', 'public');
                    $imagePath = basename($path); // Get only the filename
                } else {
                    $imagePath = null;
                }
                
                $imageFiles=[$imagePath];
                $question = new Question();
                $question->question         = $request->question;
                $question->type             = $request->type;
                $question->term             = $request->term;
                $question->attached_image   = $imagePath;
                $question->subject_code_id  = $request->subject_code_id;
                $question->author_id        = $request->author_id;
                $question->save();

                // throw new \Exception('simulated error');
                //dd($request);
                // Save each option related to the question
                foreach ($request->options as $option) {
                    
                    $questionOption                 = new Option();
                    $questionOption->option         = $option['option'];
                    // Convert boolean to string 'true' or 'false'
                    $questionOption->isCorrect      = $option['isCorrect'] ? 'true' : 'false'; //$questionOption->isCorrect      = $option['isCorrect'];
                    $questionOption->question_id    = $question->id;
                    $questionOption->save();
                }

                DB::commit();
                return redirect()->route('questions.show')->with('success', 'Successfully created new question.');
            }catch (\Exception $e)
            {
                DB::rollback();
                Log::error('error creating new question: '.$e->getMessage());
                
                // Delete all images in the array
                foreach ($imageFiles as $filename) {
                    Storage::disk('public')->delete('Images/' . $filename);
                }

                return redirect()->back()->with('error', 'Failed to create new question.');
            }
            
        }

        if($request->type == 'image')
        {
            if($request->hasFile('attached_image'))
            {
                
                try
                {
                    DB::beginTransaction();

                    // save the attached image 
                    $file = $request->file('attached_image');
                    $path = $file->store('Images', 'public');
                    $imagePath = basename($path);
                    
                     // Array to store filenames of images
                    $imageFiles = [$imagePath];

                    $question = new Question();
                    $question->question         = $request->question;
                    $question->type             = $request->type;
                    $question->attached_image   = $imagePath;
                    $question->term             = $request->term;
                    $question->subject_code_id  = $request->subject_code_id;
                    $question->author_id        = $request->author_id;
                    $question->save();

                    foreach($request->options as $option)
                    {
                       
                        $optionImage    = $option['option'];
                        $path           = $optionImage->store('Images','public');
                        $imagePath      = basename($path);

                        // Add option image filename to the array
                        $imageFiles[] = $imagePath;

                        $newOption = new Option();
                        $newOption->option      = $imagePath;
                        $newOption->isCorrect   = $option['isCorrect'] ? 'true': 'false';
                        $newOption->question_id = $question->id;
                        $newOption->save();

                    }

                    // Simulate an error
                    //throw new \Exception('Simulated error after saving the attached image');

                    DB::commit();
                    return redirect()->route('questions.show')->with('success', 'Successfully created new question.');
                
                }
                catch (\Exception $e)
                {
                    DB::rollback();
                    Log::error('error saving image question: '.$e->getMessage());
                    
                    // Delete all images in the array
                    foreach ($imageFiles as $filename) {
                        Storage::disk('public')->delete('Images/' . $filename);
                    }

                    return redirect()->back()->with('error', 'Failed to create new question');
                }
                
            }
            else
            {
                try
                {
                    DB::beginTransaction();

                    $imageFiles = [];
                    $question = new Question();

                    $question->question         = $request->question;
                    $question->type             = $request->type;
                    $question->term             = $request->term;
                    $question->subject_code_id  = $request->subject_code_id;
                    $question->author_id        = $request->author_id;
                    $question->save();

                    foreach($request->options as $option)
                    {
                        $optionImage    = $option['option'];
                        $path           = $optionImage->store('Images','public');
                        $imagePath      = basename($path);

                        // Add option image filename to the array
                        $imageFiles[] = $imagePath;

                        $newOption = new Option();
                        $newOption->option      = $imagePath;
                        $newOption->isCorrect   = $option['isCorrect'] ? 'true': 'false';
                        $newOption->question_id = $question->id;
                        $newOption->save();
                    }

                    //throw new \Exception('simulated error');
                    DB::commit();
                    return redirect()->route('questions.show')->with('success','Successfully created new question.');
                    
                }
                catch(\Exception $e)
                {
                    DB::rollback();
                    Log::error('error creating new question without image_attachment: '.$e->getMessage());

                    foreach($imageFiles as $filename)
                    {
                        Storage::disk('public')->delete('Images/' . $filename);
                    }
                    
                    return redirect()->back()->with('error', 'Failed to create new question.');
                }
            }
        }
    }
    
    public function storeQuestionModal(Request $request)
    {
        dd('im here');
        return redirect()->back()->with('success','test success');
    }


    public function showUpdate($id)
    {
        
        // only admin, coadmin, dephead is allowed to update
        // user rolse admin, co-admin, department head, faculty
        $loggedUser = Auth::user();

        $question = Question::with('subjectCode','options')->findOrFail($id);
        

        if($loggedUser->role == 'admin' || $loggedUser->role == 'co-admin')
        {
            $subjectCodes = SubjectCode::orderBy('id','asc')->get();
        }
        
        if($loggedUser->role == 'department head')
        {
            $subjectCodes = SubjectCode::where('department_id', $loggedUser->department_id)->orderBy('id','asc')->get();
        }

        return inertia('Dashboard/Questions/QuestionUpdate', [
            'subjectCodes'  => $subjectCodes,
            'question'      => $question
        ]);
    }

    public function update(Request $request)
    {
        
        //dd($request);
        //dd($request->attached_image);
        // question_id:data.question.id,
        // question:data.question.question,
        // type:data.question.type,
        // term: data.question.term,
        // attached_image:'',
        // hasExistingAttached_image:data.question.attached_image ? 'true':'false',
        // subject_code_id:'',
        // editor_id:user.id,
        // options:[],
       $questionToUpdate = Question::with('options')->findOrFail($request->question_id);
    
        
        if($request->type == 'text')
        {
            if($questionToUpdate->attached_image)
            {  
                // existing attached image not changed
                if($request->attached_image == $questionToUpdate->attached_image)
                { 
                    try
                    {
                        DB::beginTransaction();
                        $questionToUpdate->question         = $request->question;
                        $questionToUpdate->type             = $request->type;
                        $questionToUpdate->term             = $request->term;
                        $questionToUpdate->subject_code_id  = $request->subject_code_id;
                        $questionToUpdate->editor_id        = $request->editor_id;
                        $questionToUpdate->save();
                    
                        foreach($request->options as $option)
                        {
                            $answer = '';
                            if($option['isCorrect']=='true')
                            {
                                $answer = 'true';
                            }
                            else
                            {
                                $answer = 'false';
                            }

                            $optionToUpdate = Option::findOrFail($option['option_id']);

                            $optionToUpdate->option = $option['option'];
                            $optionToUpdate->isCorrect = $answer;
                            $optionToUpdate->save();
                        }

                        DB::commit();
                        return redirect()->route('questions.show')->with('success', 'Question updated successfully.');
                    }
                    catch(\Exception $e)
                    {
                        DB::rollback();
                        Log::error('error updating question 1: '.$e->getMessage());

                        return redirect()->back()->with('error','Failed to updated question.');
                    }
                    
                }
                else
                {
                    //attached image replaced
                    if($request->hasFile('attached_image'))
                    {
                        $tempImage = [];
                        try
                        {
                            DB::beginTransaction();
                            $filename = $questionToUpdate->attached_image;
                                    
                            $file = $request->file('attached_image');
                            $path = $file->store('Images','public');
                            $imagePath = basename($path);

                            $tempImage[] = $imagePath;

                            $questionToUpdate->attached_image   = $imagePath;
                            $questionToUpdate->question         = $request->question;
                            $questionToUpdate->type             = $request->type;
                            $questionToUpdate->term             = $request->term;
                            $questionToUpdate->subject_code_id  = $request->subject_code_id;
                            $questionToUpdate->editor_id        = $request->editor_id;
                            $questionToUpdate->save();

                            foreach($request->options as $option)
                            {
                                $answer = '';
                                if($option['isCorrect']=='true')
                                {
                                    $answer = 'true';
                                }
                                else
                                {
                                    $answer = 'false';
                                }

                                $optionToUpdate = Option::findOrFail($option['option_id']);

                                $optionToUpdate->option = $option['option'];
                                $optionToUpdate->isCorrect = $answer;
                                $optionToUpdate->save();
                            }
                                DB::commit();
                                Storage::disk('public')->delete('Images/' . $filename);
                                return redirect()->route('questions.show')->with('success', 'Question updated successfully.');
                            }
                            catch(\Exception $e)
                            {
                                DB::rollback();
                                Log::error('error updating question 2: '.$e->getMessage);

                                foreach($tempImage as $fileName)
                                {
                                    if(Storage::exists($fileName))
                                    {
                                        Storage::delete($fileName);
                                    }
                                }

                                return redirect()->back()->with('error', 'Failed to update the question.');
                            }
                        

                    }
                    else
                    {
                        dd('attached image was deleted.');
                    }
                };
            }
            else
            {
                // no existing image but request has image
                $tempAttachedImage = [];
                if($request->hasFile('attached_image'))
                {
                   try
                   {
                        DB::beginTransaction();
                        $file        = $request->file('attached_image');
                        $path        = $file->store('Images','public');
                        $imagePath   = basename($path);

                        $tempAttachedImage[] = $imagePath;

                        $questionToUpdate->attached_image   = $imagePath;
                        $questionToUpdate->question         = $request->question;
                        $questionToUpdate->type             = $request->type;
                        $questionToUpdate->term             = $request->term;
                        $questionToUpdate->subject_code_id  = $request->subject_code_id;
                        $questionToUpdate->editor_id        = $request->editor_id;
                        $questionToUpdate->save();
    
                        foreach($request->options as $option)
                        {
                            $answer = '';
                            if($option['isCorrect']=='true')
                            {
                                $answer = 'true';
                            }
                            else
                            {
                                $answer = 'false';
                            }

                            $optionToUpdate = Option::findOrFail($option['option_id']);

                            $optionToUpdate->option = $option['option'];
                            $optionToUpdate->isCorrect = $answer;
                            $optionToUpdate->save();
                        }

                        DB::commit();
                        return redirect()->route('questions.show')->with('success', 'Question updated successfully.');
                   }
                   catch(\Exception $e)
                   {
                        DB::rollback();
                        Log::error('error updating question 3: '.$e->getMessage());

                        foreach($tempAttachedImage as $fileName)
                        {
                            if(Storage::exists($fileName))
                            {
                                Storage::delete($fileName);
                            }
                        }

                        return redirect()->back()->with('error', 'Failed to update the question.');
                   } 
                   
                }
                else // request doesnt have image
                {
                    try
                    {
                        DB::beginTransaction();
                        $questionToUpdate->question         = $request->question;
                        $questionToUpdate->type             = $request->type;
                        $questionToUpdate->term             = $request->term;
                        $questionToUpdate->subject_code_id  = $request->subject_code_id;
                        $questionToUpdate->editor_id        = $request->editor_id;
                        $questionToUpdate->save();

                        foreach($request->options as $option)
                        {
                            $answer = '';
                            if($option['isCorrect']=='true')
                            {
                                $answer = 'true';
                            }
                            else
                            {
                                $answer = 'false';
                            }

                            $optionToUpdate = Option::findOrFail($option['option_id']);

                            $optionToUpdate->option = $option['option'];
                            $optionToUpdate->isCorrect = $answer;
                            $optionToUpdate->save();
                        }
                        DB::commit();
                        return redirect()->route('questions.show')->with('success', 'Question updated successfully.');
                    }
                    catch(\Exception $e)
                    {
                        DB::rollback();
                        Log::error('error updating question 4: '.$e->getMessage());

                        return redirect()->back()->with('error', 'Failed to update the question.');
                    }
                    
                }
            }
            
            
            
        }
       
        if($request->type == 'image')
        { 
            if($questionToUpdate->attached_image)
            {
                $tempOptionImages = [];
                $tempAttachedImage = [];
                //delete the attached image and proceed to updating;
                if($request->attached_image == $questionToUpdate->attached_image)
                {
                    try
                    {
                        DB::beginTransaction();
                        $questionToUpdate->question             = $request->question;
                        $questionToUpdate->type                 = $request->type;
                        $questionToUpdate->term                 = $request->term;
                        $questionToUpdate->subject_code_id      = $request->subject_code_id;
                        $questionToUpdate->editor_id            = $request->editor_id;
                        $questionToUpdate->save();

                        foreach($request->options as $option)
                        {
                            $answer = '';
                            if($option['isCorrect']==='true')
                            {
                                $answer = 'true';
                            }
                            else
                            {
                                $answer = 'false';
                            }
                            
                            $optionToUpdate = Option::findOrFail($option['option_id']);
                            $file = $option['option'];
                            $path = $file->store('Images','public');
                            $imagePath = basename($path);
                            
                            
                            $tempOptionImages[] = $imagePath;

                            $optionToUpdate->option = $imagePath;
                            $optionToUpdate->isCorrect = $answer;
                            $optionToUpdate->save();

                        }

                        DB::commit();
                        return redirect()->route('questions.show')->with('success', 'Successfully updated a question.');
                    }
                    catch(\Exception $e)
                    {
                        DB::rollback();
                        Log::error('error updating question 5: '.$e->getMessage());

                        foreach($tempOptionImages as $fileName)
                        {
                            if(Storage::exists($fileName))
                            {
                                Storage::delete($fileName);
                            }
                        }

                        return redirect()->back()->with('error', 'Failed to update the question');
                    }
                    
                }
                else
                {
                    //new image
                    try
                    {
                        DB::beginTransaction();
                        $file = $request->file('attached_image');
                        $path = $file->store('Images','public');
                        $imagePath = basename($path);
                        
                        $tempAttachedImage[] = $imagePath;

                        $questionToUpdate->attached_image       = $imagePath;
                        $questionToUpdate->question             = $request->question;
                        $questionToUpdate->type                 = $request->type;
                        $questionToUpdate->term                 = $request->term;
                        $questionToUpdate->subject_code_id      = $request->subject_code_id;
                        $questionToUpdate->editor_id            = $request->editor_id;
                        $questionToUpdate->save();

                        foreach($request->options as $option)
                        {
                            $answer = $option['isCorrect'] == 'true' ? 'true':'false';
                            $optionToUpdate = Option::findOrFail($option['option_id']);

                            $optionImagePath = $optionToUpdate->option;
                            //delete existing image
                            if(Storage::exists($optionImagePath))
                            {
                                Storage::delete($optionImagePath);
                            }


                            $file = $option['option'];
                            $path = $file->store("Images",'public');
                            $imagePath = basename($path);

                            $tempOptionImages[] = $imagePath;

                            $optionToUpdate->option = $imagePath;
                            $optionToUpdate->isCorrect = $answer;
                            $optionToUpdate->save();
                        }

                        DB::commit();
                        return redirect()->route('questions.show')->with('success', 'Successfully updated a question.');
                    }
                    catch(\Exception $e)
                    {
                        DB::rollback();
                        Log::error('error updating question 6: '.$e->getMessage());

                        foreach($tempAttachedImage as $fileName)
                        {
                            if(Storage::exists($fileName))
                            {
                                Storage::delete($fileName);
                            }
                        }

                        foreach($tempOptionImages as $fileName)
                        {
                            if(Storage::exists($fileName))
                            {
                                Storage::delete($fileName);
                            }
                        }

                        return redirect()->back()->with('error', 'Failed to update the question.');

                    }

                    
                }
            }
        }
        
        //dd($questionToUpdate->attached_image == $request->attached_image);
        
    }
    public function destroy($id)
    {
        $questionToDelete = Question::findOrFail($id);
        $randomNumber = rand(1,10000000);
        $time = now();
        if($questionToDelete->type == 'text')
        {
            try{
                DB::beginTransaction();

                $optionToDelete = $questionToDelete->options;

                foreach($optionToDelete as $option)
                {
                    $option->delete();
                }

                if($questionToDelete->attached_image)
                {
                    $attachedImagePath = 'public/images/'.$questionToDelete->attached_image;
                    if(Storage::exists($attachedImagePath))
                    {
                         Storage::delete($attachedImagePath);
                    }
                }

                $questionToDelete->delete();

                DB::commit();

                return redirect()->route('questions.show')->with([
                    'success'   => 'Successfully Deleted a Questions.',
                    'action'    => 'reload'
                ]);
            }catch(\Exception $e)
            {
                DB::rollback();
                Log::error('error deleting question: '.$e->getMessage());

                return redirect()->back()->with('error', 'Failed to delete question. Please try again.');
            }
            
        }

        if($questionToDelete->type == 'image')
        {
            try{
                DB::beginTransaction();
                $optionToDelete = $questionToDelete->options;

                foreach($optionToDelete as $option)
                {
                    $imagePath = 'public/Images/'.$option->option; // Adjust 'images/' based on your file storage structure
                    if(Storage::exists($imagePath))
                    {
                         Storage::delete($imagePath);
                    }
                    
                    $option->delete();
                }

                if($questionToDelete->attached_image)
                {
                    $attachedImagePath = 'public/images/'.$questionToDelete->attached_image;
                    if(Storage::exists($attachedImagePath))
                    {
                         Storage::delete($attachedImagePath);
                    }
                }

                $questionToDelete->delete();

                DB::commit();

                return redirect()->back()->with('success', 'Successfully Deleted a Question.');
            }catch(\Exception $e)
            {
                DB::rollback();
                Log::error('error deleting question: '.$e->getMessage());

                return redirect()->back()->with('error', 'Failed to delete question. Please try again.');
            }
            
        }
    }

    
}
