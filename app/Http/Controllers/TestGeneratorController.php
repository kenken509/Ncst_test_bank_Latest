<?php

namespace App\Http\Controllers;

use TCPDF;
use DateTime;
use ZipArchive;
use Carbon\Carbon;
use App\Pdf\CustomPDF;
use App\Models\Question;
use App\Pdf\CustomTCPDF;
use App\Pdf\AnswerKeyPdf;
use App\Models\Department;
use App\Models\SubjectCode;
use App\Exports\ExcelExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
        $user = Auth::user();
        $this->cleanUp();
        
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
                $shuffledQuestionAnswerKey = [];

                foreach($shuffledQuestionSet as $question)
                {
                    foreach($question->options as $index => $option)
                    {
                        $optionLetters = ['A','B','C','D'];
                        if($option->isCorrect == 'true')
                        {
                            $shuffledQuestionAnswerKey[] = $optionLetters[$index];
                        }

                    }
                }

                $date = Carbon::now();
                // Generate PDF for the current set
                $filename = $this->generatePDF($set, $shuffledQuestionSet, $subject_code_name, $subject_description, $department, $request->semester, $request->term, $request->school_year);
                $answerKeyFileName = $this->generateAnswerKeyPdf($set, $shuffledQuestionAnswerKey, $subject_code_name, $subject_description, $department, $request->semester, $request->term, $request->school_year);

                $properties = [
                    'creator'        => ucFirst($user->role).' '.ucFirst($user->name),
                    'lastModifiedBy' => ucFirst($user->role).' '.ucFirst($user->name),
                    'title'          => $subject_code_name.' '.'Set '.$set.' Answer keys',
                    'description'    => ucFirst($request->term).' Exam Answer Keys',
                    'subject'        => '',
                    'keywords'       => '',
                    'category'       => '',
                    'manager'        => '',
                    'company'        => 'National College of Science and Technology',  
                ];
                $excelFileName = ucFirst($request->term).'-'.$subject_code_name.'-ANSWER-'.$set.'-'.$date->format('d-m-Y-H-i-s').'.csv';      //final-ACT106-ANSWER-A-02_07_2024 15.33.28.csv
                $filePath = 'Pdfs/' . $excelFileName;
                Excel::store(new ExcelExport($shuffledQuestionAnswerKey, $properties), $filePath, 'public');

                $pdfFiles[] = $filename;
                $pdfFiles[] = $answerKeyFileName;
                $pdfFiles[] = $excelFileName;
                // Log successful PDF generation
                Log::info("PDF generated for Set: $set, Subject: $subject_code_name");
            }

            // Create and save the zip file
            $zipFilename = $this->createZip($pdfFiles, $subject_code_name, $request->term);
            $zipFilePath = storage_path('app/public/pdfs/' . $zipFilename);

            // Ensure zip file exists
            if (!file_exists($zipFilePath)) {
                Log::error("Zip file does not exist at: $zipFilePath");
                return response()->json(['error' => 'Zip file not found'], 404);
            }

            //throw new \Exception('simulated error ');
            // Log successful zip creation
            Log::info("Zip file created: $zipFilename");

            $downloadUrl = Storage::url('public/pdfs/' . $zipFilename);
            
            return redirect()->back()->with('donwloadUrl', $downloadUrl);
        } catch (\Exception $e) {
            // Log any exceptions that occur
            Log::error('Exception occurred while generating exam: ' . $e->getMessage());
            return redirect()->back()->with('error','Error: '.$e->getMessage().'.'.Carbon::now());
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
        $date = Carbon::now();
         

        //$set, $questionSet, $subject_code_name, $subject_description, $department, $semester, $term, $schoolYr
        $filename = $term.'-'.$subject_code_name.'-'.$set. '-' . $date->format('d-m-Y-H-i-s') . '.pdf';
        $pdf->Output(storage_path('app/public/pdfs/' . $filename), 'F');

        return $filename;
    }

    
    //goods
    private function generateAnswerKeyPdf($set, $answerKey, $subject_code_name, $subject_description, $department, $semester, $term, $schoolYr)
    {
        //$answerKey = ['C', 'A', 'A', 'A', 'D', 'A', 'C', 'B', 'A', 'C', 'B', 'A', 'B', 'D', 'C', 'D', 'B', 'B', 'D', 'B', 'B', 'B', 'C', 'D', 'D', 'B', 'A', 'C', 'C', 'A', 'B', 'C', 'D', 'B', 'A', 'C', 'C', 'D', 'C', 'D', 'A', 'A', 'A', 'D', 'C', 'B', 'B', 'B', 'C', 'D', 'D', 'C', 'C', 'B', 'C', 'A', 'D', 'C', 'D', 'D', 'B', 'A', 'B', 'D', 'A', 'B', 'B', 'A', 'D', 'A', 'D', 'C', 'A', 'D', 'C', 'D', 'D', 'D', 'C', 'B', 'C', 'D', 'D', 'C', 'B', 'C', 'B', 'C', 'D', 'D', 'B', 'D', 'B', 'A', 'A', 'C', 'B', 'B', 'B', 'B', 'A', 'D', 'A', 'C', 'C', 'C', 'A', 'C', 'D', 'A', 'B', 'B', 'A', 'D', 'B', 'A', 'C', 'C', 'C', 'D', 'A', 'B', 'D', 'B', 'A', 'B', 'C', 'D', 'B', 'B', 'D', 'B', 'A', 'B', 'A', 'D', 'D', 'C', 'C', 'D', 'B', 'B', 'D', 'B', 'A', 'A', 'D', 'D', 'D', 'B', 'A', 'B', 'D', 'A', 'D', 'C', 'A', 'C', 'D', 'A', 'B', 'B', 'A', 'A', 'A', 'A', 'C', 'A', 'A', 'B', 'C', 'A', 'B', 'A', 'A', 'D', 'B', 'D', 'A', 'B', 'B', 'D', 'B', 'B', 'D', 'B', 'A', 'D', 'C', 'A', 'B', 'C', 'D', 'D', 'C', 'C', 'D', 'B', 'A', 'C', 'B', 'B', 'A', 'B', 'B', 'A', 'B', 'C', 'D', 'C', 'C', 'A', 'D', 'B', 'D', 'A', 'C', 'C', 'A', 'C', 'A', 'C', 'B', 'C', 'C', 'C', 'D', 'C', 'B', 'B', 'B', 'A', 'A', 'A', 'D', 'C', 'D', 'B', 'C', 'D', 'B', 'D', 'D', 'D', 'D', 'D', 'B', 'D', 'D', 'C', 'B', 'B', 'A', 'B', 'D', 'B', 'B', 'D', 'C', 'C', 'C', 'C', 'A', 'D', 'C', 'C', 'A', 'A', 'C', 'C', 'B', 'C', 'D', 'D', 'D', 'C', 'D', 'D', 'D', 'B', 'C', 'C', 'B', 'A', 'C', 'B', 'C', 'C', 'B', 'B', 'C', 'D', 'C', 'B', 'D', 'B', 'D', 'C', 'A', 'B'];
        // $answerKey = [
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        //     'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D',
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        //     'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D',
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        //     'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D',
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        //     'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D',
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        //     'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D',
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        //     'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D',
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        //     'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D',
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        //     'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D',
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        //     'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D',
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        //     'A', 'B', 'C', 'D', 'A', 'B', 'C', 'D', 'A', 'B',
        // ];
        $user = Auth::user();
        $pdf = new AnswerKeyPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->selectedDepartment    = $department;
        $pdf->subject_description   = $subject_description;
        $pdf->selectedTerm          = $term;
        $pdf->selectedSubjectCode   = $subject_code_name;
        $pdf->selectedSemester      = $semester;
        $pdf->selectedSchoolYear    = $schoolYr;
        $pdf->set                   = $set;

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($user->role . ' ' . $user->name);
        $pdf->SetTitle($term.' Set '.$set.' Answer Key in ' . $subject_code_name);
        $pdf->SetSubject('Generated Exam Paper');
        $pdf->SetKeywords('TCPDF, PDF, exam, test, paper');

        $pdf->SetMargins(10, 10, 10, true);
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 12);

        $pdf->AddPage();

        // Write header information
        $pdf->SetY(73);

        $pdf->setCellPadding(1.6,1.6,1.6,1.6);

        // Column settings
        $maxAnswersPerColumn = 25;
        $maxNumberOfColumns = 8;
        $numColumns = ceil(count($answerKey) / $maxAnswersPerColumn);

        $currentColumn = 0;
        if(floor($numColumns) > 8)
        {
            $columnWidth = floor($pdf->getPageWidth() - 20) / $maxNumberOfColumns; // Adjust the width based on margins
        }
        else
        {   
            $columnWidth = floor($pdf->getPageWidth() - 20) / $numColumns; // Adjust the width based on margins
        }
        
        
        for($currentColumn ; $currentColumn < $numColumns; $currentColumn++)
        {
            $startingIndex = $currentColumn*$maxAnswersPerColumn;
            $endingIndex = $maxAnswersPerColumn;

            if($currentColumn < 8)
            {
                $pdf->SetY(73);

                // Create the new array based on the specified range
                $newArray = array_slice($answerKey, $startingIndex, $endingIndex);
                
                foreach($newArray as $index => $answer)
                {
                    $pdf->SetX(($currentColumn*$columnWidth)+10);
                    $pdf->MultiCell($columnWidth - 5, 5, (($currentColumn*$maxAnswersPerColumn)+($index + 1)) . '. ' . $answer, 0, 'L', false);
                }
            }

            // needs update ******
            // $multiplier = 1;
            // $layer = 8;

            // if($currentColumn >= 8 && $currentColumn % 8 == 0)
            // {
                
            //     $layer = $layer * $multiplier;
            //     $pdf->addPage();
            //     $pdf->SetY(10);                
                
            //     $multiplier++;
            // }

            // if($currentColumn >= 8)
            // {
                
            //     $newArray = array_slice($answerKey, $startingIndex+7, 32);

                
            //     foreach($newArray as $index => $answer)
            //     {
            //         $pdf->SetX((($currentColumn-$layer)*$columnWidth)+10);
            //         $pdf->MultiCell($columnWidth - 5, 5, (($currentColumn*$maxAnswersPerColumn)+($index + 1)) . '. ' . $answer, 1, 'L', false);
            //     }
            
            // }
   
        }   
        
        
        // for ($col = 0; $col < $numColumns; $col++) {
        //     $pdf->SetY(73);
        //     //$pdf->SetX(($col * $columnWidth)+10); // Adjust starting X position for each column
            
        //     for ($i = 0; $i < $maxAnswersPerColumn; $i++) {

        //         $index = $col * $maxAnswersPerColumn + $i;
        //         if ($index >= count($answerKey)) break;
        //         $answer = $answerKey[$index];
        //         $pdf->SetX(($col * $columnWidth)+10);
        //         $pdf->MultiCell($columnWidth - 5, 5, ($index + 1) . '. ' . $answer, 0, 'L', false);
        //     }
            
        // }

        $date = Carbon::now();
        $filename = $term.'-'.$subject_code_name.'-'.'ANSWER-'.$set.'-' . $date->format('d-m-Y-H-i-s') . '.pdf';
        $pdf->Output(storage_path('app/public/pdfs/' . $filename), 'F');

        return $filename;
    }

    




    



    private function createZip($files , $subject_code, $term)
    {
        $zip = new ZipArchive;
        $date = Carbon::now();
        $zipFilename = ucFirst($term).'_Exam_'.$subject_code.'_'. $date->format('d-m-Y-H-i-s') . '.zip';
    
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

    public function cleanUp()
    {
        
        $directory = public_path('storage/Pdfs');

        // Check if the directory exists
        if (File::exists($directory)) {
            // Delete the directory and its contents
            File::deleteDirectory($directory);

            // Recreate the empty directory if needed
            File::makeDirectory($directory, 0755, true, true);
        }

    }

    // public function deleteFolderContents()
    // {
    //     $directory = public_path('storage/Pds');

    //     // Check if the directory exists
    //     if (File::exists($directory)) {
    //         // Delete the directory and its contents
    //         File::deleteDirectory($directory);

    //         // Recreate the empty directory if needed
    //         File::makeDirectory($directory, 0755, true, true);
    //     }

    //     return 'Directory contents deleted successfully.';
    // }
}
