<?php
//split pdf into smaller pdf files
class PDFsplit
{	
	//initialize values
	public $filename;
	public $split_method = 0;
	public $split_percentage = 0;
	public $ranges = 0; 

	public function __construct(){
	 	$this->file_directory  = $_SERVER['DOCUMENT_ROOT']."/pdfminions/storage/";
	 	require_once('fpdf/fpdf.php');
		require_once('fpdi/fpdi.php');
	}
	/**
	 * import inputs into class splitPDF  
	 * @param $filename
	 * @param $split_method: 1-split by percentage : 0-split by ranges specified by the user
	 * @return void
	 */
	public function inputs($filename,$split_method,$split_values){
	 	$this->filename = $filename;
	 	$this->split_method = $split_method;

	 	switch ($split_method) {
	 		case 1:
	 			$this->split_percentage = $split_values;
	 			$this->ranges  = $this->split_ranges();
	 			break;
	 		
	 		default:
	 			$this->ranges  = $split_values;
	 			break;
	 	}	
	}
	public function split(){
		if(!empty($this->ranges)){
			$zip = new ZipArchive();
			$filename = "../storage/split_file.zip";

			if ($zip->open($filename, ZipArchive::OVERWRITE)!==TRUE) {
			    exit("cannot open <$filename>\n");
			}			
		foreach ($this->ranges as $key => $range) {
			$split_pdf = new FPDI();
			$split_pdf->setSourceFile($this->filename);//open the file for every range to get the content
			for($i = $range[0]; $i <= $range[1]; $i++){
				$split_pdf->AddPage();
				$split_pdf->useTemplate($split_pdf->importPage($i));
			}
			$new_file = $range[0]."_".$range[1].".pdf";
			$split_pdf->Output($this->file_directory.$new_file,"F");
			$split_pdf->close();//close the file when done
			$zip->addFile("../storage/".$new_file,$new_file);
		}	
		$zip->close();
		}else{
			echo "Pdf must have more than 1 page or less than number of pdf files";
		}
	}
	/**
	 * Split the original pdf file into multiple page ranges
	 * @return $pdf_ranges 
	 */
	private function split_ranges(){
		$pdf = new FPDI();
		$percentage = $this->split_percentage;
		$pdf_count =  1/($percentage); //determine number of pdfs
		$pagecount = $pdf->setSourceFile($this->filename);
		if($pagecount > 1 && $pagecount >= $pdf_count){
		$pdf_ranges = array(); // contains the page ranges of each split pdfs
		
		for($i = 1; $i <= $pdf_count; $i++){
			if ($i == 1){
				$pdf_ranges[$i] = [1,floor($pagecount*$percentage)]; 
			}
			else{
				//if the total number of pages is divided evenly by the number of split pdfs 
				$pdf_ranges[$i] = [$start,floor($pagecount*$i*$percentage)];
			}
			$start = $pdf_ranges[$i][1] + 1;
		}
		}else{
			return ""; 
		}
		$pdf->close();
		return $pdf_ranges;
	}
}
//test
// $new_pdf = new PDFsplit;
// $new_pdf->inputs("sample_pdfs/one.pdf",1,50);
// $new_pdf->split();





?>