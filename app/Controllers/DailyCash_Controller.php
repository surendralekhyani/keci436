<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Dailycash_model;

class DailyCash_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Dailycash_model();
		// $data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
		$data['opBal'] = $model->getOpeningBal();
		$data['records'] = $model->getDataLimit();
		$data['plusDues'] = $model->getPlusDues();
		$data['minusDues'] = $model->getMinusDues();
		$data['purchaseSum'] = $model->getPurchaseSum();
		$data['paymentsSum'] = $model->getPaymentsSum();
		$data['upiCollection'] = $model->getUpiCollection();
		$data['deepuSuriBank'] = $model->getDeepuSuriBank();
		$data['stocksInvested'] = $model->getStocksInvested();
        echo view('DailyCash_view', $data);
		echo view('footer');
	}  
	

	public function insert()
	{
		$model = new Dailycash_model();
		$model->insertNow($this->request);
		if( $this->request->getPost('inOutMode') == "OUT" &&  $this->request->getPost('upiAmt') > 0 )
		{
			$model->saveUpiAmt($this->request);
		}
		$data['opBal'] = $model->getOpeningBal();
		$data['records'] = $model->getDataLimit();
		$data['plusDues'] = $model->getPlusDues();
		$data['minusDues'] = $model->getMinusDues();
		$data['purchaseSum'] = $model->getPurchaseSum();
		$data['paymentsSum'] = $model->getPaymentsSum();
		$data['upiCollection'] = $model->getUpiCollection();
		$data['deepuSuriBank'] = $model->getDeepuSuriBank();
		$data['stocksInvested'] = $model->getStocksInvested();
		echo json_encode($data);
	}

	public function showDataAll()
	{
		$model = new Dailycash_model();
		$data['opBal'] = '';
		$data['records'] = $model->getDataAll();
		echo json_encode($data);
	}

	

	public function loadIntervalJobs()
    {
		$model = new Dailycash_model();
        $data['dailyCashInEntry'] = $model->dailyCashInEntry();
        echo json_encode($data);
    }

    public function deleteOldData()
    {
		$model = new Dailycash_model();
    	if($model->thisDateMustBeThare($this->request) == 0)
        {
        	$data = "This Date Not Found";
        	echo json_encode($data);
        }
        else
        {
	        $model->deleteOldData($this->request);
	        echo json_encode("done..");
	    }
    }

	public function saveUpiAmt()
    {
		$model = new Dailycash_model();
		$model->saveUpiAmt($this->request);
		// $data['upiCollection'] = $model->getUpiCollection();
		// $data['deepuSuriBank'] = $model->getDeepuSuriBank();
		// echo json_encode($data);
		echo json_encode("sd");
		// header("Refresh:0");
    }
}
