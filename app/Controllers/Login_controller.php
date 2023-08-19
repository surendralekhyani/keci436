<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Organisation_model;
use App\Models\Login_model;
use Config\Services;

class Login_controller extends BaseController
{
    public function index()
    {
        // session()->destroy();
    	// helper(['form', 'url']);
    	$model = new Organisation_model();
    	$data['orgInfo'] = $model->getOrganisation();
        $data['title'] = "Hello World from Codeigniter 4.1.2";
        $data['errMsg'] = "";
        echo view('Login_view', $data);
        // $this->tmp();
    }

    public function checkLogin()
	{

        $session = session();
        // $throttler = Services::throttler();

        // if ($throttler->check($this->request->getIPAddress(), 2, MINUTE) === false)
        // {
        //     return "Now you can login after a minute..";
        // }

        if (!$_SERVER['REQUEST_METHOD'] == 'post') 
        {
            return redirect()->to(base_url('/'));
        }

        $rules = [
            'txtUID' => ['label' => 'User name', 'rules' => 'required|min_length[3]|max_length[20]'],
            'txtPassword' => ['label' => 'Password', 'rules' => 'required|max_length[20]'],
        ];
        $errors = [
            'password' => [
                'validateUser' => "User name or Password don't match",
            ],
        ];
        if (!$this->validate($rules, $errors)) 
        {
            return redirect()->back()->withInput()->with('msg', "Invalid user...");
        }
        else 
        {
            $model = new Login_model();
            $userName = preg_replace("/[^A-Za-z0-9@.-_ ]/", "",  $_POST['txtUID']);
            $userRec = $model->where('uid', $userName)->first();
            // dd($userRec);
            if($userRec){
                $pass = $userRec['pwd'];
                $verify_pass = password_verify($_POST['txtPassword'], $pass);
                if($verify_pass){
                    $this->setUserSession($userRec);
                    return redirect()->to(base_url('index.php/dashboard'));
                }else{
                    return redirect()->back()->withInput()->with('msg', 'Wrong Password');
                }
            }else{
                return redirect()->back()->withInput()->with('msg', 'Invalid User');
            }
        }
	}

	private function setUserSession($user)
    {
        $data = [
            'userId' => $user['uid'], //$_POST['txtUID'],
            'userRowId' => $user['rowid'],
            'isLogin' => "TruE",
            'isLoggedIn' => true,
            'desires' => "SabAchhaHoga",
        ];

        session()->set($data);
        return true;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }
    public function test()
    {
        // return view('welcome_message');
        echo 'ddd';
    }
}
