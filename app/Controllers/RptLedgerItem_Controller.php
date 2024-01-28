<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Rptledgeritem_model;

class RptLedgerItem_Controller extends BaseController
{

	
	public function index($argCustomerRowId=-1)
	{
		$model = new Rptledgeritem_model();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
		// $data['customers'] = $this->modelUtil->getCustomerWithBalance();
		$data['items'] = $model->getItemList();

        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('RptLedgerItem_view', $data);
		echo view('footer');
	}  

	public function showData()
	{
		$model = new Rptledgeritem_model();
		$data['opBal'] = $model->getOpeningBal($this->request);
		$data['purchase'] = $model->getPurchase($this->request);
		$data['sale'] = $model->getSale($this->request);
		// $data['cashSale'] = $model->getCashSale();
		echo json_encode($data);
	}

	public function yeItem($cid='-1')
	{
		$model = new Rptledgeritem_model();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
		$data['items'] = $model->getItemList();
		// $data['itemName'] = $model->getItemName();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('RptLedgerItem_view', $data);
		echo view('footer');
	} 

}
