<?php
require('../scripts/fpdf/fpdf.php');
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(60,20,"PDF Test");
$pdf->Output();