<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Rptdues_model;
use App\Models\Rptledger_model;

class RptDues_Controller extends BaseController
{
	public function index()
	{
		$model = new Rptdues_model();
		$data['customers4Ledger'] = $model->getCustomerList();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('RptDues_view', $data);
		echo view('footer');
	} 
	

	public function showData()
	{
		$model = new Rptdues_model();
		$data['records'] = $model->getDues();
		$data['recordsNegative'] = $model->getDuesNegative();
		echo json_encode($data);
	}
	


	public function receiveAmt()
	{
		$model = new Rptdues_model();
		if($model->checkDuplicate($this->request) == 1)
        {
        	$data = "This amt for this Party on this Date already saved... Try diff. amt...";
        	echo json_encode($data);
        }
		else
		{
			$model->receiveAmt($this->request);
			$data['records'] = $model->getDues();
			$data['recordsNegative'] = $model->getDuesNegative();
			echo json_encode($data);
		}
	}

	public function payAmt()
	{
		$model = new Rptdues_model();
		$model->payAmt($this->request);
		$data['records'] = $model->getDues();
		$data['recordsNegative'] = $model->getDuesNegative();
		echo json_encode($data);
	}


	public function markDoobat()
	{
		$model = new Rptdues_model();
		$model->markDoobat($this->request);
		$data['records'] = $model->getDues();
		$data['recordsNegative'] = $model->getDuesNegative();
		echo json_encode($data);
	}



	function deleteOldRecs()
	{
		$model = new Rptdues_model();
		if( $this->request->getPost('p') != "MunnaBhai93520" )
		{
	        echo json_encode("Invalid...");
		}
		else
		{
			$model->deleteOldRecs($this->request);
		}
		echo json_encode("done...");
	}
}
