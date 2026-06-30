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
	public $defaultGroup = 'default';

	/**
	 * The default database connection.
	 *
	 * For LOCAL DEVELOPMENT this group is driven entirely by .env
	 * (database.default.*), which points at a SQLite file under
	 * writable/db/. The literals below are harmless fallbacks; .env
	 * overrides them at runtime.
	 *
	 * @var array
	 */
	public $default = [
		'DSN'      => '',
		'hostname' => 'localhost',
		'username' => '',
		'password' => '',
		'database' => WRITEPATH . 'db/teacherpedia.db',
		'DBDriver' => 'SQLite3',
		'DBPrefix' => '',
		'pConnect' => FALSE,
		'DBDebug'  => TRUE,
		'charset'  => 'utf8',
		'DBCollat' => 'utf8_general_ci',
		'swapPre'  => '',
		'encrypt'  => FALSE,
		'compress' => FALSE,
		'strictOn' => FALSE,
		'failover' => [],
		'port'     => 3306,
		'foreignKeys' => TRUE,
	];

	/**
	 * Automated test connection.
	 *
	 * Real credentials are supplied via .env (database.tests.*) and are
	 * intentionally NOT committed here.
	 *
	 * @var array
	 */
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
		'DBCollat' => 'utf8_general_ci',
		'swapPre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'strictOn' => FALSE
	];

	/**
	 * Production connection.
	 *
	 * Real credentials are supplied via .env (database.production.*) and
	 * are intentionally NOT committed here. Setting any literal value
	 * here would leak secrets into source control.
	 *
	 * @var array
	 */
	public $production = [
		'hostname' => '',
		'username' => '',
		'password' => '',
		'database' => '',
		'DBDriver' => 'MySQLi',
		'DBPrefix' => '',
		'pConnect' => TRUE,
		'DBDebug'  => FALSE,
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

		// Ensure that we always set the database group to 'tests' if
		// we are currently running an automated test suite, so that
		// we don't overwrite live data on accident.
		// During automated tests always use the 'tests' group so we never
		// touch live data. Otherwise the 'default' group is used, which is
		// SQLite locally (via .env) and can be pointed at MySQL in
		// production by overriding database.default.* in the server .env.
		// Real credentials for the 'production' / 'tests' groups come from
		// .env (database.production.* / database.tests.*), never from this
		// committed file.
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
		}
	}

	//--------------------------------------------------------------------

}
