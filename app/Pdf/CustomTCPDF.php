<?php

namespace App\Pdf;

use TCPDF;

class CustomTCPDF extends TCPDF
{
    public $selectedDepartment;
    public $selectedTerm;
    public $selectedSubjectCode;
    public $selectedSemester;
    public $selectedSchoolYear;
    public $displaySetQuestion;
    public $subject_description;
    public $set;

    public function Header()
    {
        if($this->page == 1)
        {
            // Define the logo path
            $logoUrl = public_path('storage/Images/ncstLogo.png'); // Ensure the path is correct

            

            
            // Set font
            $this->SetFont('helvetica', 'B', 16);

            // Move the cursor to the right of the image
            //$this->SetXY(40, 50); // Adjust X and Y coordinates as needed  40mm from the left, 15mm from the top

            $this->SetY($this->GetY() + 5); 
            // Title
            $this->Cell(0, 8, 'NATIONAL COLLEGE OF SCIENCE AND TECHNOLOGY', 0, 1, 'C');

            // Set font
            $this->SetFont('helvetica', '', 10);

            // Move the cursor below the title
            //$this->SetX(40); // Adjust X coordinate as needed

           
            // Address
            $this->Cell(0, 4, 'Amafel Building Aguinaldo Highway, Dasmariñas, Cavite', 0, 1, 'C');
            
            // Move the cursor below the address
            //$this->SetX(40); // Adjust X coordinate as needed

            // Telephone
            $this->Cell(0, 4, 'Tel. no. (1234-1234)', 0, 1, 'C');

            // Move the cursor below the telephone
            //$this->SetX(40); // Adjust X coordinate as needed

            // Website
            $this->Cell(0, 4, 'www.ncst.edu.ph', 0, 1, 'C');

            $this->SetY($this->GetY() + 5); 
            $this->SetFont('helvetica', 'B', 12); // Set font to Helvetica, bold, size 10
            $this->Cell(0, 4, $this->selectedDepartment.' Department', 0, 1, 'C');
            $this->Cell(0, 4, ucFirst($this->selectedTerm).' Exam in '.$this->subject_description, 0, 1, 'C');
            $this->Cell(0, 4, $this->selectedSemester.' Semester SY:'.$this->selectedSchoolYear, 0, 1, 'C');
            $this->Cell(0, 4, 'Set '.$this->set, 0, 1, 'C');

            $this->SetY($this->GetY() + 5); 
            $left_margin = 10;
            $right_margin = 10;
            $this->SetX($left_margin);
            $cell_width = $this->getPageWidth() - $left_margin - $right_margin;
            $this->MultiCell($cell_width, 4, 'Directions: Mulitple Choice. Read the statement carefully. Use the provided answer sheet. Choose the letter that correspond to the correct answer. STRICLY NO ERASURE.', 0, 'l');
            // Add the image
            $this->Image($logoUrl, 12, 7, 15, 15, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

            $this->Line(10, 70, 200, 70); // Example of adding a line (xs,ys,xe,ye)

        }
    }
   
    public function Footer() {
        // Position at 10 mm from bottom
        $this->SetY(-10);
        // Set font
        $this->SetFont('helvetica', 'I', 8);

        
        $this->Line(10, $this->getY(), 200, $this->getY()); // Example of adding a line (xs,ys,xe,ye)

        $left_margin = 10;
        $right_margin = 10;
        $this->SetX($left_margin);
        $this->setCellPadding(1,0,1,1);
        $cell_width = floor(($this->getPageWidth() - $left_margin - $right_margin) / 2);
        $this->MultiCell($cell_width, 5, $this->selectedSubjectCode, 0, 'L', 0, 0, '', '', true, 'T', 'M');
       
        $this->Cell($cell_width, 5, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, 1, 'R', 0, '', 0, false, 'T', 'M');

        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, '', 0, '', 0, false, 'T', 'M');
    }

}

?>