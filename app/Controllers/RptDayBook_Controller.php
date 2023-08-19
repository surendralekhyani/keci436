<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Rptdaybook_model;

class RptDayBook_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Rptdaybook_model();
		// $data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('RptDayBook_view', $data);
		echo view('footer');
	}  

	
	public function showData()
	{
		$model = new Rptdaybook_model();
		$data['records'] = $model->getDataForReport($this->request);
		echo json_encode($data);
	}

	// public function exportData()
	// {
	// 	$this->printToExcel();
	// }
	

	public function getSaleDetail()
	{
		$model = new Rptdaybook_model();
		$data['records'] = $model->getSaleDetail($this->request);
		// $data['recordsSr'] = $model->getSaleDetailSr();
		echo json_encode($data);
	}

	public function getPurchaseDetail()
	{
		$model = new Rptdaybook_model();
		$data['records'] = $model->getPurchaseDetail($this->request);
		echo json_encode($data);
	}
}
