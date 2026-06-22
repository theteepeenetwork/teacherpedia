<?php

namespace App\Controllers\admin;

use App\Models\Login_model;
use CodeIgniter\Controller;
use App\Models\Image;

class Gallery extends Controller
{


    public function index()
    {
        $data = array();

        $con = array(
            'where' => array(
                'status' => 1
            )
        );
        $images = new Image();
        $data['gallery'] = $images->getRows($con);
        $data['page_title'] = 'Images Gallery';
        $data['main_content'] = view('/admin/manage_gallery/index', $data);

        return view('dashboard', $data);

        // Load the list page view 


    }
}
