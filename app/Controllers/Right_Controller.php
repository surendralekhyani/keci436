<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\User_model;
use App\Models\Right_model;
// use App\Models\Menu_model;

class Right_Controller extends BaseController
{
	
	public function index()
	{
		$model = new User_model();
		$data['users'] = $model->getData('rowid','uid','pwd');
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Rights_view', $data);
		echo view('footer');
	}    

	public function insertRights()
	{
		$model = new Right_model();
		$model->insertAjax($this->request);

		$model = new User_model();
		$data['users'] = $model->getData('rowid','uid','pwd');
		echo view('Rights_view', $data);
		echo view('footer');
	}

	public function getRights()
    {
		$model = new Right_model();
        $arr = $model->getRights($this->request->getPost('uid'));
        $msg="";
        foreach ($arr as $key => $value) {
            $msg = $value['menuoption'].",".$msg;
        }
        // echo json_encode($temp);
        echo $msg;
    }


}
