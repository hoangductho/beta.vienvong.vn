<?php
/**
 * Created by PhpStorm.
 * User: Hoang Duc Tho
 * Date: 1/8/15
 * Time: 11:12 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once('mongo_query_builder.php');

class CI_DB_mongo_driver extends Mongo_query_builder
{
    /**
     * Database driver
     *
     * @var    string
     */
    public $dbdriver = 'mongo';

    /**
     * Compression flag
     *
     * @var    bool
     */
    public $compress = FALSE;

    /**
     * DELETE hack flag
     *
     * Whether to use the MySQL "delete hack" which allows the number
     * of affected rows to be shown. Uses a preg_replace when enabled,
     * adding a bit more processing to all queries.
     *
     * @var    bool
     */
    public $delete_hack = TRUE;

    /**
     * Strict ON flag
     *
     * Whether we're running in strict SQL mode.
     *
     * @var    bool
     */
    public $stricton = FALSE;

    // --------------------------------------------------------------------

    /**
     * Identifier escape character
     *
     * @var    string
     */
    protected $_escape_char = '`';

    /**
     * Connect String
     *
     * Connect info to connect with MongoDB Server
     *
     * @var String
     */
    private $connection_string;

    /**
     * Connect Stream
     *
     * Stream connect with MongoDB Server
     *
     * @var Object
     */
    public $connection = false;

    /**
     * Select Database
     *
     * Using database to connect
     */
    public $db = null;


    // --------------------------------------------------------------------

    /**
     * Class constructor
     *
     * @param    array $params
     * @return    void
     */
    public function __construct($params)
    {
        if (is_array($params)) {
            foreach ($params as $key => $val) {
                $this->$key = $val;
            }
        }

        $this->db_connect();

        log_message('debug', 'Database Driver Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Initialize Database Settings
     *
     * @return    bool
     */
    public function initialize()
    {
        /* If an established connection is available, then there's
		 * no need to connect and select the database.
		 *
		 * Depending on the database driver, conn_id can be either
		 * boolean TRUE, a resource or an object.
		 */
        if ($this->connection) {
            return TRUE;
        } else {
            $this->db_connect();
        }
    }

    // --------------------------------------------------------------------

    /**
     * DB connect
     *
     * This is just a dummy method that all drivers will override.
     *
     * @return      mixed
     */
    public function db_connect($persistent = FALSE)
    {
        $options = array(
            'username' => $this->username,
            'password' => $this->password,
            'db' => $this->database
        );
        try {
            $this->_connection_string();
            $this->connection = new MongoClient($this->connection_string, $options);

            if (!sizeof($this->connection->getConnections())) {
                $this->connection = FALSE;
            } else {
                $this->db = $this->connection->{$this->database};
            }

            log_message('debug', 'Database Connected Successful');
            return $this;
        } catch (MongoConnectionException $e) {
            show_error("Unable to connect to MongoDB: {$e->getMessage()}", 500);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Persistent database connection
     *
     * @return    mixed
     */
    public function db_pconnect()
    {
        return $this->db_connect(TRUE);
    }

    // --------------------------------------------------------------------

    /**
     * Reconnect
     *
     * Keep / reestablish the db connection if no queries have been
     * sent for a length of time exceeding the server's idle timeout.
     *
     * This is just a dummy method to allow drivers without such
     * functionality to not declare it, while others will override it.
     *
     * @return      void
     */
    public function reconnect()
    {
        if ($this->connection !== FALSE && $this->connection->ping() === FALSE) {
            $this->connection = FALSE;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Database version number
     *
     * Returns a string containing the version of the database being used.
     * Most drivers will override this method.
     *
     * @return    string
     */
    public function version()
    {
        if (!$this->connection) {
            $this->initialize();
        }

        $conn_data = $this->connection->getConnections();

        return $conn_data[0]['server']['version'];
    }

    // --------------------------------------------------------------------

    /**
     * Create connection string
     *
     * @since v1.0.0
     */
    private function _connection_string()
    {

        $connection_string = "mongodb://";

        if (empty($this->hostname) || !isset($this->hostname)) {
            $this->hostname = 'localhost';
        }

        if (empty($this->database) || !isset($this->database)) {
            $this->database = 'admin';
        }

        if(isset($this->port) && !empty($this->port)):
            $connection_string .= "{$this->hostname}:{$this->port}";
        else:
            $connection_string .= "{$this->hostname}";
        endif;

        $this->connection_string = trim($connection_string);
    }

    // --------------------------------------------------------------------

    /**
     * Close DB Connection
     *
     * @return    void
     */

    public function close()
    {
        $this->connection->close();
        log_message('debug', 'Database Connection Closed');
    }

    // --------------------------------------------------------------------

    /**
     * Returns an array of table names
     *
     * @param    string $constrain_by_prefix = FALSE
     * @return    array
     */
    public function list_tables($constrain_by_prefix = false)
    {
        return $this->db->getCollectionNames();
    }

    // --------------------------------------------------------------------

    /**
     * Determine if a particular table exists
     *
     * @param    string $table_name
     * @return    bool
     */
    public function table_exists($table_name)
    {
        // get list tables into database
        $list_tables = $this->list_tables();

        // find table requested in list tables
        foreach($list_tables as $table) {
            if($table == $table_name) {
                return TRUE;
            }
        }

        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * "Count All" query
     *
     * Generates a platform-specific query string that counts all records in
     * the specified database
     *
     * @param    string
     * @return    int
     */
    public function count_all($table = '')
    {
        if ($table === '')
        {
            return 0;
        }

        return $this->db->{$table}->count();
    }

    // --------------------------------------------------------------------

    /**
     * Fetch Field Names
     *
     * @param    string    the table name
     * @return    array
     */
    public function list_fields($table)
    {
        if($this->table_exists($table)) {
            $this->display_error('MongoDB do not support <b>list_fields</b> function. Because MongoDB Collections do not have fix structure', '', TRUE);
            return TRUE;
        }else {
            $this->display_error('Table <b>"'.$table.'"</b> is not exist', '', TRUE);
            return FALSE;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Determine if a particular field exists
     *
     * @param    string
     * @param    string
     * @return    bool
     */
    public function field_exists($field_name, $table_name)
    {
        if($this->table_exists($table_name)) {
            $this->display_error('MongoDB do not support <b>field_exists</b> function. Because MongoDB Collections do not have fix structure', '', TRUE);
            return TRUE;
        }else {
            $this->display_error('Table <b>"'.$table_name.'"</b> is not exist', '', TRUE);
            return FALSE;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Returns an object with field data
     *
     * @param    string $table the table name
     * @return    array
     */
    public function field_data($table)
    {
        if($this->table_exists($table)) {
            $this->display_error('MongoDB do not support <b>field_data</b> function. Because MongoDB Collections do not have fix structure', '', TRUE);
            return TRUE;
        }else {
            $this->display_error('Table <b>"'.$table.'"</b> is not exist', '', TRUE);
            return FALSE;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Compile Bindings
     *
     * @param    string    the sql statement
     * @param    array    an array of bind data
     * @return    string
     */
    public function compile_binds($sql, $binds)
    {
        $this->display_error('Mongo Driver not support compile_binds function',
                            'Mongo Driver not support', TRUE);
    }

    // --------------------------------------------------------------------

    /**
     * Set client character set
     *
     * @param    string
     * @return    bool
     */
    public function db_set_charset($charset)
    {
        $this->display_error('Mongo Drive not support db_set_charset function.
                            \n Because MongoDB storage by <a href="http://bsonspec.org/#/specification">BSON</a> object, and using UTF-8 as soon as default.',
            'Mongo Driver not support', TRUE);
    }

    // --------------------------------------------------------------------

    /**
     * Get INSERT query string
     *
     * Compiles an insert query and returns the sql
     *
     * @param    string    the table to insert into
     * @param    bool    TRUE: reset QB values; FALSE: leave QB values alone
     * @return    string
     */
    public function get_compiled_insert($table = '', $reset = true)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Get UPDATE query string
     *
     * Compiles an update query and returns the sql
     *
     * @param    string    the table to update
     * @param    bool    TRUE: reset QB values; FALSE: leave QB values alone
     * @return    string
     */
    public function get_compiled_update($table = '', $reset = true)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Insert
     *
     * Compiles an insert string and runs the query
     *
     * @param    string    the table to insert data into
     * @param    array    an associative array of insert values
     * @param    bool $escape Whether to escape values and identifiers
     * @return    object
     */
    public function insert($table = '', $set = NULL, $escape = NULL)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Insert_Batch
     *
     * Compiles batch insert strings and runs the queries
     *
     * @param    string $table Table to insert into
     * @param    array $set An associative array of insert values
     * @param    bool $escape Whether to escape values and identifiers
     * @return    int    Number of rows inserted or FALSE on failure
     */
    public function insert_batch($table = '', $set = NULL, $escape = NULL)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Generate an insert string
     *
     * @param    string    the table upon which the query will be performed
     * @param    array    an associative array data of key/values
     * @return    string
     */
    public function insert_string($table, $data)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Determines if a query is a "write" type.
     *
     * @param    string    An SQL query string
     * @return    bool
     */
    public function is_write_type($sql)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Primary
     *
     * Retrieves the primary key. It assumes that the row in the first
     * position is the primary key
     *
     * @param    string $table Table name
     * @return    string
     */
    public function primary($table)
    {
        return '_id';
    }

    // --------------------------------------------------------------------

    /**
     * Execute the query
     *
     * Accepts an SQL string as input and returns a result object upon
     * successful execution of a "read" type query. Returns boolean TRUE
     * upon successful execution of a "write" type query. Returns boolean
     * FALSE upon failure, and if the $db_debug variable is set to TRUE
     * will raise an error.
     *
     * @param    string $sql
     * @param    array $binds = FALSE        An array of binding data
     * @param    bool $return_object = NULL
     * @return    mixed
     */
    public function query($sql, $binds = false, $return_object = NULL)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Replace
     *
     * Compiles an replace into string and runs the query
     *
     * @param    string    the table to replace data into
     * @param    array    an associative array of insert values
     * @return    object
     */
    public function replace($table = '', $set = NULL)
    {
    }

    // --------------------------------------------------------------------

    /**
     * The "set" function.
     *
     * Allows key/value pairs to be set for inserting or updating
     *
     * @param    mixed
     * @param    string
     * @param    bool
     * @return    CI_DB_query_builder
     */
    public function set($key, $value = '', $escape = NULL)
    {
    }

    // --------------------------------------------------------------------

    /**
     * The "set_insert_batch" function.  Allows key/value pairs to be set for batch inserts
     *
     * @param    mixed
     * @param    string
     * @param    bool
     * @return    CI_DB_query_builder
     */
    public function set_insert_batch($key, $value = '', $escape = NULL)
    {
    }

    // --------------------------------------------------------------------

    /**
     * The "set_update_batch" function.  Allows key/value pairs to be set for batch updating
     *
     * @param    array
     * @param    string
     * @param    bool
     * @return    CI_DB_query_builder
     */
    public function set_update_batch($key, $index = '', $escape = NULL)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Simple Query
     * This is a simplified version of the query() function. Internally
     * we only use it when running transaction commands since they do
     * not require all the features of the main query() function.
     *
     * @param    string    the sql query
     * @return    mixed
     */
    public function simple_query($sql)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Complete Transaction
     *
     * @return    bool
     */
    public function trans_complete()
    {
    }

    // --------------------------------------------------------------------

    /**
     * Disable Transactions
     * This permits transactions to be disabled at run-time.
     *
     * @return    void
     */
    public function trans_off()
    {
    }

    // --------------------------------------------------------------------

    /**
     * Start Transaction
     *
     * @param    bool $test_mode = FALSE
     * @return    void
     */
    public function trans_start($test_mode = false)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Lets you retrieve the transaction flag to determine if it has failed
     *
     * @return    bool
     */
    public function trans_status()
    {
    }

    // --------------------------------------------------------------------

    /**
     * Enable/disable Transaction Strict Mode
     * When strict mode is enabled, if you are running multiple groups of
     * transactions, if one group fails all groups will be rolled back.
     * If strict mode is disabled, each group is treated autonomously, meaning
     * a failure of one group will not affect any others
     *
     * @param    bool $mode = TRUE
     * @return    void
     */
    public function trans_strict($mode = true)
    {
    }

    // --------------------------------------------------------------------

    /**
     * UPDATE
     *
     * Compiles an update string and runs the query.
     *
     * @param    string $table
     * @param    array $set An associative array of update values
     * @param    mixed $where
     * @param    int $limit
     * @return    object
     */
    public function update($table = '', $set = NULL, $where = NULL, $limit = NULL)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Update_Batch
     *
     * Compiles an update string and runs the query
     *
     * @param    string    the table to retrieve the results from
     * @param    array    an associative array of update values
     * @param    string    the where key
     * @return    int    number of rows affected or FALSE on failure
     */
    public function update_batch($table = '', $set = NULL, $index = NULL)
    {
    }

    // --------------------------------------------------------------------

    /**
     * Generate an update string
     *
     * @param    string    the table upon which the query will be performed
     * @param    array    an associative array data of key/values
     * @param    mixed    the "where" statement
     * @return    string
     */
    public function update_string($table, $data, $where)
    {
    }

}