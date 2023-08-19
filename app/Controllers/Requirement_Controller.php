<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Requirement_model;

class Requirement_Controller extends BaseController
{
	public function index()
	{
		$model = new Requirement_model();
		$data['itemList'] = $model->getItemList();
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Requirement_view', $data);
		echo view('footer');
	}  
	
	public function insert()
	{
		if( trim($this->request->getPost('itemName')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Requirement_model();
		$model->insertNow($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}

	

	public function delete()
	{
		$model = new Requirement_model();
		$model->deleteNow($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}

	

	public function getPurchaseLog()
	{
		$model = new Requirement_model();
		$data['records'] = $model->getPurchaseLog($this->request);
		echo json_encode($data);
	}


	public function deleteChecked()
	{
		$model = new Requirement_model();
		$model->deleteChecked($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
    }

}
