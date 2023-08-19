<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Reminders_model;

class Reminders_Controller extends BaseController
{
	public function index()
	{
		$model = new Reminders_model();
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Reminders_view', $data);
		echo view('footer');
	}  

	public function insert()
	{
		if( trim($this->request->getPost('remarks')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Reminders_model();
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
		if( trim($this->request->getPost('remarks')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Reminders_model();
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
		$model = new Reminders_model();
		$model->deleteNow($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}

	// public function loadAllRecords()
	// {
	// 	$data['records'] = $this->Reminders_model->getDataAll();
	// 	echo json_encode($data);
	// }

}
