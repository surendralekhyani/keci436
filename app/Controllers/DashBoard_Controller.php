<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
// use App\Models\Util_model;
// use App\Models\Login_model;

class DashBoard_Controller extends BaseController
{
	// public function _remap()
	// {
	//     // Some code here...
	// }
    public function index()
    {
    	// helper(['form', 'url']);
  //   	$model = new Util_model();
        // $MenuRights['mr'] = $model->getUserRights();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('success_view', $data);
        // $this->tmp();
    }

    // public function insert()
    // {
    // 	// helper(['form', 'url']);
    // 	// $model = new Organisation_model();
    // 	// $data['orgInfo'] = $model->getOrganisation();
    //     $data['title'] = "Hello World from Codeigniter 4.1.2";
    //     $data['errMsg'] = "";
    //     echo view('success_view', $data);
    //     // $this->tmp();
    // }
}
// 	public function index()
// 	{
// 		if ($this->session->isLogin===True && $this->session->session_id != '') /*if logged in*/
// 		{
// 			if($this->Util_model->getRight($this->session->userRowId,'DashBoard')==0)
// 			{
// 				$this->load->view('includes/header4all');
// 				$MenuRights['mr'] = $this->Util_model->getUserRights();
// 				$MenuRights['notifications'] = $this->Util_model->getNotifications();
// 				$this->load->view('includes/menu4admin', $MenuRights);
// 				$this->load->view('ErrorUnauthenticateUser_view');
// 				$this->load->view('includes/footer');				
// 				return;
// 			}
// 			$data['recordsDues'] = $this->Dashboard_model->getDues();
// 			$data['recordsDuesNegative'] = $this->Dashboard_model->getDuesNegative();
//         	$data['notifications'] = $this->Util_model->getNotifications();
// 			$data['complaints'] = $this->Dashboard_model->getComplaints();
//       		$data['replacements'] = $this->Dashboard_model->getReplacements();
//       		$data['requirements'] = $this->Dashboard_model->getRequirements();
// 			$data['errorfound'] = "";
// 			///// userRights
	
// 			$this->load->view('includes/header4all');
// 			$MenuRights['mr'] = $this->Util_model->getUserRights();
// 			$MenuRights['notifications'] = $this->Util_model->getNotifications();
// 			$this->load->view('includes/menu4admin', $MenuRights);

// 			$this->load->view('DashBoard_view', $data);
// 			$this->load->view('includes/footer');
// 		}
// 		else 	/* if not logged in */	
// 		{
//             $this->load->view('includes/header');           // with Jumbotron
//         	$this->load->model('Organisation_model');
//             $data['orgInfo'] = $this->Organisation_model->getOrganisation();

//             $this->load->view('login_view', $data);
// 	        $this->load->view('includes/footer');
// 		}
// 	}  


// }
