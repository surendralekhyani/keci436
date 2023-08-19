<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Customers_model;
use App\Models\Rptledger_model;

class Customers_Controller extends BaseController
{
	public function getDataLimit($n=0)
	{
		$builder = $this->db->table('customers');
		$query = $builder->select('*')
			                 ->where('deleted', 'N')
			                 ->orderBy('customerRowId desc')
			                 ->get($n);
		return($query->getResultArray());
	}

	public function index()
	{
		$model = new Customers_model();
		$data['records'] = $this->getDataLimit(5000);
		$data['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
        $data['title'] = "Customers";
        echo view('Customers_view', $data);
	}  
	
	public function insert()
	{
		$model = new Customers_model();
		$duplicate = $model->asArray()->where('customerName', $this->request->getPost('customerName'))->first();
		if( $duplicate > 0 )
        {
        	$data = "Duplicate record...";
        	echo json_encode($data);
			return;
        }
		$data = array(
			// 'customerRowId' => $current_row
			'customerName' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('customerName'))
			, 'address' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('address'))        
			, 'mobile1' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('mobile1'))
			, 'mobile2' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('mobile2'))
			, 'remarks' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('remarks'))
			, 'remarks2' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('remarks2'))
			, 'createdBy' => session('userRowId')
			, 'createdStamp' => date('Y-m-d H:i')
		);
		$this->db->transStart();	
		$save = $model->insert($data);
		$this->db->transComplete();	
		if($save != false)
		{
			$status = "Saved...";
		}
		else{
			$status = $model->errors();
		}
		$data['records'] = $this->getDataLimit(5000);
		echo json_encode(array("status" => $status , 'data' => $data));
	}

	public function update()
	{
		$model = new Customers_model();
		$duplicate = $model->asArray()->where('customerName', $this->request->getPost('customerName'))->where('customerRowId !=', $this->request->getPost('globalrowid'))->first();
		if( $duplicate > 0 )
        {
        	$data = "Duplicate record...";
        	echo json_encode($data);
			return;
        }
		
        
		$data = array(
			// 'customerRowId' => $current_row
			'customerName' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('customerName'))
			, 'address' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('address'))        
			, 'mobile1' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('mobile1'))
			, 'mobile2' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('mobile2'))
			, 'remarks' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('remarks'))
			, 'remarks2' => preg_replace("/[^A-Za-z0-9@.?-_\/)(:, ]/", "", $this->request->getPost('remarks2'))
		);
		$this->db->transStart(false);	
		$save = $model->update($this->request->getPost('globalrowid'),$data);
		$this->db->transComplete();	
		if($save != false)
		{
			$status = "Updated...";
		}
		else{
			$status = $model->errors();
		}
		$data['records'] = $this->getDataLimit(5000);
		echo json_encode(array("status" => $status , 'data' => $data));
	}

	public function delete()
	{
		$modelLedger = new Rptledger_model();
		$dependent = $modelLedger->asArray()->where('customerRowId', $this->request->getPost('rowId'))->first();
		if( $dependent > 0 )
        {
        	$data['dependent'] = "yes";
        	echo json_encode($data);
			return;
        }
		

		$model = new Customers_model();
		$data = array(
			'deleted' => 'Y'
	        , 'deletedBy' => session('userRowId')
        	, 'deletedStamp' => date('Y-m-d H:i')
		);
		$this->db->transStart(false);	
		$save = $model->update($this->request->getPost('rowId'),$data);
		$this->db->transComplete();	
		if($save != false)
		{
			$status = "Deleted...";
		}
		else{
			$status = $model->errors();
		}
		$data['records'] = $this->getDataLimit(5000);
		echo json_encode(array("status" => $status , 'data' => $data));
	}

	public function loadAllRecords()
	{
		$data['records'] = $this->getDataLimit(50000);
		echo json_encode($data);
	}
}
