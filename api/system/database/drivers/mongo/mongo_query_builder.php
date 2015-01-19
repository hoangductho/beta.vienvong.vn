<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 1/13/15
 * Time: 8:59 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Mongo_query_builder extends CI_DB {

    /**
     * QB define using aggregate function
     *
     * @var	array
     */
    protected $aggregate_setup;

    /**
     * QB Cache group function init parameter
     *
     * @var	array
     */
    protected $group_parameters;

    // --------------------------------------------------------------------
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
            $this->_track_aliases($table);
            $this->from($table);
        }

        if ( ! empty($limit))
        {
            $this->limit($limit, $offset);
        }

        $table = str_replace('`','',$this->_from_tables());

        if(!$this->table_exists($table)) {
            $this->display_error("Table <b>\"$table\"</b> don't exists!", '', TRUE);
        }

        if($this->aggregate_setup) {

            if(!empty($this->qb_where)) {
                $this->qb_groupby['$match'] = $this->qb_where;
            }

            var_dump($this->qb_groupby);

            $result = $this->db->{$table}
                ->aggregate($this->qb_groupby);

            print_r(var_dump($result['result']));
        }else {
            $result = $this->db->{$table}
                ->find($this->qb_where, $this->qb_select)
                ->limit($this->qb_limit)
                ->skip($this->qb_offset);

            var_dump(iterator_to_array($result));
        }
    }

    // --------------------------------------------------------------------

    /**
     * Get SELECT query string
     *
     * Compiles a SELECT query string and returns the sql.
     *
     * @param    string    the table name to select from (optional)
     * @param    bool    TRUE: resets QB values; FALSE: leave QB vaules alone
     * @return    string
     */
    public function get_compiled_select($table = '', $reset = true)
    {
        // set table will be query
        if ($table !== '')
        {
            $this->_track_aliases($table);
            $this->from($table);
        }

        // get table name
        $table = str_replace('`','',$this->_from_tables());

        // build where conditions
        if(!empty($this->qb_where)) {
            $where = json_encode($this->qb_where);
        }else {
            $where = '{}';
        }

        // build select fields
        if(!empty($this->qb_select)) {
            $select = json_encode($this->qb_select);
        }else {
            $select = '{}';
        }

        // build query
        $get_compiled = "db.{$table}.find({$where},{$select})";

        // build limited parameter
        if(!empty($this->qb_limit)) {
            $get_compiled .= ".limit({$this->qb_limit})";
        }

        // build offset parameter
        if(!empty($this->qb_offset)) {
            $get_compiled .= ".offset({$this->qb_offset})";
        }

        // clear cache of current query
        if($reset) {
            $this->_reset_select();
        }

        return $get_compiled;
    }

    // --------------------------------------------------------------------

    /**
     * WHERE
     *
     * Generates the WHERE portion of the query.
     * Separates multiple calls with 'AND'.
     *
     * @param    mixed
     * @param    mixed
     * @param    bool
     * @return    CI_DB_query_builder
     */
    public function where($key, $value = NULL, $escape = NULL)
    {
        $this->_wh('qb_where', $key, $value);
    }

    // --------------------------------------------------------------------

    /**
     * WHERE, HAVING
     *
     * @used-by	where()
     * @used-by	or_where()
     * @used-by	having()
     * @used-by	or_having()
     *
     * @param	string	$qb_key	'qb_where' or 'qb_having'
     * @param	mixed	$key
     * @param	mixed	$value
     * @param	string	$type
     * @param	bool	$escape
     * @return	CI_DB_query_builder
     */
    protected function _wh($qb_key, $key, $value = NULL, $type = 'AND ', $escape = NULL)
    {
//        $qb_cache_key = ($qb_key === 'qb_having') ? 'qb_cache_having' : 'qb_cache_where';

        if ( ! is_array($key))
        {
            $key = array($key => $value);
        }

        foreach($key as $wh=>$val) {
            $this->{$qb_key}[$wh] = $val;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Select
     *
     * Generates the SELECT portion of the query
     *
     * @param    string
     * @param    mixed
     * @return    CI_DB_query_builder
     *
     */

    public function select($select = '*', $escape = NULL)
    {
        // var to cache select field request
        $select_query = array();

        if($select !== '*') {

            // convert select string to array
            $select = explode(',', $select);

            foreach( $select as $field) {

                // replace all white space at right and left string
                $field = trim($field);

                // filter all different type mongoDB not support
                if(strpos($field, ' ')) {
                    $this->display_error(
                        "Select parameter <b>\"$field\"</b> don't supported by MongoDB driver",
                        '',
                        TRUE
                    );
                }

                // push select field to cache
                $select_query[$field] = 1;
            }

        }

        // don't get _id default field of MongoDB
        if(!isset($select_query['_id'])) {
            $select_query['_id'] = 0;
        }

        // set select query to global parameter
        $this->qb_select = $select_query;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * GROUP BY
     *
     * @param    string $by
     * @param    bool $escape
     * @return    CI_DB_query_builder
     */
    public function group_by($by, $escape = NULL)
    {
        // convert group_by string to array
        if(!is_array($by)) {
            $by = explode(',', $by);
        }

        $group = array();

        if(empty($by)) {
            $by = null;
        }else {
            foreach ($by as $key=>$value) {
                $group[$value] = '$'.trim($value);
            }
        }

        $this->qb_groupby['$group']['_id'] = $group;

        $this->aggregate_setup = true;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * SELECT [MAX|MIN|AVG|SUM]()
     *
     * @used-by	select_max()
     * @used-by	select_min()
     * @used-by	select_avg()
     * @used-by	select_sum()
     *
     * @param	string	$select	Field name
     * @param	string	$alias
     * @param	string	$type
     * @return	CI_DB_query_builder
     */
    protected function _max_min_avg_sum($select = '', $alias = '', $type = 'MAX')
    {
        // alias of aggregate function in operation
        $aggregate_type = array('MAX' => '<', 'MIN' => '>', 'AVG' => '', 'SUM' => '');

        // filter select fields query input
        if ( ! is_string($select) OR $select === '')
        {
            $this->display_error('db_invalid_query');
        }

        // filter type of function input
        $type = strtolower($type);

        if ( ! in_array($type, array('max', 'min', 'avg', 'sum')))
        {
            show_error('Invalid function type: '.$type);
            $this->display_error("Invalid function type: <b>'{$type}'</b>. A aggregate funtion have type 'MAX, MIN, SUM, AVG'.",'' , TRUE);
        }

        if($alias === '') {
            $alias = $select.'_'.strtoupper($type);
        }

        if(empty($this->qb_groupby)) {
            $this->group_by(null, FALSE);
        }

        $this->qb_groupby['$group'][$alias]['$'.$type] = '$'.$select;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Select Max
     *
     * Generates a SELECT MAX(field) portion of a query
     *
     * @param    string    the field
     * @param    string    an alias
     * @return    CI_DB_query_builder
     */

    public function select_max($select = '', $alias = '')
    {
        $this->_max_min_avg_sum($select, $alias, 'MAX');
    }

    // --------------------------------------------------------------------

    /**
     * Select Min
     *
     * Generates a SELECT MIN(field) portion of a query
     *
     * @param    string    the field
     * @param    string    an alias
     * @return    CI_DB_query_builder
     *
     */
    public function select_min($select = '', $alias = '')
    {
        $this->_max_min_avg_sum($select, $alias, 'MIN');
    }

    // --------------------------------------------------------------------


}
/**
 * End of Class Mongo_query_builder
 */