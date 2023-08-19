<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Addressbook_model;

class AddressBook_Controller extends BaseController
{
	public function index()
	{
		$model = new Addressbook_model();
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('AddressBook_view', $data);
		echo view('footer');
	}  
	

	public function insert()
	{
		$model = new Addressbook_model();
		if($model->checkDuplicate($this->request) == 1)
        {
        	$data = "Duplicate record...";
        	echo json_encode($data);
        }
        else
        {
			$model->insertNow($this->request);
			$data['records'] = $model->getDataLimit();
			echo json_encode($data);
        }
	}

	public function update()
	{
		$model = new Addressbook_model();
		if($model->checkDuplicateOnUpdate($this->request) == 1)
        {
        	$data = "Duplicate record...";
        	echo json_encode($data);
        }
        else
        {
			$model->updateNow($this->request);
			$data['records'] = $model->getDataLimit();
			echo json_encode($data);
        }
	}

	public function delete()
	{
		$model = new Addressbook_model();
		$model->deleteNow($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}

	// public function loadAllRecords()
	// {
	// 	$data['records'] = $model->getDataAll();
	// 	echo json_encode($data);
	// }


}
