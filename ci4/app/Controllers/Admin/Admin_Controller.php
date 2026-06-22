<?php

namespace App\Controllers\Admin;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;

class Admin_Controller extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];


	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		$this->response->CSP->setDefaultSrc('http://localhost:8888/');
		$styleSrc = [
			'http://localhost:8888/',
			'https://stackpath.bootstrapcdn.com/',
			'https://fonts.googleapis.com/',
			'https://fonts.googleapis.com/',
			'https://pagead2.googlesyndication.com/'

		];
		$scriptSrc = [
			'http://localhost:8888/',
			'https://pagead2.googlesyndication.com/',
			'https://ajax.googleapis.com/',
			'https://cdnjs.cloudflare.com/',
			'https://stackpath.bootstrapcdn.com/',
			'https://www.googletagmanager.com/',
			'https://adservice.google.com/',
			'https://www.googletagservices.com/',
			'https://adservice.google.co.uk/',
			'https://pagead2.googlesyndication.com/'

		];
		$fontSrc = [
			'http://localhost:8888/',
			'https://stackpath.bootstrapcdn.com/',
			'https://fonts.gstatic.com/',
			'https://fonts.googleapis.com/',
			'https://pagead2.googlesyndication.com/'

		];
		$this->response->CSP->addStyleSrc($styleSrc);
		$this->response->CSP->addFontSrc($fontSrc);
		$this->response->CSP->addScriptSrc($scriptSrc);
		$this->response->CSP->addObjectSrc('http://localhost:8888/');





		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
	}
}
