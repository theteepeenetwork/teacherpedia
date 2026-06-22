<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Admin_Controller;
use App\Models\Admin_login_model;
use CodeIgniter\Controller;
use Login;

class Admin_users extends Admin_Controller
{
	public function index($page = '')
	{

		$session = session();

		$data = [];
		helper(['form']);
		if (session()->get('id')) {
			$data['title']   = ucfirst($page);
			$data['main']    = view('user/' . $page, $data);

			return view('index', $data);
		}


		if ($this->request->getMethod() == 'post') {
			//let's do the validation here

			if (!$this->validate('login')) {
				$data['validation'] = $this->validator;
			} else {
				$model = new Admin_login_model();

				$user = $model->where('email', $this->request->getVar('user_email'))
					->first();

				$this->setUserSession($user);
				if (session()->get('admin') == 'yes') {
					$data['test'] = '<li class="nav-item"><a style="color: white" id="link" class="nav-link" href="/resources/view/all_test_resources">Test</a>';
				} else {
					$data['test'] = '';
				}
				return redirect()->to('/dashboard');
			}
			$failed = 'Incorrect login details entered. Try again.';
			$_SESSION['login_error'] = $failed;
			$session->markAsFlashdata('login_error');

			return redirect()->to('/admin_login');
		}

		$data['success'] = $session->getFlashdata('success');
		$data['title']   = ucfirst($page);
		$data['main_content']    = view('/admin/admin_users/' . $page, $data);

		return view('admin/login', $data);
	}

	private function setUserSession($user)
	{
		$data = [
			'id' => $user['id'],
			'first_name' => $user['first_name'],
			'second_name' => $user['second_name'],
			'email' => $user['email'],
			'subscriber' => $user['subscriber'],
			'isLoggedIn' => true,
			'communication' => $user['communication'],
			'admin' => $user['admin']
		];

		session()->set($data);
		return true;
	}

	public function register($page = 'register')
	{
		$session = session();
		$data = [];
		helper(['form', 'url']);

		if ($this->request->getMethod() == 'post') {
			//let's do the validation here

			if (!$this->validate('admin_register')) {
				$data['validation'] = $this->validator;
			} else {
				$model = new Admin_login_model();

				$newData = [
					'first_name' => $this->request->getVar('first_name'),
					'second_name' => $this->request->getVar('second_name'),
					'email' => $this->request->getVar('user_email'),
					'username' => $this->request->getVar('user_name'),
					'password' => $this->request->getVar('user_password'),
					'subscriber' => 'basic',
					'communication' => $this->request->getVar('communication')
				];
				$model->save($newData);
				$session->setFlashdata('success', 'Registration successful - You can now login!');
				return redirect()->to('/admin/login');
			}
		}


		$data['title']   = ucfirst($page);
		$data['main_content']    = view('/admin/admin_users/' . $page, $data);
		return view('admin/login', $data);
	}

	function update_communication()
	{

		$user_model = new Login_model();

		$data = array(
			'id' => session()->get('id'),
			'communication' => $this->request->getVar('communication')
		);


		$user_model->save($data);

		$data = [
			'id' => session()->get('id'),
			'communication' => $this->request->getVar('communication')
		];

		session()->set($data);

		session()->setFlashdata('message', "Thanks for updating your preferences.");
		return redirect()->to('/user/users/index/communication');
	}

	public function change_password()
	{
		//$/this->logged_in();

		$data['title'] = 'Change Password';

		if ($this->request->getMethod() == 'post') {
			//let's do the validation here

			if (!$this->validate('change_password')) {

				return view('index', [
					'main' => view('user/change_password', [
						'title' => 'Change Password',
						'errors' => $this->validator->getErrors(),
					]),
				]);

				$data['validation'] = $this->validator;
				session()->setFlashdata('password', 'Password change failed');
				return redirect()->to('/user/users/index/change_password');
			} else {
				$model = new Login_model();

				$newData = [
					'id' => session()->get('id'),
					'password' => $this->request->getVar('new_password'),
				];
				$model->save($newData);
				$session = session();
				session()->setFlashdata('success', 'Password successfully changed');
				return redirect()->to('/user/users/index/change_password');
			}
		}
	}


	public function logout()
	{
		session()->destroy();
		return redirect()->to('/');
	}

	//--------------------------------------------------------------------

}