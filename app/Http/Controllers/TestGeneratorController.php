<?php

namespace App\Http\Controllers;

use TCPDF;
use DateTime;
use ZipArchive;
use Carbon\Carbon;
use App\Pdf\CustomPDF;
use App\Models\Question;
use App\Pdf\CustomTCPDF;
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
            $subject_description = $subject_code->description;
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
                $questionSet = $prelimQuestions->concat($midtermQuestions)->concat($preFinalQuestions)->concat($finalQuestions);

                $shuffledQuestionSet = $questionSet->shuffle();
               
                // Generate PDF for the current set
                $filename = $this->generatePDF($set, $shuffledQuestionSet, $subject_code_name, $subject_description, $department, $request->semester, $request->term, $request->school_year);
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



    private function generatePDF($set, $questionSet, $subject_code_name, $subject_description, $department, $semester, $term, $schoolYr)
    {

        $user = Auth::user();
        $pdf = new CustomTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->selectedDepartment    = $department;
        $pdf->subject_description   = $subject_description;
        $pdf->selectedTerm          = $term;
        $pdf->selectedSubjectCode   = $subject_code_name;
        $pdf->selectedSemester      = $semester;
        $pdf->selectedSchoolYear    = $schoolYr;
        $pdf->set                   = $set;

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($user->role . ' ' . $user->name);
        $pdf->SetTitle($term . ' Exam in ' . $subject_code_name);
        $pdf->SetSubject('Generated Exam Paper');
        $pdf->SetKeywords('TCPDF, PDF, exam, test, paper');

        $pdf->SetMargins(10, 10, 10, true);
        //$pdf->SetAutoPageBreak(true, 10); // Sets bottom margin
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 12);

        $pdf->AddPage();

        // Write header information
        $pdf->SetY(73);

        $number = 0;
        foreach ($questionSet as $question) 
        {
            $number++;
            $pdf->setCellPaddings(2, 2, 2, 2);
            // if string length is more than pagewithd - padding - margins text should be justified
            $textOrientation = 'L';
            $stringLength = strlen($question->question);
            if(strlen($question->question) > 60)
            { 
                $textOrientation = 'J';
            }

            $pdf->MultiCell(0, 5, $number . '. ' . $question->question, 0, $textOrientation , false);
            
            if ($question->type == 'text') // options
            {
                
                $xPos = $pdf->GetX();
                $pageWidth = $pdf->getPageWidth() - $pdf->getMargins()['right'] - $pdf->getMargins()['left'];
                $cellWidth = ($pdf->getPageWidth() - $pdf->getMargins()['right'] - $pdf->getMargins()['left'] - 11 ) / 4; // Width of each cell
                $cellSpacing = 2; // Space between cells
                $currentWidth = 0;

                $maxLength = 0;

                // Find the maximum length in the options array
                foreach ($question->options as $option) {
                    $optionLength = strlen($option->option);
                    if ($optionLength > $maxLength) {
                        $maxLength = $optionLength;
                    }
                }

                $oneColumn = false;
                $twoColumns = false;
                $fourColumns = false;
                // Determine the number of columns based on the maximum length
                if ($maxLength > 26) {
                    $oneColumn = true;
                    $twoColumns = false;
                    $fourColumns = false;
                } elseif ($maxLength > 13 && $maxLength <= 26) {
                    $twoColumns = true;
                    $oneColumn = false;
                    $fourColumns = false;
                } else {
                    $fourColumns = true;
                    $oneColumn = false;
                    $twoColumns = false;
                }

                if($twoColumns)
                {
                    $cellWidth = ($pdf->getPageWidth() - $pdf->getMargins()['right'] - $pdf->getMargins()['left'] - 11 ) / 2; // Width of each cell
                }

                if($oneColumn)
                {
                    $cellWidth = ($pdf->getPageWidth() - $pdf->getMargins()['right'] - $pdf->getMargins()['left'] - 11 );
                }

                foreach ($question->options as $index => $option) 
                {
                    $length = strlen($option->option);
                    $letter = ['A.','B.','C.','D.'];
                    if($fourColumns)
                    {
                        if ($currentWidth + $cellWidth > $pageWidth) {
                            // Move to the next line if the width exceeds the page width
                            $pdf->Ln();
                            $xPos = $pdf->GetX();
                            $currentWidth = 0;
                        }
    
                        $pageHeight = $pdf->getPageHeight() - 20;
                        $pdf->SetX($xPos + $currentWidth +5);

                        if(floor($pdf->GetY()+10) >= $pageHeight)
                        {
                            $pdf->addPage();
                            $pdf->setY(11);
                            $pdf->ln();
                            $pdf->setX(15);
                        }

                        $pdf->MultiCell($cellWidth, 5, $letter[$index].' '.$option->option, 0, 'L', 0, 0, '', '', true);
                        $currentWidth += $cellWidth + $cellSpacing;
                    }

                    if($twoColumns)
                    {
                        
                        if ($currentWidth + $cellWidth > $pageWidth) {
                            // Move to the next line if the width exceeds the page width
                            $pdf->Ln();
                            $xPos = $pdf->GetX();
                            $currentWidth = 0;
                        }
    
                        
                        $pdf->SetX($xPos + $currentWidth +5);

                        $pageHeight = $pdf->getPageHeight() - 20;
                        
                       
                        if(floor($pdf->GetY()+10) >= $pageHeight)
                        {
                            $pdf->addPage();
                            $pdf->setY(11);
                            $pdf->ln();
                            $pdf->setX(15);
                        }
                        
                        $pdf->MultiCell($cellWidth, 5, $letter[$index].' '.$option->option, 0, 'L', 0, 0, '', '', true);
                        $currentWidth += $cellWidth + $cellSpacing;
                    }
                   
                    if($oneColumn)
                    {
                        if ($currentWidth + $cellWidth > $pageWidth) {
                            // Move to the next line if the width exceeds the page width
                            $pdf->Ln();
                            $xPos = $pdf->GetX();
                            $currentWidth = 0;
                        }
                        
                        
                        $pdf->SetX($xPos + $currentWidth +5);
                        
                        $pageHeight = $pdf->getPageHeight() - 20;
                        
                       
                        if(floor($pdf->GetY()+10) >= $pageHeight)
                        {
                            $pdf->addPage();
                            $pdf->setY(11);
                            $pdf->ln();
                            $pdf->setX(15);
                        }
                        
                        $pdf->MultiCell($cellWidth, 5, $letter[$index].' '.$option->option, 0, 'L', 0, 0, '', '', true);
                        //$pdf->Cell($cellWidth,5,$letter[$index].' '.$option->option,1,0,'L',false,'');
                        //$pdf->MultiCell($cellWidth, 5, $letter[$index] . ' ' . $option->option, 0, 'L', false);
                        $currentWidth += $cellWidth + $cellSpacing;
                    }
                    
                }
                $pdf->Ln(); // Move to the next line after options
            }

        
                
            if ($question->type == 'image') {

                

                // Get image paths
                $optionA = public_path('storage/Images/' . $question->options[0]->option);
                $optionB = public_path('storage/Images/' . $question->options[1]->option);
                $optionC = public_path('storage/Images/' . $question->options[2]->option);
                $optionD = public_path('storage/Images/' . $question->options[3]->option);
            
                $pageWidth = $pdf->getPageWidth() - $pdf->getMargins()['right'] - $pdf->getMargins()['left'];
                $y = $pdf->GetY();

                $pageHeight = $pdf->getPageHeight() - 20; // margin T10 B10

                if($y+5+34 > $pageHeight)
                {
                    $pdf->addPage();
                    $y = 10;
                }
            
                // Define the width for each element
                $textWidth = 10; // Width for the text "A."
                $imageWidth = 34; // Width for the image
                $imageHeight = 34;
                // Add the text "A."
                $pdf->SetX(10);
                $pdf->MultiCell(10,5,'A. ',0,'J',0,0,15,$y+5,true); //+3
                $pdf->MultiCell(10,5,'B. ',0,'J',0,0,62,$y+5,true);
                $pdf->MultiCell(10,5,'C. ',0,'J',0,0,109,$y+5,true);
                $pdf->MultiCell(10,5,'D. ',0,'J',0,0,156,$y+5,true);
                // Add the image
                $pdf->Image($optionA, 23, $y+5, $imageWidth, $imageHeight, '', '', '', false, 300, '', false, false, 1, false, false, false);
                $pdf->Image($optionB, 70, $y+5, $imageWidth, $imageHeight, '', '', '', false, 300, '', false, false, 1, false, false, false);
                $pdf->Image($optionC, 117, $y+5, $imageWidth, $imageHeight, '', '', '', false, 300, '', false, false, 1, false, false, false);
                $pdf->Image($optionD, 164, $y+5, $imageWidth, $imageHeight, '', '', '', false, 300, '', false, false, 1, false, false, false);

                $pdf->ln();
            }
            
        }
        $date = Carbon::create(2024, 7, 2, 15, 33, 28);
         

        //$set, $questionSet, $subject_code_name, $subject_description, $department, $semester, $term, $schoolYr
        $filename = $term.'-'.$subject_code_name.'-'.$set. '-' . $date->format('d-m-Y-H-i-s') . '.pdf';
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
