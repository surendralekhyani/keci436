<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Family_model;

class Family_Controller extends BaseController
{
	public function index()
	{
		$model = new Family_model();
		$data['familyList'] = $model->getFamilyList();
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Family_view', $data);
		echo view('footer');
	}  


	public function insert()
	{
		if( trim($this->request->getPost('name')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Family_model();
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
		if( trim($this->request->getPost('name')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
    	// sleep(22);
		$model = new Family_model();
		if($model->checkDuplicateOnUpdate($this->request) == 1)
        {
        	$data = "Duplicate record...";
        	echo json_encode($data);
        }
        else
        {
			$data['affectedRows'] = $model->updateNow($this->request);
			$data['records'] = $model->getDataLimit();
			echo json_encode($data);
        }
	}

	public function delete()
	{
    	// sleep(22);
		$model = new Family_model();
		if($model->isDependent($this->request->getPost('rowId')) == 1)
        {
        	$data['dependent'] = "yes";
        	echo json_encode($data);
        }
        else
        {
			$model->deleteNow($this->request);
			$data['records'] = $model->getDataLimit();
			echo json_encode($data);
		}
	}

	public function getChildren()
	{
		$model = new Family_model();
		$data['records'] = $model->getChildren($this->request);
		echo json_encode($data);
	}

	public function saveChildOrder()
	{
		$model = new Family_model();
		$model->saveChildOrder($this->request);
	}

	public function showDataForBulkEdit()
	{
		$model = new Family_model();
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}
	public function saveBulkEdit()
	{
		$model = new Family_model();
		$model->insertBulkEdit($this->request);

		// $data['records'] = $model->getDataLimit();
		// echo json_encode($data);
	}
}
