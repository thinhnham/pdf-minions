<?php
$name = $_POST['name'];
$files  = $_FILES['file'];
$results = "";
if ($name == ''){
	$name = "merged";
}

$url = $_SERVER['DOCUMENT_ROOT'].'/pdfminions/storage/'.$name.'.pdf';
include($_SERVER['DOCUMENT_ROOT'].'/pdfminions/php_libraries/PDFMerger.php');
$pdf = new PDFMerger();

foreach ($files['tmp_name'] as $key => $value) {
	if($value != ""){
	$pdf->addPDF($value, 'all');
	}
}
$pdf->merge('file',$url);

$zip = new ZipArchive();
$filename = "../storage/merged_file.zip";
if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
}		
$zip->addFile("../storage/".$name.'.pdf',$name.'.pdf');
$zip->close();



$results = "<center><h4>Click To Download</h4><a target='_blank' href='storage/merged_file.zip'><img src='images/output.png' style='width:80px;height:80px'/></a></center>";

echo $results;
?>