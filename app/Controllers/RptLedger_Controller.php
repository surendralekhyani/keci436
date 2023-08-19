<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Rptledger_model;

class RptLedger_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Rptledger_model();
		// $data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
		$data['customers'] = $this->modelUtil->getCustomerWithBalance();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('RptLedger_view', $data);
		echo view('footer');
	}  

	public function yeParty($cid='-1')
	{
		$model = new Rptledger_model();
		// $data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
		$data['customers'] = $this->modelUtil->getCustomerWithBalance();
        $data['errMsg'] = "";
        // $data['cid'] = $cid;
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('RptLedger_view', $data);
		echo view('footer');
	} 

	public function showData()
	{
		$model = new Rptledger_model();
		$data['opBal'] = $model->getOpeningBal($this->request);
		$data['records'] = $model->getDataForReport($this->request);
		echo json_encode($data);
	}

	// public function exportData()
	// {
	// 	$this->printToExcel();
	// }
	

	public function getSaleDetail()
	{
		$model = new Rptledger_model();
		$data['records'] = $model->getSaleDetail($this->request);
		// $data['recordsSr'] = $model->getSaleDetailSr();
		echo json_encode($data);
	}

	public function getPurchaseDetail()
	{
		$model = new Rptledger_model();
		$data['records'] = $model->getPurchaseDetail($this->request);
		echo json_encode($data);
	}
}
