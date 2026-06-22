<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Notices extends BaseController
{
    public function index($page = "")
    {
        /*if (!is_file(APPPATH . '/Views/home' . '.php')) {
            // Whoops, we don't have a page for that!
            throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
        }*/

        $data['title'] = 'Teacherpedia';
        $data['main'] =  view('notices/coming-soon', $data);
        return view('notices/notices', $data);
    }



    //--------------------------------------------------------------------

}
