
class DBBase {
	/**
	 * @var resource
	 */
	protected $con;

	protected $query_result;

	protected $result_array;

	public function connect($dbhost, $dbname, $dbuser, $dbpass = '') {}
	public function execute($query) {}
	public function getResultAsArray() {}
}