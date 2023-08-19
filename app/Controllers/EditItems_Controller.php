<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Edititems_model;

class EditItems_Controller extends BaseController
{
	public function index()
	{
		// $model = new Edititems_model();
		// $MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		$data['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
        $data['title'] = "Edit Items";
		// echo view('header');
		// echo view('menu', $MenuRights);
        echo view('EditItems_view', $data);
		// echo view('footer');
	}   

	public function showData()
	{
		$timeStart = microtime(TRUE);
		$model = new Edititems_model();
		$data['records'] = $model->getDataForReport($this->request);
		$timeEnd = microtime(TRUE);
		$data['timeTook'] = round( $timeEnd - $timeStart, 3 ) ;
		echo json_encode($data);
	}

	public function showDataWithDt()
	{
		$timeStart = microtime(TRUE);
		$model = new Edititems_model();
		$data['records'] = $model->getDataForReportWithDt($this->request);
		$timeEnd = microtime(TRUE);
		$data['timeTook'] = round( $timeEnd - $timeStart, 3 ) ;
		echo json_encode($data);
	}

	public function getClosingBalance()
	{
		$timeStart = microtime(TRUE);
		$model = new Edititems_model();
		$data['records'] = $model->getClosingBalance($this->request);
		$timeEnd = microtime(TRUE);
		$data['timeTook'] = round( $timeEnd - $timeStart, 3 ) ;
		echo json_encode($data);
	}

	public function showDataDeleted()
	{
		$model = new Edititems_model();
		$data['records'] = $model->getDataForReportDeleted($this->request);
		echo json_encode($data);
	}

	public function saveData()
	{
		$model = new Edititems_model();
		$model->insertNow($this->request);
		/// delete
		$model->deleteNow($this->request);
	}

	public function delete()
	{
		$model = new Edititems_model();
		$model->deleteNow($this->request);
		$data['records'] = $model->getDataForReport($this->request);
		echo json_encode($data);
	}

	public function undelete()
	{
		$model = new Edititems_model();
		$model->undelete($this->request);
	}

}
