<?php

namespace App\Http\Controllers;

use TCPDF;
use ZipArchive;
use App\Models\Question;
use App\Models\Department;
use App\Models\SubjectCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class TestGeneratorController extends Controller
{
    public function showTestGenerator()
    {
        // $subjectCodes = SubjectCode::with(['questions' => function ($query){
        //     $query->with(['author','options']);
        // }])->latest()->get();

        $departments = Department::with(['subjectCodes' => function($query){
            $query->with(['questions' => function ($query){
                $query->with(['author','options']);
            },'problemSets']);
        },'divisions'])->get();

        // $departments = Department::with(['subjectCodes' => function($query){
        //     $query->with(['questions' => function ($query){
        //         $query->with(['author'])
        //               ->with(['options' => function ($query) {
        //                   $query->inRandomOrder();
        //               }])
        //               ->inRandomOrder();
        //     }]);
        // }, 'divisions'])->get();

      
       //dd($departments);
        return inertia('Dashboard/TestGenerator/TestGenearator',[
            'department' => $departments
        ]);
    }

 
    public function showGeneratedExam(Request $request)
    {
       // dd($request);
        return inertia('Dashboard/TestGenerator/GeneratedTest');
    }

    public function showTestGeneratorNew()
    {
        $departments = Department::with(['subjectCodes' => function($query){
            $query->with(['questions' => function ($query){
                $query->with(['author','options']);
            },'problemSets']);
        },'divisions'])->get();


        return inertia('Dashboard/TestGenerator/TestGenNew',[
            'department' => $departments
        ]);
    }

    public function generateExamOld(Request $request)
    {
        $subject_code = SubjectCode::where('id', $request->subject_code_id)
            ->with(['department', 'division'])
            ->first();

        $subject_code_name = $subject_code->name;
        $department = '';
        $semester = $request->semester;
        $term = $request->term;
        $selectedExamSet = explode(" ", $request->set);

        if ($subject_code->division) {
            $department = $subject_code->department->name . ' ' . $subject_code->division->name;
        } else {
            $department = $subject_code->department->name;
        }

        

        foreach ($selectedExamSet as $set) {
            $questionSet = [];

            $prelimQuestions = Question::where('term', 'prelim')
                ->with(['options' => function($query) {
                    $query->inRandomOrder();
                }])
                ->inRandomOrder()
                ->take($request->prelim_count ? $request->prelim_count : 0)
                ->get()
                ->unique('id');

            $midtermQuestions = Question::where('term', 'mid-term')
                ->with(['options' => function($query) {
                    $query->inRandomOrder();
                }])
                ->inRandomOrder()
                ->take($request->mid_term_count ? $request->mid_term_count : 0)
                ->get()
                ->unique('id');

            $preFinalQuestions = Question::where('term', 'pre-final')
                ->with(['options' => function($query) {
                    $query->inRandomOrder();
                }])
                ->inRandomOrder()
                ->take($request->pre_final_count ? $request->pre_final_count : 0)
                ->get()
                ->unique('id');

            $finalQuestions = Question::where('term', 'final')
                ->with(['options' => function($query) {
                    $query->inRandomOrder();
                }])
                ->inRandomOrder()
                ->take($request->final_count ? $request->final_count : 0)
                ->get()
                ->unique('id');

            // Merge all questions into one collection
            $questionSet = $prelimQuestions
                ->merge($midtermQuestions)
                ->merge($preFinalQuestions)
                ->merge($finalQuestions);

          
        }

       
    }

    public function generateExam(Request $request)
    {
        
        
        try {
            $subject_code = SubjectCode::where('id', $request->subject_code_id)
                ->with(['department', 'division'])
                ->first();

            // Fetch subject details
            $subject_code_name = $subject_code->name;
            $department = $subject_code->department->name;
            if ($subject_code->division) {
                $department .= ' ' . $subject_code->division->name;
            }

            // Fetch selected exam sets
            $selectedExamSet = explode(" ", $request->set);

            // Initialize array for PDF file names
            $pdfFiles = [];

            // Loop through selected exam sets
            foreach ($selectedExamSet as $set) {
                // Fetch questions for each term and merge them into a single collection
                $prelimQuestions = Question::where('term', 'prelim')->inRandomOrder()->take($request->prelim_count ?? 0)->get()->unique('id');
                $midtermQuestions = Question::where('term', 'mid-term')->inRandomOrder()->take($request->mid_term_count ?? 0)->get()->unique('id');
                $preFinalQuestions = Question::where('term', 'pre-final')->inRandomOrder()->take($request->pre_final_count ?? 0)->get()->unique('id');
                $finalQuestions = Question::where('term', 'final')->inRandomOrder()->take($request->final_count ?? 0)->get()->unique('id');

                // Merge all questions into one collection
                //$questionSet = $prelimQuestions->merge($midtermQuestions)->merge($preFinalQuestions)->merge($finalQuestions);
                $questionSet = $prelimQuestions->merge($finalQuestions);
                // Generate PDF for the current set
                $filename = $this->generatePDF($set, $questionSet, $subject_code_name, $department, $request->semester, $request->term);
                $pdfFiles[] = $filename;

                // Log successful PDF generation
                Log::info("PDF generated for Set: $set, Subject: $subject_code_name");
            }

            // Create and save the zip file
            $zipFilename = $this->createZip($pdfFiles);
            $zipFilePath = storage_path('app/public/pdfs/' . $zipFilename);

            // Ensure zip file exists
            if (!file_exists($zipFilePath)) {
                Log::error("Zip file does not exist at: $zipFilePath");
                return response()->json(['error' => 'Zip file not found'], 404);
            }

            // Log successful zip creation
            Log::info("Zip file created: $zipFilename");

            $downloadUrl = Storage::url('public/pdfs/' . $zipFilename);

            return redirect()->back()->with('donwloadUrl', $downloadUrl);
        } catch (\Exception $e) {
            // Log any exceptions that occur
            Log::error('Exception occurred while generating exam: ' . $e->getMessage());
            throw $e;
        }
    }



    private function generatePDF($set, $questionSet, $subject_code_name, $department, $semester, $term)
    {
        
        $user = Auth::user();
        $pdf = new TCPDF();

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($user->role.' '.$user->name);
        $pdf->SetTitle($term.' Exam in '.$subject_code_name);
        $pdf->SetSubject('Generated Exam Paper');
        $pdf->SetKeywords('TCPDF, PDF, exam, test, paper');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->AddPage();

        $pdf->writeHTML('<h1>Exam Set: ' . $set . '</h1>', true, false, true, false, '');
        $pdf->writeHTML('<h2>Subject: ' . $subject_code_name . '</h2>', true, false, true, false, '');
        $pdf->writeHTML('<h3>Department: ' . $department . '</h3>', true, false, true, false, '');
        $pdf->writeHTML('<h4>Semester: ' . $semester . ' Term: ' . $term . '</h4>', true, false, true, false, '');

        foreach ($questionSet as $question) {
            
            $pdf->writeHTML('<p>' . $question->question . '</p>', true, false, true, false, '');
            foreach ($question->options as $option) {
                if($question->type == 'text')
                {
                    $pdf->writeHTML('<p>- ' . $option->option . '</p>', true, false, true, false, '');
                }
                else if($question->type == 'image')
                {
                    $imageFilePath = public_path('storage/Images/' . $option->option); // Adjust the path as needed
                    $html = '<p>- <img src="' . $imageFilePath . '" width="100" /></p>'; // Adjust width as needed
                    $pdf->writeHTML($html, true, false, true, false, '');
                }
            }
        }

        $filename = 'exam_' . $set . '_' . time() . '.pdf';
        $pdf->Output(storage_path('app/public/pdfs/' . $filename), 'F');

        return $filename;
    }

    private function createZip($files)
    {
        $zip = new ZipArchive;
        $zipFilename = 'exams_' . time() . '.zip';
    
        if ($zip->open(storage_path('app/public/pdfs/' . $zipFilename), ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $filePath = storage_path('app/public/pdfs/' . $file);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();
        }
    
        return $zipFilename;
    }

}
