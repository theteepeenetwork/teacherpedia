<?php

namespace App\Controllers\User;

use App\Models\Login_model;
use CodeIgniter\Encryption\Encryption;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Controller;
use CodeIgniter\Encryption\EncrypterInterface;
use CodeIgniter\Pager\Pager;
use Login;

class Users extends \App\Controllers\BaseController
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
				$model = new Login_model();

				$user = $model->where('email', $this->request->getVar('user_email'))
					->first();

				$this->setUserSession($user);
				if (session()->get('admin') == 'yes') {
					$data['test'] = '<li class="nav-item"><a style="color: white" id="link" class="nav-link" href="/resources/view/all_test_resources">Test</a>';
				} else {
					$data['test'] = '';
				}
				return redirect()->to('/account');
			}
			$failed = 'Incorrect login details entered. Try again.';
			$_SESSION['login_error'] = $failed;
			$session->markAsFlashdata('login_error');

			return redirect()->to('/login');
		}

		$data['success'] = $session->getFlashdata('success');
		$data['title']   = ucfirst($page);
		$data['main']    = view('user/' . $page, $data);

		return view('index', $data);
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

	public function register($page = '')
	{
		$session = session();
		$data = [];
		helper(['form', 'url']);

		if ($this->request->getMethod() == 'post') {
			//let's do the validation here

			if (!$this->validate('register')) {
				$data['validation'] = $this->validator;
			} else {
				$model = new Login_model();


				$verification_key = uniqid();

				$newData = [
					'first_name' => $this->request->getVar('first_name'),
					'second_name' => $this->request->getVar('second_name'),
					'email' => $this->request->getVar('user_email'),
					'username' => $this->request->getVar('user_name'),
					'password' => $this->request->getVar('user_password'),
					'subscriber' => 'free',
					'communication' => $this->request->getVar('communication'),
					'verification_key' => $verification_key
				];

				$subject = "Confirm Registation to Teacherpedia.";
				$verify_link = '<a href="' . base_url() . '/verify_email/' . $this->request->getVar('user_email') . '/' . $verification_key . '">here</a>';
				$message = "Welcome to Teacherpedia. Please click " . $verify_link . " to confirm your registration.";
				$email_body = '<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The HTML5 Herald</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">

  <link rel="stylesheet" href="css/styles.css?v=1.0">

</head>

<body>
  ' . $message . '
</body>
</html>';



				if ($this->send_email($this->request->getVar('user_email'), $subject, $email_body)) {
					$session->setFlashdata('success', 'Registration successful - check your emails to confirm your registration!');
					$model->save($newData);
					return redirect()->to('/login');
				} else {
					$session->setFlashdata('success', 'Registration unsuccessful.');
					return redirect()->to('/login');
				}
			}
		}


		$data['title']   = ucfirst($page);
		$data['main']    = view('user/register' . $page, $data);
		return view('index', $data);
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

	public function send_email($user_email, $subject, $message)
	{
		$email = \Config\Services::email();

		$email->setFrom('confirm@teacherpedia.co.uk', 'Teacherpedia');
		$email->setTo($user_email);
		$email->setSubject($subject);
		$email->setMessage($message);
		return $email->send();
	}

	public function verify_email()
	{

		$request = new \CodeIgniter\HTTP\URI();
		$uri = $this->request->uri;
		$user_email = $uri->getSegment(2);
		$verification_code = $uri->getSegment(3);
		$model = new Login_model();
		$verified = $model->verify_email($user_email, $verification_code);

		if ($verified == 'done') {
			session()->setFlashdata('success', "Awesome! We've verified your email! You can now log in.");
			return redirect()->to('/login');
		} elseif ($verified == 'already_verified') {
			session()->setFlashdata('warning_msg', 'Email already Verified');

			return redirect()->to('/login');
		} else {
			session()->setFlashData('warning_msg', 'Something went wrong.');
			return redirect()->to('/login');
		}
	}


	public function logout()
	{
		session()->destroy();
		return redirect()->to('/');
	}

	//--------------------------------------------------------------------

}
