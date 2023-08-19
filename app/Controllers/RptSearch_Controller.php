<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Rptsearch_model;

class RptSearch_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Rptsearch_model();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('RptSearch_view', $data);
		echo view('footer');
	}  


	public function showData()
	{
		$model = new Rptsearch_model();
		$data['ledgerData'] = $model->getLedgerData($this->request);
		$data['reminderData'] = $model->getReminderData($this->request);
		$data['datesData'] = $model->getDatesData($this->request);
		$data['cashSaleData'] = $model->getCashSaleData($this->request);
		echo json_encode($data);
	}
}
