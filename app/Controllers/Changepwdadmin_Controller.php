<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Changepwdadmin_model;

class Changepwdadmin_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Changepwdadmin_model();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
		$data['users'] = $model->getData();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('ChangePwdAdmin_view', $data);
		echo view('footer');
	}  
	
	public function checkLogin()
	{
		$model = new Changepwdadmin_model();
		$rules = [
            'txtUID' => ['label' => 'User Id', 'rules' => 'trim|required|is_natural_no_zero'],
            'txtPassword' => ['label' => 'Password', 'rules' => 'trim|required|min_length[3]|max_length[20]'],
            'txtRepeatPassword' => ['label' => 'Repeat Password', 'rules' => 'trim|required|min_length[3]|max_length[20]|matches[txtPassword]'],
        ];
	    $errors = [
            'password' => [
                'validateUser' => "Password don't match",
            ],
        ];
        if (!$this->validate($rules, $errors)) 
        {
			$data['users'] = $model->getData();
			$MenuRights['mr'] = $this->modelUtil->getUserRights();
    		$data['validation'] = $this->validator;
            $data['errMsg'] = "";
            echo view('header');
			echo view('menu', $MenuRights);
            echo view('ChangePwdAdmin_view', $data);
			echo view('footer');
        }
        else 
        {
	        $uid = $this->request->getPost('txtUID');
	        $n_pw = $this->request->getPost('txtPassword');
        	$result = $model->changepwd($uid, $n_pw);
        	// $result = $model->changepwd($uid, $o_pw, $n_pw);
	        if($result == true)	// Successfully changed password
	        {
				$data['users'] = $model->getData();
	        	$MenuRights['mr'] = $this->modelUtil->getUserRights();
		        $data['errMsg'] = "Password changed successfully...";
				echo view('header');
				echo view('menu', $MenuRights);
		        echo view('ChangePwdAdmin_view', $data);
				echo view('footer');
	        }
	        else
	        {
				$data['users'] = $model->getData();
	   	        $MenuRights['mr'] = $this->modelUtil->getUserRights();
		        $data['errMsg'] = "Invalid OLD password...";
				echo view('header');
				echo view('menu', $MenuRights);
		        echo view('ChangePwdAdmin_view', $data);
				echo view('footer');
	        }
        }

  //       $this->load->library('form_validation');
  //       $this->form_validation->set_rules('txtUID', 'User ID', 'trim|required');
  //       $this->form_validation->set_rules('txtPassword', 'New Password', 'trim|required|min_length[8]|max_length[20]');
  //       $this->form_validation->set_rules('txtRepeatPassword', 'Repeat Password', 'trim|required|min_length[8]|max_length[20]|matches[txtPassword]');

		// // $this->form_validation->set_message('min_length', '{field} must have at least {param} characters.');
		// // $this->form_validation->set_message('matches', 'Mismatch in old and new Password');
		// $this->form_validation->set_message('matches', 'Please type correct {field}');
		// $this->form_validation->set_message('min_length', 'Length must be between 8 and 20.');

  //       if($this->form_validation->run() == FALSE)
  //       {
		// 	$this->load->view('includes/header4all');
		// 	$MenuRights['mr'] = $this->Util_model->getUserRights();
		// 	$MenuRights['notifications'] = $this->Util_model->getNotifications();
		// 	$this->load->view('includes/menu4admin', $MenuRights);
		// 	$data['users'] = $this->Changepwdadmin_model->getData();
		// 	$this->load->view('ChangePwdAdmin_view', $data);
  //           $this->load->view('includes/footer');
  //           return;
  //       }

  //       $uid = $this->input->post('txtUID');
  //       $n_pw = $this->input->post('txtPassword');

  //       $result = $this->Changepwdadmin_model->changepwd($uid, $n_pw);
  //       // print_r("<p>controller:result: ".$result);
  //       if($result==true)	// Successfully changed password
  //       {
		// 	$this->load->view('includes/header4all');
		// 	$MenuRights['mr'] = $this->Util_model->getUserRights();
		// 	$MenuRights['notifications'] = $this->Util_model->getNotifications();
		// 	$this->load->view('includes/menu4admin', $MenuRights);
  //           // $data['errMsg'] = "Password successfully changed :)";
  //           $data['errMsg'] = "Done, Password Successfully Changed.";
  //           $this->load->view('error_view', $data);
		// 	$data['users'] = $this->Changepwdadmin_model->getData();
		// 	$this->load->view('ChangePwdAdmin_view', $data);
  //       }
  //       else
  //       {
		// 	$this->load->view('includes/header4all');
		// 	$MenuRights['mr'] = $this->Util_model->getUserRights();
		// 	$MenuRights['notifications'] = $this->Util_model->getNotifications();
		// 	$this->load->view('includes/menu4admin', $MenuRights);
  //           $data['errMsg'] = "Invalid Old Password...";
  //           $this->load->view('error_view', $data);
		// 	$data['users'] = $this->Changepwdadmin_model->getData();
		// 	$this->load->view('ChangePwdAdmin_view', $data);
  //       }
  //       $this->load->view('includes/footer');
	}
}
