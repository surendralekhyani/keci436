<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Rptitemspurchaseandsoldpaging_model;

class RptItemsPurchaseAndSoldPaging_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Rptitemspurchaseandsoldpaging_model();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
        // $model = new CustomerModel();
 
        $data1 = [
            'customers' => $model->paginate(50),
            'pager' => $model->pager
        ];
        
        // // return view('index', $customers);
        $data['dtTo'] = "";
        $data['dtFrom'] = "";
        $data['vTypes'] = "";
        $data['search'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('RptItemsPurchaseAndSoldPaging_view', $data);
		echo view('footer');
	}  
	

	public function showData()
	{
        $myModel = new Rptitemspurchaseandsoldpaging_model();

        // $myModel = new MyModel();
        $searchData = $this->request->getGet();
        $data['dtTo'] = "";
        $data['dtFrom'] = "";
        $data['vTypes'] = "";
        $data['search'] = "";
        if(isset($searchData) && isset($searchData['dtTo']) && isset($searchData['dtFrom']) && isset($searchData['cboVoucherType']))
        {
            $data['dtTo'] = $searchData['dtTo'];
            $data['dtFrom'] = $searchData['dtFrom'];
            $data['vTypes'] = $searchData['cboVoucherType'];
            $data['search'] = $searchData['searchWhat'];
        }
        if( $searchData['cboVoucherType'] == "Sale" )
		{
            $contest_images = $myModel->getDataForReportSale($this->request);
        }
        else if( $searchData['cboVoucherType'] == "Purchase" )
		{
            $contest_images = $myModel->getDataForReportPurchase($this->request);
        }
        $pager=service('pager');
        $page=(int)(($this->request->getVar('page')!==null)?$this->request->getVar('page'):1)-1;
        // d($page);
        $perPage =  500;
        // d($contest_images);
        $total = count($contest_images);
        $pager->makeLinks($page+1, $perPage, $total);
        $offset = $page * $perPage;
        // d($this->request->getVar('dtTo'));
        if( $searchData['cboVoucherType'] == "Sale" )
		{
            $data['customers'] = $myModel->getDataForReportSale($this->request, $perPage, $offset);
        }
        else if( $searchData['cboVoucherType'] == "Purchase" )
		{
            $data['customers'] = $myModel->getDataForReportPurchase($this->request, $perPage, $offset);
        }
        
        
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('RptItemsPurchaseAndSoldPaging_view', $data);
		echo view('footer');
	}


}
