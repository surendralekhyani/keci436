<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Conclusions_model;

class Conclusions_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Conclusions_model();
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Conclusions_view', $data);
		echo view('footer');
	}  

	public function insert()
	{
		$model = new Conclusions_model();
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
		$model = new Conclusions_model();
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
		$model = new Conclusions_model();
		$model->deleteNow($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}

}
