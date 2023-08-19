<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Paymentreceipt_model;
use App\Models\Rptledger_model;

class PaymentReceipt_Controller extends BaseController
{
	public function index()
	{
		$model = new Paymentreceipt_model();
		$data['customers'] = $this->modelUtil->getCustomerWithBalance();
		$data['customers4Ledger'] = $model->getCustomerList();
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('PaymentReceipt_view', $data);
		echo view('footer');
	}  


	public function insert()
	{
		if( trim($this->request->getPost('customerRowId')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Paymentreceipt_model();
		$customerRowId = $this->request->getPost('customerRowId');
        if( $customerRowId == -1 ) ///new customer (Check duplicate)
        {
        	if($model->checkDuplicateNewCustomer($this->request) == 1)
	        {
	        	$data = "Duplicate new customer...";
	        	echo json_encode($data);
	        }
	        else
	        {
				$model->insertNow($this->request);
				$data['records'] = $model->getDataLimit();
				echo json_encode($data);
			}
        }
        else
        {
			$model->insertNow($this->request);
			$data['newBalance'] = $model->getCustomerNewBalance($this->request);
			$data['records'] = $model->getDataLimit();
			$modelLedger = new Rptledger_model();
			$data['opBal'] = $modelLedger->getOpeningBal($this->request);
			$data['records4Ledger'] = $modelLedger->getDataForReport($this->request);
			echo json_encode($data);
		}
	}


	public function showDetailOnUpdate()
	{
		// $data['customerInfo'] = $this->Paymentreceipt_model->getCustomerInfo();
		// echo json_encode($data);
	}

	public function checkForUpdate()
	{
		// if($this->Paymentreceipt_model->checkForUpdate() == 1)
  //       {
  //       	$data = "cant";
  //       	echo json_encode($data);
  //       }
	}
	
	public function update()
	{
		// if($this->input->post('customerRowId') == 98)
  //       {
		// 	$data['oldRecord'] = $this->Paymentreceipt_model->getOldRecord();
		// 	//////
		// 	$mobile = "9929598700";
		// 	$sms = "Voucher Edit: " . $data['oldRecord'][0]['vType'] . $data['oldRecord'][0]['refRowId'] . ", ". $data['oldRecord'][0]['amt']  . "/". $data['oldRecord'][0]['recd'] . ", ". $this->input->post('amt');

		//     $this->sendSms($mobile, $sms);
		// }
		// /////
		// $this->Paymentreceipt_model->update();
		// $data['records'] = $this->Paymentreceipt_model->getDataLimit();
		// echo json_encode($data);
	}

	

	public function delete()
	{
		$model = new Paymentreceipt_model();
		$model->deleteNow($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}

	public function loadAllRecords()
	{
		$model = new Paymentreceipt_model();
		$data['records'] = $model->getDataAll();
		echo json_encode($data);
	}

	public function showData()
	{
		$model = new Rptledger_model();
		$data['opBal'] = $model->getOpeningBal($this->request);
		$data['records'] = $model->getDataForReport($this->request);
		echo json_encode($data);
	}

}
