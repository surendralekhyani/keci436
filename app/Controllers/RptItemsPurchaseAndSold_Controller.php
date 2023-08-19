<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Rptitemspurchaseandsold_model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RptItemsPurchaseAndSold_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Rptitemspurchaseandsold_model();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('RptItemsPurchaseAndSold_view', $data);
		echo view('footer');
	}  
	

	public function showData()
	{
		$timeStart = microtime(TRUE);
		$model = new Rptitemspurchaseandsold_model();

		if( $this->request->getPost('vType') == "ALL" )
		{
			// $data['records'] = $model->getDataForReportSale($this->request);
			// $data['recordsPurchase'] = $model->getDataForReportPurchase($this->request);
			// $data['recordsQuotation'] = $model->getDataForReportQuotation($this->request);
			// $data['recordsCashSale'] = $model->getDataForReportCashSale($this->request);
			// $data['records'] = array_merge($data['records'], $data['recordsPurchase'], $data['recordsQuotation'], $data['recordsCashSale']);
		}
		else if( $this->request->getPost('vType') == "Sale" )
		{
			$data['records'] = $model->getDataForReportSale($this->request);
			// $data['recordsPurchase'] = "";
			// $data['recordsQuotation'] = "";
			// $data['recordsCashSale'] = "";
			// $data['records'] = array_merge($data['records']);
		}
		else if( $this->request->getPost('vType') == "Purchase" )
		{
			// $data['records'] = "";
			$data['records'] = $model->getDataForReportPurchase($this->request);
			// $data['recordsQuotation'] = "";
			// $data['recordsCashSale'] = "";
			// $data['records'] = array_merge($data['recordsPurchase']);
		}
		else if( $this->request->getPost('vType') == "Quotation" )
		{
			// $data['records'] = "";
			// $data['recordsPurchase'] = "";
			$data['records'] = $model->getDataForReportQuotation($this->request);
			// $data['recordsCashSale'] = "";
			// $data['records'] = array_merge($data['recordsQuotation']);
		}
		else if( $this->request->getPost('vType') == "Cash Sale" )
		{
			// $data['records'] = "";
			// $data['recordsPurchase'] = "";
			// $data['recordsQuotation'] = "";
			$data['records'] = $model->getDataForReportCashSale($this->request);
			// $data['records'] = array_merge($data['recordsCashSale']);
		}
		$timeEnd = microtime(TRUE);
		$data['timeTook'] = round( $timeEnd - $timeStart, 3 ) ;
		
		echo json_encode($data);
	}

	public function showDataExcel()
	{
		ini_set('memory_limit', '-1');
		$file_name = 'data.xlsx';

		$model = new Rptitemspurchaseandsold_model();
		if( $this->request->getPost('vType') == "Sale" )
		{
			$data = $model->getDataForReportSale($this->request);
		}
		else if( $this->request->getPost('vType') == "Purchase" )
		{
			$data = $model->getDataForReportPurchase($this->request);
		}
		

		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A3', 'Date');
		$sheet->setCellValue('B3', 'Party');
		$sheet->setCellValue('C3', 'Item');
		$sheet->setCellValue('D3', 'Qty');
		$sheet->setCellValue('E3', 'Rate');
		$sheet->setCellValue('F3', 'Amt');
		$sheet->setCellValue('G3', 'D%');
		$sheet->setCellValue('H3', 'Damt');
		$sheet->setCellValue('I3', 'CGST');
		$sheet->setCellValue('J3', 'SGST');
		$sheet->setCellValue('K3', 'Net');
		$sheet->setCellValue('L3', 'PerPc');
		$sheet->setCellValue('M3', 'SP');

		$count = 4;

		foreach($data as $row)
		{
			$sheet->setCellValue('A' . $count, $row['dt']);
			$sheet->setCellValue('B' . $count, $row['customerName']);
			$sheet->setCellValue('C' . $count, $row['itemName']);
			$sheet->setCellValue('D' . $count, $row['qty']);
			$sheet->setCellValue('E' . $count, $row['rate']);
			$sheet->setCellValue('F' . $count, $row['amt']);
			$sheet->setCellValue('G' . $count, $row['discountPer']);
			$sheet->setCellValue('H' . $count, $row['discountAmt']);
			$sheet->setCellValue('I' . $count, $row['cgstAmt']);
			$sheet->setCellValue('J' . $count, $row['sgstAmt']);
			$sheet->setCellValue('K' . $count, $row['netAmt']);
			if($row['qty'] > 0)
			{
				$sheet->setCellValue('L' . $count, $row['netAmt'] / $row['qty']);
			}
			$sheet->setCellValue('M' . $count, $row['sp']);

			$count++;
		}

		$writer = new Xlsx($spreadsheet);

		$writer->save($file_name);

		// header("Content-Type: application/vnd.ms-excel");

		// header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');

		// header('Expires: 0');

		// header('Cache-Control: must-revalidate');

		// header('Pragma: public');

		// header('Content-Length:' . filesize($file_name));

		// flush();

		// readfile($file_name);
		$dt = date("Y_m_d");
		$tm = date("H_i_s");
		echo base_url()."/public/data" . ".xlsx";
		// echo $file_name;
		// exit;

	}
}
