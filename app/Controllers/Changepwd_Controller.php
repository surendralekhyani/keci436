<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Changepwd_model;

class Changepwd_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Changepwd_model();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('ChangePwd_view', $data);
		echo view('footer');
	}
	public function checkLogin()
	{
		$model = new Changepwd_model();
		$rules = [
            'txtOldPassword' => ['label' => 'Old Password', 'rules' => 'trim|required|min_length[3]|max_length[20]'],
            'txtPassword' => ['label' => 'Password', 'rules' => 'trim|required|min_length[3]|max_length[20]'],
            'txtRepeatPassword' => ['label' => 'Repeat Password', 'rules' => 'trim|required|min_length[3]|max_length[20]|matches[txtPassword]'],
        ];
	    $errors = [
            'password' => [
                'validateUser' => "Email or Password don't match",
            ],
        ];
        if (!$this->validate($rules, $errors)) 
        {
			$MenuRights['mr'] = $this->modelUtil->getUserRights();
    		$data['validation'] = $this->validator;
            $data['errMsg'] = "";
            echo view('header');
			echo view('menu', $MenuRights);
            echo view('ChangePwd_view', $data);
			echo view('footer');
        }
        else 
        {
	        $uid = session('userId');
	        $o_pw = $this->request->getPost('txtOldPassword');
	        $n_pw = $this->request->getPost('txtPassword');
        	$result = $model->changepwd($uid, $o_pw, $n_pw);
	        if($result == true)	// Successfully changed password
	        {
	        	$MenuRights['mr'] = $this->modelUtil->getUserRights();
		        $data['errMsg'] = "Password changed successfully...";
				echo view('header');
				echo view('menu', $MenuRights);
		        echo view('ChangePwd_view', $data);
				echo view('footer');
	        }
	        else
	        {
	   	        $MenuRights['mr'] = $this->modelUtil->getUserRights();
		        $data['errMsg'] = "Invalid OLD password...";
				echo view('header');
				echo view('menu', $MenuRights);
		        echo view('ChangePwd_view', $data);
				echo view('footer');
	        }
        }


	}
}
