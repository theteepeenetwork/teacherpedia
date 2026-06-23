<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri     = $request->getUri();
        $session = Services::session();
        if (!$session->has('id')) {
            if ($uri->getSegment(1) == NULL) {
                return redirect()->to('/home');
            }
        }
        if ($session->has('id')) {
            if ($uri->getSegment(1) == 'admin_login') {
                return redirect()->to('/dashboard');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $uri     = $request->getUri();
        $session = Services::session();
        if (!$session->has('id')) {
            if ($uri->getSegment(1) == "user" && $uri->getSegment(4) == "register") {
                return redirect()->to('/register');
            }
            if ($uri->getSegment(1) == "account" || $uri->getSegment(1) == "user") {
                return redirect()->to('/login');
            }
            if ($uri->getSegment(1) == "dashboard" || $uri->getSegment(1) == "admin") {
                return redirect()->to('/admin_login');
            }
        }
    }
}
