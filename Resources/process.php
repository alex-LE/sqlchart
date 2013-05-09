
function runQuery() {
	global $global_data;
	$runner = new QueryRunner();
	$data = $runner->process();
	return $data;
}



class QueryRunner {
	/**
	 * @var DBBase
	 */
	private $db;

	public function process() {
		global $jQuery, $window;
		try {
			switch($jQuery('#dbtype')->val()) {

				case 'postgres':
					$this->db = new PostgreSQL();
					break;

				case 'mysql':
				default:
					$this->db = new MySQL();
					break;
			}

			$this->logMessage('using '.$jQuery('#dbtype')->val());
			$this->connect();
			$this->logMessage('connected');
			$start = microtime(true);
			$this->execute();
			$this->logMessage('query finished in '.round(microtime(true)-$start, 4).' s');
			$data = $this->db->getResultAsArray();
			return $data;
		} catch(Exception $e) {
			$this->logMessage($e->getMessage());
			$window->alert('failed');
		}
	}

	private function execute() {
		global $jQuery, $window;
		$query = $jQuery('#sql')->val();

		$this->db->execute($query);
	}

	/**
	 * connects to the database
	 *
	 * @return bool
	 */
	private function connect() {
		global $jQuery, $window;

		$dbuser = $jQuery('#dbuser')->val();
		$dbpass = $jQuery('#dbpass')->val();
		$dbhost = $jQuery('#dbhost')->val();
		$dbname = $jQuery('#dbname')->val();

		if(empty($dbuser) || empty($dbhost) || empty($dbname)) {
			throw new Exception('db credentials are invalid');
			return false;
		}

		return $this->db->connect($dbhost, $dbname, $dbuser, $dbpass);
	}

	/**
	 * log message to the console field
	 *
	 * @param $msg
	 */
	private function logMessage($msg) {
		global $jQuery;
		$jQuery('#console')->append('<span>'.$msg.'</span>');
	}
}