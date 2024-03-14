<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Stocks_model;

class Stocks_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Stocks_model();
		// $data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		$data['stockList'] = $model->getStockList();

		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Stocks_view', $data);
		echo view('footer');
	}  

	
	public function showData()
	{
		$model = new Stocks_model();
		$data['records'] = $model->getDataLimit($this->request);
		$data['recordsCurrentStocks'] = $model->getCurrentStocks($this->request);
		echo json_encode($data);
	}

	public function insert()
	{
		$model = new Stocks_model();
		$err = array();
		$err[]= "error mili...";
		if( trim($this->request->getPost('stockName')) == "" )
		{
			$err[]= "Invalid Stock Name...";
		}
		if( $this->request->getPost('qty') <= 0 )
		{
			$err[]= "Invalid QTY...";
		}
		if( $this->request->getPost('rate') < 0 )
		{
			$err[]= "Invalid RATE...";
		}
		if(count($err) > 1)
		{
			echo json_encode($err);
			return;
		}

		$model->insertNow($this->request);
		$data['records'] = $model->getDataLimit($this->request);
		$data['recordsCurrentStocks'] = $model->getCurrentStocks($this->request);
		echo json_encode($data);
	}


	public function saveEditedStock()
	{
		$model = new Stocks_model();
		$model->saveEditedStock($this->request);
		$data['tmp'] = 'tmp';
		echo json_encode($data);
	}

	
	public function getStockLedger()
	{
		$model = new Stocks_model();
		// $data['records'] = $model->getStockLedger($this->request);
		$data['records'] = $model->loadAllOfThisStock($this->request);
		echo json_encode($data);
	}
	public function loadAllOfThisStock()
	{
		$model = new Stocks_model();
		$data['records'] = $model->loadAllOfThisStock($this->request);
		echo json_encode($data);
	}

	public function getProfitOfSattled()
	{
		$model = new Stocks_model();
		$data['profit'] = $model->getProfitOfSattled($this->request);
		echo json_encode($data);
	}


	public function loadAllRecords()
	{
		$model = new Stocks_model();
		$data['records'] = $model->loadAllRecords($this->request);
		$data['recordsCurrentStocks'] = $model->getCurrentStocks($this->request);
		echo json_encode($data);
	}
	public function editCurrentStocks()
	{
		$model = new Stocks_model();
		$data['records'] = $model->editCurrentStocks($this->request);
		echo json_encode($data);
	}
	public function saveEditedCurrentStock()
	{
		$model = new Stocks_model();
		$model->saveEditedCurrentStock($this->request);
		$data['tmp'] = 'tmp';
		echo json_encode($data);
	}
}
