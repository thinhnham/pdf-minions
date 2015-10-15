
<?php
require_once('../php_libraries/PDFsplit.php');
// split PDF file
$filename = $_FILES['file']['tmp_name'];
$split_method  = $_POST['split_method'];
$split_values = "";
if ($split_method == 1){
	$split_values = 1/floatval($_POST['percentage']);
}else{
	$to = $_POST['to'];
	$from = $_POST['from'];
	$split_values =array();
	foreach ($from as $key => $value) {
		if(!empty($value) && !empty($to[$key])){
	 		$split_values[$key+1] = [$value,$to[$key]];
	 	}
	 } 
}

$splitPDF = new PDFsplit;
$splitPDF->inputs($filename,$split_method,$split_values);
$splitPDF->split();
unset($splitPDF);

echo "<center><h4> Click To Download</h4>
<a href='storage/split_file.zip'><img src='images/output.png' style='width:80px;height:80px'/></a></center>";
?>