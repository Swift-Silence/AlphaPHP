<?php

namespace AlphaPHP\Core\Model\Data;

use \AlphaPHP\Core\Config;
use \AlphaPHP\Debug\Logger;

/**
 * Handles all database connectivity and queries.
 *
 * Uses PDO and PDO prepared statements for SQL injection security.
 */

class DB
{

    /**
     * Singleton instance of the class
     * @var \Alpha\Data\SQL\DB
     */
    private static $instance;

    /**
     * Singleton function to retrieve object instance
     * @return \Alpha\Data\SQL\DB
     */
    public static function singleton()
    {
        if (is_object(static::$instance))
        {
            return static::$instance;
        }
        else
        {
            static::$instance = new static();
            return static::$instance;
        }
    }



    /**
     * MySQL host
     * @var string
     */
    private $host;

    /**
     * MySQL user
     * @var string
     */
    private $user;

    /**
     * MySQL password
     * @var string
     */
    private $password;

    /**
     * MySQL Database name
     * @var string
     */
    private $db;

    /**
     * Latest PDOStatement object
     * @var \PDOStatement|null
     */
    private $stmt;

    public static $executed_queries = 0;

    /**
     * Private constructor for singleton pattern. Attempts connection to the
     * database and logs the error if present.
     */
    private function __construct()
    {
        $this->log("DB object instantiated.");

        // Grab database config
        $Config = Config::singleton();
        $this->host =       $Config->get('DB_HOST');
        $this->user =       $Config->get('DB_USER');
        $this->password =   $Config->get('DB_PASSWORD');
        $this->db =         $Config->get('DB_DB');

        // Attempt connection
        try {
            $this->connect();
        } catch (\Throwable $e) {
            $this->log("Error connecting to database: " . $e->getMessage() . ' [' . $e->getFile() . ':' . $e->getLine() . ']');
            Logger::dump(true);
        }
    }

    /**
     * Performs a query on the MySQL server
     * @param  string $sql   SQL statement in PDO prepared style
     * @param  array  $data  Data to use for the prepared style variables
     * @return \PDOStatement Result of the query
     */
    public function query(string $sql, array $data = [])
    {
        $this->log("Preparing SQL query <b>[$sql] <i>[Data: {{data}}]</i></b>...", true, $data);

        try {
            $this->stmt = $this->PDO->prepare($sql);

            $this->log("Executing SQL query...", true);
            $this->stmt->execute($data);
            $this->log("Query executed.", true);

            static::$executed_queries++;
        } catch (\Throwable $e) {
            $this->log("Error preparing query: <b>{$e->getMessage()}</b> [{$e->getFile()}:<b>{$e->getLine()}</b>]");
            return false;
        }

        return $this->stmt;
    }

    /**
     * Method for checking if a table exists in the database.
     * @param  string  $table_name Name of the table to be looked for.
     * @return boolean
     */
    public function hasTable(string $table_name)
    {
        $sql = "SELECT 1 FROM `{$table_name}` LIMIT 1";

        return (bool) $this->query($sql);
    }

    /**
     * Returns the total number of rows in a table
     * @param  string  $table_name Name of the table
     * @param  string  $where      Where clause in pure SQL syntax format
     * @return int                 Number of table rows.
     */
    public function countRows(string $table_name, string $where, array $placeholders)
    {
        $sql = "SELECT COUNT(*) FROM `{$table_name}` {$where}";
        //print_r($sql);
        //print_r($placeholders);
        $this->query($sql, $placeholders);

        //var_dump($this->stmt->errorCode());
        if ($this->stmt === null) return null;

        if (!$num = $this->stmt->fetch()) return (int) 0;
        else return $num['COUNT(*)'];
   }

    /**
     * Method for creating a table in the database. Takes cols as an array of objects.
     * @param  string $table_name SQL Table name
     * @param  array  $cols       Array of column object
     * @return \PDOStatement
     */
    public function createTable(string $table_name, array $cols)
    {
        $sql = "CREATE TABLE `$table_name` (";
        foreach ($cols as $col)
        {
            $sql .= " `{$col->name}` {$col->type}";

            if ($col->values !== null && $col->values) 
            {
                if (is_array($col->values))
                {
                    $sql .= "(";
                    foreach ($col->values as $val)
                    {
                        $sql .= "'{$val}',";
                    }
                    $sql = substr($sql, 0, -1) . ")"; // Trim lastr comma and add closing parenthesis
                }
                else 
                {
                    $sql .= "({$col->values})";
                }
            }

            if (!$col->null)    $sql .= " NOT NULL";
            if ($col->primary)  $sql .= " PRIMARY KEY";
            if ($col->AI)       $sql .= " AUTO_INCREMENT";

            $sql .= ",";
        }
        $sql = substr($sql, 0, -1) . " )";

        $this->query($sql);
        return $this->stmt;
    }

    /**
     * Connects to the database
     */
    private function connect()
    {
        $this->log("Attempting to connect to database <b>`{$this->db}`</b> as <b>{$this->user}@{$this->host}</b>...");

        // Establish DSN and options for PDO
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
        $options = [
            \PDO::ATTR_ERRMODE               => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE    => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES      => false
        ];

        // Create PDO object.
        $this->PDO = new \PDO($dsn, $this->user, $this->password, $options);

        // This log only runs if PDO initialized successfully.
        $this->log("Successfully connected to <b>`{$this->db}`</b> as <b>{$this->user}@{$this->host}</b>, PDO initialized successfully.");
    }

    /**
     * Log method for logging messages to the debug logger
     * @param  string $message Message to log
     */
    private function log(string $message, bool $query = false, array $data = [])
    {
        if ($query)
        {
            $s_data = "";
            foreach ($data as $placeholder => $val)
            {
                $s_data .= "<u>['{$placeholder}' => '{$val}']</u> ";
            }
            $s_data = rtrim($s_data);

            Logger::log($this, "<b>[SQL QUERY]</b> " . str_replace("{{data}}", $s_data, $message));
        }
        else
        {
            Logger::log($this, "$message");
        }
    }

}
