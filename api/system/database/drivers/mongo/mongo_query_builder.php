<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 1/13/15
 * Time: 8:59 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Mongo_query_builder extends CI_DB {

    public function __construct() {
//        parent::__construct();
    }

    /**
     * Get
     *
     * Compiles the select statement based on the other functions called
     * and runs the query
     *
     * @param    string    the table
     * @param    string    the limit clause
     * @param    string    the offset clause
     * @return    object
     */
    public function get($table = '', $limit = NULL, $offset = NULL)
    {
        if ($table !== '')
        {
            if(!$this->table_exists($table)) {
                $this->display_error("Table <b>\"$table\"</b> don't exists!", '', TRUE);
            }
            $this->_track_aliases($table);
            $this->from($table);
        }

        if ( ! empty($limit))
        {
            $this->limit($limit, $offset);
        }

        $table = str_replace('`','',$this->_from_tables());

        $result = $this->db->{$table}->find()->limit($this->qb_limit)->skip($this->qb_offset);

        foreach ($result as $doc) {
            var_dump($doc);
        }
    }

    // --------------------------------------------------------------------
}
/**
 * End of Class Mongo_query_builder
 */