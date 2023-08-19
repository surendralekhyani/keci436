<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Duplicates_model;

class Duplicates_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Duplicates_model();
		$data['records'] = $model->getDataAll();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Duplicates_view', $data);
		echo view('footer');
	}  
	

	public function showData()
	{
		$model = new Duplicates_model();
		$data['quotationDetail'] = $model->showQuotaionDetail($this->request);
		$data['cashSale'] = $model->showCashSale($this->request);
		$data['purchaseDetail'] = $model->showPurchaseDetail($this->request);
		$data['saleDetail'] = $model->showSaleDetail($this->request);
		echo json_encode($data);
	}

	public function replaceNow()
	{
		$model = new Duplicates_model();
		$model->replaceNow($this->request);
		$data['records'] = "ddd";
		echo json_encode($data);
	}

// 	public function delete()
// 	{
// 		// if($this->Util_model->isDependent('addressbook', 'prefixTypeRowId', $this->input->post('rowId')) == 1)
//   //       {
//   //       	$data['dependent'] = "yes";
//   //       	echo json_encode($data);
//   //       }
//   //       else
//         {
// 			$model = new Duplicates_model();
// 			$model->delete();
// 			$data['records'] = $model->getDataAll();
// 			echo json_encode($data);
// 		}
	// }

}
