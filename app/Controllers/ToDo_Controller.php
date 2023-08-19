<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Todo_model;

class ToDo_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Todo_model();
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('ToDo_view', $data);
		echo view('footer');
	}  
	
	public function insert()
	{
		$model = new Todo_model();
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
		$model = new Todo_model();
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
		$model = new Todo_model();
		$model->deleteNow($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}
}
