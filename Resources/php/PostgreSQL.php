
class PostgreSQL extends DBBase {

	private $con_string;

	/**
	 * @param $dbhost
	 * @param $dbname
	 * @param $dbuser
	 * @param string $dbpass
	 * @throws Exception
	 */
	public function connect($dbhost, $dbname, $dbuser, $dbpass = '') {
		$this->con_string = "host=$dbhost port=5432 dbname=$dbname user=$dbuser password=$dbpass";
		/*
		$this->con = pg_connect("host=$dbhost port=5432 dbname=$dbname user=$dbuser password=$dbpass");
		if (!$this->con) {
			throw new Exception("Connect failed: %s", pg_last_error());
		}
		*/
	}

	/**
	 * @param $query
	 */
	public function execute($query) {
		global $global_exec_path, $global_platform;


        if($global_platform == 'Darwin') {
            $command = dirname($global_exec_path).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'psql'.DIRECTORY_SEPARATOR;
            $command .= 'osx'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'psql';
        } else {
            $command = dirname($global_exec_path).DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'psql'.DIRECTORY_SEPARATOR;
            $command .= 'win'.DIRECTORY_SEPARATOR.'psql.exe';
        }

        $command .= ' -F "|" -c '.escapeshellarg($query).' -A "'.$this->con_string.'"';
		$result = shell_exec($command);

		$out = shell_exec($command." 2> output");

		$this->result_array = array();
		$tmp = explode("\n", $result);

		if(!is_array($tmp)) {
			#$out = shell_exec("php test.php 2> output");
			#print $out ? $out : join("", file("output"));
			throw new Exception('Query failed');
		}

		$keys = array();
		foreach($tmp as $line) {
			if(count($keys) == 0) {
				$keys = explode('|', $line);
			} else {
				if(strpos($line, '(') === 0) {
					break;
				}

				$this->result_array[] = array_combine($keys, explode('|', $line));
			}
		}

		return;
	}

	/**
	 * @return array|void
	 */
	public function getResultAsArray() {
		$return = array();
		if(is_array($this->result_array)) {
			return json_encode($this->result_array);
		}

		/*
		while($row = pg_fetch_assoc($this->query_result)) {
			$return[] = $row;
		}
		*/
		$this->result_array = $return;

		return $return;
	}
}