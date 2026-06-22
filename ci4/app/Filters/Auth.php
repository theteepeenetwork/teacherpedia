<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request)
    {
        $session = Services::session();
        if (!$session->has('id')) {
            if ($request->uri->getSegment(1) == NULL) {
                return redirect()->to('/home');
            }
        }
        if ($session->has('id')) {
            if ($request->uri->getSegment(1) == 'admin_login') {
                return redirect()->to('/dashboard');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response)
    {
        $session = Services::session();
        if (!$session->has('id')) {
            $session = Services::session();
            if ($request->uri->getSegment(1) == "user" && $request->uri->getSegment(4) == "register") {
                return redirect()->to('/register');
            }
            if ($request->uri->getSegment(1) == "account" || $request->uri->getSegment(1) == "user") {
                return redirect()->to('/login');
            }
            if ($request->uri->getSegment(1) == "dashboard" || $request->uri->getSegment(1) == "admin") {
                return redirect()->to('/admin_login');
            }
        }
    }
}
