<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\User_model;

class User_Controller extends BaseController
{
	public function index()
	{
		$model = new User_model();
		$data['records'] = $model->getData('rowid','uid','pwd');
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('User_view', $data);
		echo view('footer');
	}   

	public function insertUser()
	{
		$model = new User_model();
		if($model->checkDuplicate($this->request) == 1)
        {
        	$data = "Duplicate record...";
        	echo json_encode($data);
        }
        else
        {
			$model->insertNow($this->request);
			$data['records'] = $model->getData('rowid','uid','pwd');
			echo json_encode($data);
        }
	}


	public function updateUser()
	{
		$model = new User_model();
		if($model->checkDuplicateOnUpdate($this->request) == 1)
        {
        	$data = "Duplicate record...";
        	echo json_encode($data);
        }
        else
        {
			$model->updateNow($this->request);
			$data['records'] = $model->getData('rowid','uid','pwd');
			echo json_encode($data);
        }
	}

	public function deleteUser()
	{
		$model = new User_model();
		// $this->User_model->delRow();
		$model->deleteNow($this->request);
		$data['records'] = $model->getData('rowid','uid','pwd');
		echo json_encode($data);
	}
}