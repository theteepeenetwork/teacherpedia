<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ResourcesModel;
use App\Models\Image;

class Home extends BaseController
{
	public function index($page = "")
	{

		if ($page == NULL) {

			$page = 'home';
		} else {
			$page = 'home/' . $page;
		}

		if (!is_file(APPPATH . '/Views/home' . '.php')) {
			// Whoops, we don't have a page for that!
			throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
		}

		$resources = new ResourcesModel();

		$data['latest_resources'] = $resources->load_latest_resources();
		$data['feature'] = $resources->get_random_feature();
		$data['title'] = 'Teacherpedia';
		$data['main'] =  view($page, $data);
		return view('index', $data);
	}

	public function notice()
	{
		/*if (!is_file(APPPATH . '/Views/home' . '.php')) {
            // Whoops, we don't have a page for that!
            throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
        }*/

		$data['title'] = 'Teacherpedia';
		$data['main'] =  view('home/notices/coming-soon', $data);
		return view('home/notices/notices', $data);
	}

	public function legal($page = "")
	{
		$data['title'] = ucfirst($page);
		$data['main'] =  view('legal/privacy-policy', $data);
		return view('index', $data);
	}

	public function contact($page = "")
	{

		if ($this->request->getMethod() == 'post') {
			$username = $this->request->getVar('user_name');
			$useremail = $this->request->getVar('user_email');
			$message = $this->request->getVar('message');

			$email = \Config\Services::email();

			$email->setFrom('contact@teacherpedia.co.uk', $username);
			$email->setTo('contact@teacherpedia.co.uk');

			$email->setSubject('Contact Form');
			$email->setMessage('From: ' . $username . '<br />' . 'Email: ' . $useremail . '<br />' . "Message: " . $message);

			if ($email->send()) {
				$session = session();
				$session->setFlashdata('success', "Thanks! We've got your message");
				redirect()->to('/contact');
			}
		}

		$data['title'] = ucfirst('Contact Form');
		$data['main'] =  view('home/contact/contact', $data);
		return view('index', $data);
	}

	/*public function index()
    {
        return view('welcome_message');
    }

    public function load($page = 'home')
    {
        if (!is_file(APPPATH . '/Views/pages/' . $page . '.php')) {
            // Whoops, we don't have a page for that!
            throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter
        $data['main'] = view('pages/' . $page, $data);

        return view('index', $data);
    }*/



	//--------------------------------------------------------------------

}
