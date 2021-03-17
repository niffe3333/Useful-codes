<?php

namespace classes;

use mysqli;

class db
{

    protected $connection;
    protected $query;
    protected $show_errors = TRUE;
    protected $query_closed = TRUE;

    // Creates a connection with the base
    public function __construct($dbhost = 'localhost', $dbuser = 'root', $dbpass = '', $dbname = 'template', $charset = 'utf8')
    {

        $this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

        if ($this->connection->connect_error) {
            $this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
        }
        $this->connection->set_charset($charset);

        return $this;
    }

    public function query($query)
    {
        if (!$this->query_closed) {
            $this->query->close();
        }
        if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
                $types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
                    if (is_array($args[$k])) {
                        foreach ($args[$k] as $j => &$a) {
                            $types .= $this->_gettype($args[$k][$j]);
                            $args_ref[] = &$a;
                        }
                    } else {
                        $types .= $this->_gettype($args[$k]);
                        $args_ref[] = &$arg;
                    }
                }
                array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
            if ($this->query->errno) {
                $this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
            }
            $this->query_closed = FALSE;
        } else {
            $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
        }
        return $this;
    }

    // Fetch multiple record
    public function fetchAll($callback = null)
    {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            if ($callback != null && is_callable($callback)) {
                $value = call_user_func($callback, $r);
                if ($value == 'break') break;
            } else {
                $result[] = $r;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }
    // Fetch record
    public function fetchArray()
    {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            foreach ($row as $key => $val) {
                $result[$key] = $val;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    private function _gettype($var)
    {
        if (is_string($var)) return 's';
        if (is_float($var)) return 'd';
        if (is_int($var)) return 'i';
        return 'b';
    }


    // close database
    public function close()
    {
        return $this->connection->close();
    }

    //Returns the auto generated id used in the latest query
    public function lastInsertID()
    {
        return $this->connection->insert_id;
    }

    //shows errors
    public function error($error)
    {
        if ($this->show_errors) {
            exit($error);
        }
    }
    //Escapes special characters in a string
    public function real_escape_string($string)
    {


        return  $this->connection->real_escape_string($string);
    }


    /**
     * Generate random text Lorem ipsum
     *
     * @param int $count number of wordsq
     */
   static public function loremIpsum($count = 1)
    {
        $loremIpsumComplete = '';

        $loremIpsum = explode(' ', file_get_contents('loremIpsum.txt'));

        if ($count >= 1) {

            for ($i = 0; $i < $count; $i++) {
                $random = rand(0, 999);
                $loremIpsumComplete .= ' ' . $loremIpsum[$random];
            }
        }
        return $loremIpsumComplete;
    }
}
