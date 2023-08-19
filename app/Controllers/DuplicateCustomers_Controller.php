<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Duplicatecustomers_model;

class DuplicateCustomers_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Duplicatecustomers_model();
		$data['records'] = $model->getDataAll();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('DuplicateCustomers_view', $data);
		echo view('footer');
	}  
	
	public function showData()
	{
		$model = new Duplicatecustomers_model();
		$data['quotation'] = $model->showQuotaion($this->request);
		$data['ledger'] = $model->showLedger($this->request);
		$data['purchase'] = $model->showPurchase($this->request);
		$data['sale'] = $model->showSale($this->request);
		echo json_encode($data);
	}

	public function replaceNow()
	{
		$model = new Duplicatecustomers_model();
		$model->replaceNow($this->request);
		$data['records'] = "ddd";
		echo json_encode($data);
	}
}
