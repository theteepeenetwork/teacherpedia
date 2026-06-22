<?php

namespace Config;

/**
 * Database Configuration
 *
 * @package Config
 */

class Database extends \CodeIgniter\Database\Config
{
	/**
	 * The directory that holds the Migrations
	 * and Seeds directories.
	 *
	 * @var string
	 */
	public $filesPath = APPPATH . 'Database/';

	/**
	 * Lets you choose which connection group to
	 * use if no other is specified.
	 *
	 * @var string
	 */
	public $defaultGroup = 'production';

	/**
	 * The default database connection.
	 *
	 * @var array
	 */
	public $development = [
		'hostname' => 'localhost',
		'username' => 'teacherpedia',
		'password' => 'teacherpedia',
		'database' => 'teacherpedia',
		'DBDriver' => 'MySQLi',
		'DBPrefix' => '',
		'pConnect' => FALSE,
		'DBDebug'  => TRUE,
		'cacheOn'  => FALSE,
		'cacheDir' => '',
		'charset'  => 'utf8',
		'DBCollat' => 'utf8_general_ci',
		'swapPre'  => '',
		'encrypt'  => FALSE,
		'compress' => FALSE,
		'strictOn' => FALSE
	];

	public $tests = [
		'hostname' => '',
		'username' => '',
		'password' => '',
		'database' => '',
		'DBDriver' => 'MySQLi',
		'DBPrefix' => '',
		'pConnect' => TRUE,
		'DBDebug' => TRUE,
		'cacheOn' => FALSE,
		'cacheDir' => '',
		'charset' => 'utf8',
		'DBcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'strictOn' => FALSE
	];

	public $production = [
		'hostname' => '',
		'username' => '',
		'password' => '',
		'database' => '',
		'DBDriver' => 'MySQLi',
		'DBPrefix' => '',
		'pConnect' => TRUE,
		'DBDebug'  => TRUE,
		'cacheOn'  => FALSE,
		'cacheDir' => '',
		'charset'  => 'utf8',
		'DBCollat' => 'utf8_general_ci',
		'swapPre'  => '',
		'encrypt'  => FALSE,
		'compress' => FALSE,
		'strictOn' => FALSE
	];




	/**
	 * This database connection is used when
	 * running PHPUnit database tests.
	 *
	 * @var array
	 */
	/*public $tests = [
		'DSN'      => '',
		'hostname' => '127.0.0.1',
		'username' => '',
		'password' => '',
		'database' => ':memory:',
		'DBDriver' => 'SQLite3',
		'DBPrefix' => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
		'pConnect' => false,
		'DBDebug'  => (ENVIRONMENT !== 'production'),
		'cacheOn'  => false,
		'cacheDir' => '',
		'charset'  => 'utf8',
		'DBCollat' => 'utf8_general_ci',
		'swapPre'  => '',
		'encrypt'  => false,
		'compress' => false,
		'strictOn' => false,
		'failover' => [],
		'port'     => 3306,
	];*/

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		// Read connection credentials from the environment (ci4/.env) so
		// that no secrets are stored in this file. Safe non-secret
		// defaults are used as fallbacks when the env vars are not set.
		$this->production['hostname'] = env('database.production.hostname', '');
		$this->production['username'] = env('database.production.username', '');
		$this->production['password'] = env('database.production.password', '');
		$this->production['database'] = env('database.production.database', '');

		$this->tests['hostname'] = env('database.tests.hostname', '');
		$this->tests['username'] = env('database.tests.username', '');
		$this->tests['password'] = env('database.tests.password', '');
		$this->tests['database'] = env('database.tests.database', '');

		$this->development['hostname'] = env('database.development.hostname', 'localhost');
		$this->development['username'] = env('database.development.username', 'teacherpedia');
		$this->development['password'] = env('database.development.password', 'teacherpedia');
		$this->development['database'] = env('database.development.database', 'teacherpedia');

		// Ensure that we always set the database group to 'tests' if
		// we are currently running an automated test suite, so that
		// we don't overwrite live data on accident.
		if (ENVIRONMENT === 'testing') {
			$this->defaultGroup = 'tests';

			// Under Travis-CI, we can set an ENV var named 'DB_GROUP'
			// so that we can test against multiple databases.
			if ($group = getenv('DB')) {
				if (is_file(TESTPATH . 'travis/Database.php')) {
					require TESTPATH . 'travis/Database.php';

					if (!empty($dbconfig) && array_key_exists($group, $dbconfig)) {
						$this->tests = $dbconfig[$group];
					}
				}
			}
		} elseif (ENVIRONMENT == 'development') {
			$this->defaultGroup = 'development';
		} else {
			$this->defaultGroup = 'production';
		}
	}

	//--------------------------------------------------------------------

}
