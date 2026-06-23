<?php

namespace App\Controllers\Admin;

use App\Models\Login_model;

class Admin extends Admin_Controller
{
    public function index($page = 'main')
    {

        $data['title'] = ucfirst($page); // Capitalize the first letter

        //pass database result to view as array
        $data['first_name'] = session()->get('first_name');
        $data['main_content']    = view('admin/' . $page, $data);
        return view('dashboard', $data);
    }
    public function login($page = 'login')
    {

        $data['title'] = ucfirst('Teacherpedia - Admin ' . $page); // Capitalize the first letter

        //pass database result to view as array
        $data['main_content']    = view('admin/admin_users/' . $page, $data);
        return view('admin/login', $data);
    }
}
