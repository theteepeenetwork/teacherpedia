<?php

namespace Config;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var array
	 */
	public $ruleSets = [
		\CodeIgniter\Validation\Rules::class,
		\CodeIgniter\Validation\FormatRules::class,
		\CodeIgniter\Validation\FileRules::class,
		\CodeIgniter\Validation\CreditCardRules::class,
		\App\Validation\UserRules::class
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// Register
	//--------------------------------------------------------------------
	public $register = [
		'first_name' => 'required|min_length[3]|max_length[20]',
		'user_name' => 'required|min_length[3]|is_unique[users.username]|max_length[20]',
		'second_name' => 'required|min_length[3]|max_length[20]',
		'user_email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
		'user_password' => 'required|min_length[8]|max_length[255]',
	];
	public $admin_register = [
		'first_name' => 'required|min_length[3]|max_length[20]',
		'user_name' => 'required|min_length[3]|is_unique[admin_users.username]|max_length[20]',
		'second_name' => 'required|min_length[3]|max_length[20]',
		'user_email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[admin_users.email]',
		'user_password' => 'required|min_length[8]|max_length[255]',
	];
	public $register_errors = [
		'first_name' => [
			'required' => 'Please enter your name.',
			'min_length' => 'First name must be at least 3 characters.',
		],
		'user_name' => [
			'required' => 'Please enter a username.',
			'min_length' => 'Username must be at least 3 characters.',
			'is_unique' => 'Someone with that username is already registered',
		],
		'second_name' => [
			'required' => 'Please enter a second name.',
			'min_length' => 'Second name must be at least 3 characters.',
		],
		'user_email' => [
			'required' => 'Please enter an email address.',
			'valid_email' => 'Please enter a valid email.',
			'is_unique' => 'Looks like that email is already registered!',
			'min_length' => 'Email must be at least 6 characters.',
		],
		'user_password' => [
			'required' => 'Please provide a password.',
			'min_length' => 'Password must be at least 8 characters.',
		]
	];
	//--------------------------------------------------------------------
	// Change Password
	//--------------------------------------------------------------------
	public $change_password = [
		'old_password' => 'required|validatePasswordChange[old_password]',
		'new_password' => 'required|min_length[8]|max_length[255]',
		'passconf' => 'required|matches[new_password]',
	];
	public $change_password_errors = [
		'old_password' => [
			'required' => 'Please enter your old password.',
			'validatePasswordChange' => 'You old password does not match.',
		],
		'new_password' => [
			'required' => 'You must enter a new password',
			'min_length' => 'Your new password must be at least 8 characters',
		],
		'passconf' => [
			'required' => 'Confirm your new password',
			'matches' => "Your passwords don't match",
		]
	];

	//--------------------------------------------------------------------
	// Login
	//--------------------------------------------------------------------
	public $login = [
		'user_email' => 'required|valid_email',
		'user_password' => 'required|validateUser[user_email, user_password]',
	];
	public $login_errors = [
		'user_email' => [
			'required' => 'Enter an email address.',
			'valid_email' => 'Please enter a valid email address.'
		],
		'user_password' => [
			'required' => 'Enter your password.',
			'validateUser' => 'Email or password doesn\'t match',
		]
	];

	public $admin_image_upload = [
		'title' => 'required',
		'image' => 'required'
	];

	public $callback_file_check_errors = [
		'title' => [
			'required' => 'Enter the name of the image.',
		],
		'image' => [
			'required' => 'Select an image to upload.'
		]
	];
}
