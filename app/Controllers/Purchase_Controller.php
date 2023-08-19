<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Purchase_model;

class Purchase_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Purchase_model();
		$data['customers'] = $this->modelUtil->getCustomerWithBalance();
		$data['items'] = $model->getItems();
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Purchase_view', $data);
		echo view('footer');
	}  

	public function insert()
	{
		if( trim($this->request->getPost('customerRowId')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Purchase_model();
		$data['purchaseRowId'] = $model->insertNow($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}

	public function showDetailOnUpdate()
	{
		$model = new Purchase_model();
		$data['records'] = $model->showDetail($this->request);
		$data['customerInfo'] = $model->getCustomerInfo($this->request);
		echo json_encode($data);
	}

	public function checkForUpdate()
	{
		$model = new Purchase_model();
		if($model->checkForUpdate() == 1)
        {
        	$data = "cant";
        	echo json_encode($data);
        }
	}
	
	public function update()
	{
		if( trim($this->request->getPost('customerRowId')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Purchase_model();
		$model->updateNow($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}


	public function delete()
	{
		$model = new Purchase_model();
		if($model->checkPossibility() == 1)
        {
        	$data = "yes";
        	echo json_encode($data);
        }
        else
        {
			$model->deleteNow($this->request);
			$data['records'] = $model->getDataLimit();
			echo json_encode($data);
		}
	}

	public function loadAllRecords()
	{
		$model = new Purchase_model();
		$data['records'] = $model->getDataAll();
		echo json_encode($data);
	}

	public function searchRecords()
	{
		$model = new Purchase_model();
		$data['records'] = $model->searchRecords($this->request);
		echo json_encode($data);
	}

	public function getPurchaseDetial()
	{
		$model = new Purchase_model();
		$data['purchaseDetail'] = $model->getPurchaseDetail($this->request);
		echo json_encode($data);
	}

	public function getPurchaseLog()
	{
		$model = new Purchase_model();
		$data['records'] = $model->getPurchaseLog($this->request);
		echo json_encode($data);
	}
}
