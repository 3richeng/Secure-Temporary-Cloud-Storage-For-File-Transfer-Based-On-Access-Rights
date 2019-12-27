<?php

session_start();

require 'db.php';
require 'phpSpreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_SESSION['username']))
{

$username = $_SESSION['username'];

//create phpSpreadsheet object
$spreadsheet = new Spreadsheet(); 
$Excel_writer = new Xls($spreadsheet); 
$spreadsheet->createSheet();

//Select worksheet Completed (0)
$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();

$spreadsheet->getActiveSheet()->setTitle("Completed");

$pending = mysqli_query($db_conn,"SELECT * from files WHERE status ='Completed' AND owner = '$username'");
$row = 4;

//Insert title to cell
$spreadsheet->getActiveSheet()
    ->setCellValue('A1', 'List Of Your Completed Uploaded File')
    ->setCellValue('A3', 'ID')
    ->setCellValue('B3', 'NAME')
    ->setCellValue('C3', 'TYPE/FORMAT')
    ->setCellValue('D3', 'SIZE')
    ->setCellValue('E3', 'DATE')
    ->setCellValue('F3', 'DESCRIPTION')
    ->setCellValue('G3', 'STATUS')
    ->setCellValue('H3', 'PUBLICITY')
    ->setCellValue('I3', 'MESSAGE DIGEST 5 (MD5)')
    ->setCellValue('J3', 'SECURE HASHING ALGORITHM (SHA1)');

//Insert data to cell
while($data = mysqli_fetch_assoc($pending)){
    $activeSheet
        ->setCellValue('A'.$row, $data['id'])
        ->setCellValue('B'.$row, $data['name'])
        ->setCellValue('C'.$row, $data['type'])
        ->setCellValue('D'.$row, $data['size'])
        ->setCellValue('E'.$row, $data['date'])
        ->setCellValue('F'.$row, $data['description'])
        ->setCellValue('G'.$row, $data['status'])
        ->setCellValue('H'.$row, $data['publicity'])
        ->setCellValue('I'.$row, $data['md5'])
        ->setCellValue('J'.$row, $data['sha1']);
    
    $row++;
}


//Set column width
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(40);




//Merging the title
$spreadsheet->getActiveSheet()->mergeCells('A1:I1');

//Aligning
$spreadsheet->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal('center');
$spreadsheet->getActiveSheet()->getStyle("A3:J3")->getAlignment()->setHorizontal('center');

//Set font
$spreadsheet->getActiveSheet()->getStyle("A1:G1")->getFont()->setSize(18);


//Title Style
$spreadsheet->getActiveSheet()->getStyle('A3:J3')->applyFromArray(
    array(
        'font' => array(
            'bold' => true
        ),
        'borders' => array(
            'outline' => array(
                'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
            ),
            'vertical' => array(
                'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
            )
        
        )
    )
);

//Borders for data  
$spreadsheet->getActiveSheet()->getStyle('A4:J'.($row-1))->applyFromArray(
    array(
        'borders' => array(
            'outline' => array(
                'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
            ),
            'vertical' => array(
                'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
            )
        
        )
    )
);

//Select Worksheet Pending(1)
$spreadsheet->setActiveSheetIndex(1);
$activeSheet = $spreadsheet->getActiveSheet();
$spreadsheet->getActiveSheet()->setTitle("Pending");

$pending = mysqli_query($db_conn,"SELECT * from files WHERE status ='Pending' AND owner ='$username'");
$row = 4;

//Insert title to cell
$spreadsheet->getActiveSheet()
    ->setCellValue('A1', 'List Of Your Pending Uploaded File')
    ->setCellValue('A3', 'ID')
    ->setCellValue('B3', 'NAME')
    ->setCellValue('C3', 'TYPE/FORMAT')
    ->setCellValue('D3', 'SIZE')
    ->setCellValue('E3', 'DATE')
    ->setCellValue('F3', 'DESCRIPTION')
    ->setCellValue('G3', 'STATUS')
    ->setCellValue('H3', 'PUBLICITY')
    ->setCellValue('I3', 'MESSAGE DIGEST 5 (MD5)')
    ->setCellValue('J3', 'SECURE HASHING ALGORITHM (SHA1)');

//Insert data to cell
while($data = mysqli_fetch_assoc($pending)){
    $activeSheet
        ->setCellValue('A'.$row, $data['id'])
        ->setCellValue('B'.$row, $data['name'])
        ->setCellValue('C'.$row, $data['type'])
        ->setCellValue('D'.$row, $data['size'])
        ->setCellValue('E'.$row, $data['date'])
        ->setCellValue('F'.$row, $data['description'])
        ->setCellValue('G'.$row, $data['status'])
        ->setCellValue('H'.$row, $data['publicity'])
        ->setCellValue('I'.$row, $data['md5'])
        ->setCellValue('J'.$row, $data['sha1']);
    
    $row++;
}

//Merge title cells
$spreadsheet->getActiveSheet()->mergeCells('A1:I1');

//Set dimensions
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(40);

//Merging the title
$spreadsheet->getActiveSheet()->mergeCells('A1:I1');

//Aligning
$spreadsheet->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal('center');
$spreadsheet->getActiveSheet()->getStyle("A3:J3")->getAlignment()->setHorizontal('center');

//Set font
$spreadsheet->getActiveSheet()->getStyle("A1:G1")->getFont()->setSize(18);


//Title Style
$spreadsheet->getActiveSheet()->getStyle('A3:J3')->applyFromArray(
    array(
        'font' => array(
            'bold' => true
        ),
        'borders' => array(
            'outline' => array(
                'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
            ),
            'vertical' => array(
                'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
            )
        
        )
    )
);
//Borders for data  
$spreadsheet->getActiveSheet()->getStyle('A4:J'.($row-1))->applyFromArray(
    array(
        'borders' => array(
            'outline' => array(
                'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
            ),
            'vertical' => array(
                'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
            )
        
        )
    )
);


//Download
try{
    
    header("Content-type: application/vnd.ms-excel");
    header('Content-Disposition: attachment;filename="FileData.xls"');
    header('Cache-Control: max-age=0');
 
    $Excel_writer->save('php://output');
    
}
catch(Exception $e)
{
    echo "false";
}
    
}

