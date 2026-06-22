<?php

namespace App\Controllers;

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

class BaseController extends Controller
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
		$this->response->CSP->setDefaultSrc(base_url());
		$styleSrc = [
			'http://teacherpedia.co.uk/',
			'http://test.teacherpedia.co.uk/',
			base_url(),
			'https://stackpath.bootstrapcdn.com/',
			'https://fonts.googleapis.com/',
			'https://cdnjs.cloudflare.com/',
			'https://cmp.osano.com/',
			'https://googleads.g.doubleclick.net/',
			'https://www.google-analytics.com/',
			'ajax.googleapis.com',
			'https://apis.google.com',
			'https://tpc.googlesyndication.com/sodar/sodar2.js',
			'https://www.google-analytics.com',
			'https://stats.g.doubleclick.net',
			'https://www.google.com',
			'https://www.google-analytics.com/'



		];
		$scriptSrc = [
			'http://teacherpedia.co.uk/',
			'http://test.teacherpedia.co.uk/',
			base_url(),
			'https://pagead2.googlesyndication.com/',
			'https://ajax.googleapis.com/',
			'https://cdnjs.cloudflare.com/',
			'https://stackpath.bootstrapcdn.com/',
			'https://www.googletagmanager.com/',
			'https://adservice.google.com/',
			'https://www.googletagservices.com/',
			'https://adservice.google.co.uk/',
			'https://cmp.osano.com/',
			'https://googleads.g.doubleclick.net/',
			'https://www.google-analytics.com/',
			'https://www.ajax.googleapis.com',
			'https://apis.google.com',
			'https://tpc.googlesyndication.com/sodar/sodar2.js',
			'https://www.google-analytics.com',
			'https://stats.g.doubleclick.net',
			'https://www.google.com',
		];
		$fontSrc = [
			'http://teacherpedia.co.uk/',
			'http://test.teacherpedia.co.uk/',
			base_url(),
			'https://stackpath.bootstrapcdn.com/',
			'https://fonts.gstatic.com/',
			'https://cdnjs.cloudflare.com/',
			'https://cmp.osano.com/',

		];

		$imageSrc = [
			'http://test.teacherpedia.co.uk/',
			'http://www.w3.org/',
			'https://www.paypalobjects.com/',
			base_url(),
			'www.googletagmanager.com',
			'https://www.google-analytics.com/',
			'http://www.w3.org/2000/svg/ data:',
			'https://ik.imagekit.io/teacherpedia/',
			'https://res.cloudinary.com/'
		];

		$connectSource = [
			'http://test.teacherpedia.co.uk/',
			'https://pagead2.googlesyndication.com/',
			'https://tattle.api.osano.com'
		];


		$this->response->CSP->addStyleSrc($styleSrc);
		$this->response->CSP->addFontSrc($fontSrc);
		$this->response->CSP->addScriptSrc($scriptSrc);
		$this->response->CSP->addImageSrc($imageSrc);
		$this->response->CSP->addConnectSrc($connectSource);
		$this->response->CSP->addObjectSrc(base_url());
		$this->response->CSP->addObjectSrc('http://test.teacherpedia.co.uk:8888/');
		$this->response->CSP->addObjectSrc('http://teacherpedia.co.uk:8888/');





		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
	}
}
