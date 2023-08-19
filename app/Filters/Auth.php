<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // echo "SSS";
        if (! session('isLoggedIn') || session('isLogin') != "TruE" || session('desires') != "SabAchhaHoga")
	    {
	        return redirect()->to('/');
	    }
        $router = service('router'); 
        $controller  = $router->controllerName(); 
        $controller = substr($controller, strrpos($controller, '\\') + 1);
        // echo $controller;
        if($this->getRight( session('userRowId'), strtoupper($controller) ) == 0)
        {
            // echo $this->getRight( session('userRowId'), strtoupper($controller));
            return redirect()->to('/');
        }
    }

    public function getRight($uid,$right)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('userrights');
        $query = $builder->select('*')
                             ->where('userrowid', $uid)
                             ->where('upper(controllername)', $right)
                             ->limit(1)
                             ->get();
        $row = $query->getRow();
        if (isset($row))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}