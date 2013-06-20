<?PHP
#################################################################################
## Developed by Manifest Interactive, LLC                                      ##
## http://www.manifestinteractive.com                                          ##
## ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ ##
##                                                                             ##
## THIS SOFTWARE IS PROVIDED BY MANIFEST INTERACTIVE 'AS IS' AND ANY           ##
## EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE         ##
## IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR          ##
## PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL MANIFEST INTERACTIVE BE          ##
## LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR         ##
## CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF        ##
## SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR             ##
## BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,       ##
## WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE        ##
## OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,           ##
## EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                          ##
## ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ ##
## Authors of file: Peter Schmalfeldt & John Kramlich                          ##
#################################################################################

/**
 * @category Apple Push Notification Service using PHP & MySQL
 * @package EasyAPNs
 * @author Peter Schmalfeldt <manifestinteractive@gmail.com>
 * @author John Kramlich <me@johnkramlich.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link http://code.google.com/p/easyapns/
 */

/**
 * Begin Document
 */

class DbConnect
{
	/**
	* Connection to MySQL.
	*
	* @var string
	*/
	private $link;

	/**
	* Holds the most recent connection.
	*
	* @var string
	*/
	private $recent_link = null;

	/**
	* Holds the contents of the most recent SQL query.
	*
	* @var string
	*/
	private $sql = '';

	/**
	* Holds the number of queries executed.
	*
	* @var integer
	*/
	private $query_count = 0;

	/**
	* The text of the most recent database error message.
	*
	* @var string
	*/
	private $error = '';
	private $connect_error = '';

	/**
	* The error number of the most recent database error message.
	*
	* @var integer
	*/
	private $errno = null;
	
	/**
	* The number of times an operation tried to recover in current session.
	*
	* @var integer
	*/
	private $retryCount = 0;

	/**
	* Do we currently have a lock in place?
	*
	* @var boolean
	*/
	private $is_locked = false;

	/**
	* Show errors? If set to true, the error message/sql is displayed.
	*
	* @var boolean
	*/
	private $show_errors = true;
	
	/**
	* Log errors? If set to true, the error message/sql is logged.
	*
	* @var boolean
	*/
	public $log_errors = false;
	
	/**
	* The Database.
	*
	* @var string
	*/
	public $DB_DATABASE;
	
	/**
	* The variable used to contain a singleton instance of the database connection.
	*
	* @var string
	*/
	static $instance;

	/**
	* The number of rows affected by the most recent query.
	*
	* @var string
	*/
	public $affected_rows;
	public $insert_id;
	public $num_rows;

	/**
	* Constructor. Initializes a database connection and sects our database.
	*/
	private function __construct($development = null)
	{
		if($development == "release")
		{
			$this->DB_HOST     = '';
			$this->DB_DATABASE = '';
		}
		if($development == "releaseDebug")
		{
			$this->DB_HOST     = '';
			$this->DB_DATABASE = '';
		}
		else if($development == "beta")
		{
			$this->DB_HOST     = '';
			$this->DB_DATABASE = '';
		}
		else if($development == "betaDebug")
		{
			$this->DB_HOST     = '';
			$this->DB_DATABASE = '';
		}
		else if($development == "debug")
		{
			$this->DB_HOST     = '';
			$this->DB_DATABASE = '';
		}
		$this->DB_USERNAME = '';
		$this->DB_PASSWORD = '';
		
		$this->link = new mysqli($this->DB_HOST, $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_DATABASE);
		$this->recent_link =& $this->link;

		if($this->link->errno)
		{
			$call = debug_backtrace(false);
			$call = (isset($call[1])) ? $call[1] : $call[0];
			$this->raise_error($this->connect_error(), $call['file'], $call['line']);
		}

	}
	
	public static function getInstance($development = null)
	{
		if (!isset(self::$instance))
		{
			$c = __CLASS__;
			self::$instance = new $c($development);
        }

		return self::$instance;
	}

	/**
	* Executes a sql query. If optional $only_first is set to true, it will
	* return the first row of the result as an array.
	*
	* @param  string  Query to run
	* @param  bool    Return only the first row, as an array?
	* @return mixed
	*/
	function query($sql, $only_first = false)
	{
		$this->recent_link =& $this->link;
		$this->sql =& $sql;
		
		if(!$result = $this->link->query($sql))
		{
			// retry deadlock up to 3 times
			if($this->errno() == 1213 && $this->retryCount < 3)
			{
				$this->retryCount++;
				
				// log error to file
				error_log("Deadlock found when trying to get lock, rety: ".$this->retryCount);

				return $this->query($sql, $only_first);
			}
			else
			{
				$call = debug_backtrace(false);
				$call = (isset($call[1])) ? $call[1] : $call[0];
				$this->raise_error($this->error(), $call['file'], $call['line']);
			}
		}
		
		$this->retryCount = 0;
		$this->affected_rows = $this->link->affected_rows;
		$this->insert_id = $this->link->insert_id;

		$this->query_count++;

		if ($only_first)
		{
			$return = $result->fetch_array();
			$this->free_result($result);
			return $return;
		}
		return $result;
	}

	/**
	* Fetches a row from a query result and returns the values from that row as an array.
	*
	* @param  string  The query result we are dealing with.
	* @return array
	*/
	function fetch_array($result)
	{
		return @$result->fetch_assoc();
	}

	/**
	* Returns the number of rows in a result set.
	*
	* @param  string  The query result we are dealing with.
	* @return integer
	*/
	function num_rows($result)
	{
		return $this->link->num_rows;
	}

	/**
	* Retuns the number of rows affected by the most recent query
	*
	* @return integer
	*/
	function affected_rows()
	{
		return $this->link->affected_rows;
	}
	

	/**
	* Returns the number of queries executed.
	*
	* @param  none
	* @return integer
	*/
	function num_queries()
	{
		return $this->query_count;
	}

	/**
	* Lock database tables
	*
	* @param   array  Array of table => lock type
	* @return  void
	*/
	function lock($tables)
	{
		if (is_array($tables) AND count($tables))
		{
			$sql = '';

			foreach ($tables AS $name => $type)
			{
				$sql .= (!empty($sql) ? ', ' : '') . "$name $type";
			}

			$this->query("LOCK TABLES $sql");
			$this->is_locked = true;
		}
	}

	/**
	* Unlock tables
	*/
	function unlock()
	{
		if ($this->is_locked)
		{
			$this->query("UNLOCK TABLES");
		}
	}

	/**
	* Returns the ID of the most recently inserted item in an auto_increment field
	*
	* @return  integer
	*/
	function insert_id()
	{
		return $this->link->insert_id;
	}

	/**
	* Escapes a value to make it safe for using in queries.
	*
	* @param  string  Value to be escaped
	* @param  bool    Do we need to escape this string for a LIKE statement?
	* @return string
	*/
	function prepare($value, $do_like = false)
	{
		if ($do_like)
		{
			$value = str_replace(array('%', '_'), array('\%', '\_'), $value);
		}

		return $this->link->real_escape_string($value);
	}

	/**
	* Frees memory associated with a query result.
	*
	* @param  string   The query result we are dealing with.
	* @return boolean
	*/
	function free_result($result)
	{
		return @$result->free_result();
	}
	
	function next_result()
	{
		$this->link->next_result();
	}

	/**
	* Turns database error reporting on
	*/
	function show_errors()
	{
		$this->show_errors = true;
	}

	/**
	* Turns database error reporting off
	*/
	function hide_errors()
	{
		$this->show_errors = false;
	}

	/**
	* Closes our connection to MySQL.
	*
	* @param  none
	* @return boolean
	*/
	function close()
	{
		$this->sql = null;
		return $this->link->close();
	}

	/**
	* Returns the MySQL error message.
	*
	* @param  none
	* @return string
	*/
	function error()
	{
		$this->error = (is_null($this->recent_link)) ? '' : $this->link->error;	
		return $this->error;
	}
	
	function connect_error()
	{
		$this->connect_error = (is_null($this->recent_link)) ? '' : $this->link->connect_error;	
		return $this->connect_error;
	}

	/**
	* Returns the MySQL error number.
	*
	* @param  none
	* @return string
	*/
	function errno()
	{
		$this->errno = (is_null($this->recent_link)) ? 0 : $this->link->errno ;
		return $this->errno;
	}

	/**
	* If there is a database error, the script will be stopped and an error message displayed.
	*
	* @param  string  The error message. If empty, one will be built with $this->sql.
	* @return string
	*/
	function raise_error($errorMessage = '', $file = NULL, $line = NULL)
	{
		$yongopal = YongoPal::getInstance();
		$message = "MySQL Error: ".$errorMessage.". (errno: ".$this->errno().")";
		$yongopal->handleError(1, $message, $file, $line, null);
	}
}

?>
