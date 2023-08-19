<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
// use App\Models\Util_model;
use App\Models\Organisation_model;

class Organisation_Controller extends BaseController
{
	public function index()
	{
        $model = new Organisation_model();
		$data['records'] = $model->findAll();
		$data['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
        $data['title'] = "Organisation";
        echo view('Organisation_view', $data);
	}  


	public function update()
	{
		$model = new Organisation_model();
    	if ( !$this->request->isAJAX())
		{
			return false;
		}
		
		$builder = $this->db->table('organisation');
		$builder->select('*');
		$query = $builder->get();
		$row = $query->getRow();
		if (isset($row))
		{    	
			$data = array(
	            'orgName' => $this->request->getPost('orgName')
				, 'add1' => $this->request->getPost('add1')
				, 'add2' => $this->request->getPost('add2')
		        , 'add3' => $this->request->getPost('add3')
		        , 'add4' => $this->request->getPost('add4')
		        , 'electricianNo' => $this->request->getPost('electricianNo')
		        , 'rechargeLimit' => $this->request->getPost('rechargeLimit')
		        , 'rechargeMobile' => $this->request->getPost('rechargeMobile')
	        );
			$save = $model->update($this->request->getPost('id'), $data);
		}
		else
		{
			$data = array(
		        'orgName' => $this->request->getPost('orgName')
		        , 'add1' => $this->request->getPost('add1')	        
		        , 'add2' => $this->request->getPost('add2')
		        , 'add3' => $this->request->getPost('add3')
		        , 'add4' => $this->request->getPost('add4')
		        , 'electricianNo' => $this->request->getPost('electricianNo')
		        , 'rechargeLimit' => $this->request->getPost('rechargeLimit')
		        , 'rechargeMobile' => $this->request->getPost('rechargeMobile')
			);
			$save = $model->insert($data);
		}
		if($save != false)
		{
			// $data = $model->where('id', $save)->first();
			$data = "Saved...";
		}
		else{
			// $data = "Not Saved...";
			$data = $model->errors();
		}
		echo json_encode(array("status" => true , 'data' => $data));
	}
}
