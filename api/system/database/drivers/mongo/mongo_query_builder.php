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
     * Comparison
     *
     * All comparison orders of where and having conditions
     */
    private  $comparison = array(
                                    '$gte' => '>=',
                                    '$lte' => '<=',
                                    '$ne' => '!=',
                                    '$gt' => '>',
                                    '$lt' => '<'
                                );

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

        // set table will be query
        if ($table !== '')
        {
            if(!empty($this->qb_from[0])) {
                $this->qb_from = null;
            }
            $this->from($table);
        }elseif(empty($this->qb_from[0])) {
            $this->display_error('Table\'s name just never setup.','',TRUE);
        }

        // set limit and offset conditions
        if ( ! empty($limit))
        {
            $this->limit($limit, $offset);
        }

        $table = str_replace('`','',$this->qb_from[0]);

        if(!$this->table_exists($table)) {
            $this->display_error("Table <b>\"$table\"</b> don't exists!", '', TRUE);
        }

        $select = $this->_build_stages();

        $result = $result = $this->db->{$table}
            ->aggregate($select);

        $this->_reset_select();

        return $result;
    }

    // --------------------------------------------------------------------

    /**
     * Build all stages of aggregate query function
     *
     * Generates a query stage array based on which functions were used.
     * Should not be called directly.
     *
     * @return	array
     */
    private  function _build_stages()
    {
        $select = array();

        // Filter Where conditions
        if(!empty($this->qb_where)) {
            array_push($select, array('$match'=>$this->qb_where));
        }

        if(!empty($this->qb_groupby)) {

            // Group By compute
            if(!empty($this->qb_groupby)) {
                array_push($select, $this->qb_groupby);
            }

        }else {

            // build offset condition
            if(!empty($this->qb_offset)) {
                array_push($select, array('$skip' => $this->qb_offset));
            }

            // build limit condition
            if(!empty($this->qb_limit)) {
                array_push($select, array('$limit' => $this->qb_limit));
            }

            // build select required
            if(!empty($this->qb_select)) {
                array_push($select, array('$project' => $this->qb_select));
            }
        }

        // Having group
        if(!empty($this->qb_having)) {
            array_push($select, array('$match'=>$this->qb_having));
        }

        return $select;
    }

    // --------------------------------------------------------------------

    /**
     * Get_Where
     *
     * Allows the where clause, limit and offset to be added directly
     *
     * @param    string $table
     * @param    string $where
     * @param    int $limit
     * @param    int $offset
     * @return    object
     */
    public function get_where($table = '', $where = NULL, $limit = NULL, $offset = NULL)
    {
        // set where conditions
        $this->where($where);

        // get result query
        $result = $this->get($table, $limit, $offset);

        return $result;
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
            if(!empty($this->qb_from[0])) {
                $this->qb_from = null;
            }
            $this->from($table);
        }elseif(empty($this->qb_from[0])) {
            $this->display_error('Table\'s name just never setup.','',TRUE);
        }

        // get table name
        $table = str_replace('`','',$this->qb_from[0]);

        // build aggregate stages
        $stages = json_encode($this->_build_stages(), TRUE);

        $stages = substr($stages, 1, strlen($stages)-2);

        // build aggregate query
        $get = "db.{$table}.aggregate({$stages})";

        // clear cache of current query
        if($reset) {
            $this->_reset_select();
        }

        return $get;
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
     * OR WHERE
     *
     * Generates the WHERE portion of the query.
     * Separates multiple calls with 'OR'.
     *
     * @param    mixed
     * @param    mixed
     * @param    bool
     * @return    CI_DB_query_builder
     */
    public function or_where($key, $value = NULL, $escape = NULL)
    {
        $this->_wh('qb_where', $key, $value, ' OR ');
    }

    // --------------------------------------------------------------------

    /**
     * WHERE IN
     *
     * Generates a WHERE field IN('item', 'item') SQL query,
     * joined with 'AND' if appropriate.
     *
     * @param    string $key The field to search
     * @param    array $values The values searched on
     * @param    bool $escape
     * @return    CI_DB_query_builder
     */
    public function where_in($key = NULL, $values = NULL, $escape = NULL)
    {
        $this->_where_in($key, $values);
    }

    // --------------------------------------------------------------------

    /**
     * WHERE NOT IN
     *
     * Generates a WHERE field NOT IN('item', 'item') SQL query,
     * joined with 'AND' if appropriate.
     *
     * @param    string $key The field to search
     * @param    array $values The values searched on
     * @param    bool $escape
     * @return    CI_DB_query_builder
     */
    public function where_not_in($key = NULL, $values = NULL, $escape = NULL)
    {
        $this->_where_in($key, $values, TRUE);
    }

    // --------------------------------------------------------------------

    /**
     * OR WHERE IN
     *
     * Generates a WHERE field IN('item', 'item') SQL query,
     * joined with 'OR' if appropriate.
     *
     * @param    string $key The field to search
     * @param    array $values The values searched on
     * @param    bool $escape
     * @return    CI_DB_query_builder
     */
    public function or_where_in($key = NULL, $values = NULL, $escape = NULL)
    {
        $this->_where_in($key, $values, FALSE, 'OR');
    }

    // --------------------------------------------------------------------

    /**
     * OR WHERE NOT IN
     *
     * Generates a WHERE field NOT IN('item', 'item') SQL query,
     * joined with 'OR' if appropriate.
     *
     * @param    string $key The field to search
     * @param    array $values The values searched on
     * @param    bool $escape
     * @return    CI_DB_query_builder
     */
    public function or_where_not_in($key = NULL, $values = NULL, $escape = NULL)
    {
        $this->_where_in($key, $values, TRUE, 'OR');
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

        if(empty($key)) {
            return $this;
        }

        if ( ! is_array($key))
        {
            $key = array($key => $value);
        }

        $match = array();

        foreach($key as $wh=>$val) {
            $complie = $this->_compile_where_comparison($wh, $val);

            $match[$complie['key']] = $complie['value'];
        }

        if(trim($type) === 'OR'){
            $this->{$qb_key} = $this->_build_or_where($this->{$qb_key}, $match);
        }else {
            $this->{$qb_key} = array_merge_recursive($this->{$qb_key}, $match);
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Internal WHERE IN
     *
     * @used-by	where_in()
     * @used-by	or_where_in()
     * @used-by	where_not_in()
     * @used-by	or_where_not_in()
     *
     * @param	string	$key	The field to search
     * @param	array	$values	The values searched on
     * @param	bool	$not	If the statement would be IN or NOT IN
     * @param	string	$type
     * @param	bool	$escape
     * @return	CI_DB_query_builder
     */
    protected function _where_in($key = NULL, $values = NULL, $not = FALSE, $type = 'AND ', $escape = NULL)
    {
        if ($key === NULL OR $values === NULL)
        {
            return $this;
        }

        if ( ! is_array($values))
        {
            $values = array($values);
        }

        if(!is_string($key)) {
            $this->display_error("Field's name invalid. Field's is a string only", '', TRUE);
        }

        $match = array();

        if(!$not){
            $match[$key] = array('$in' => $values);
        }else {
            $match[$key] = array('$nin' => $values);
        }

//        array_push($match[$key], );

        if(trim($type) === 'OR'){
            $this->qb_where = $this->_build_or_where($this->qb_where, $match);
        }else {
            $this->qb_where = array_merge_recursive($this->qb_where, $match);
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Build "OR" of Where
     *
     * Build "OR" phrase of Where conditions
     *
     * @param   array   $where
     * @param   array   $match
     * @return  array   new statement of where condition
     */
    protected function _build_or_where($where, $match) {
        if(empty($where)) {
            $where = $match;
        }else {
            $order['$or'] = array();
            array_push($order['$or'], $where);
            array_push($order['$or'], $match);
            $where = $order;
        }

        return $where;
    }

    // --------------------------------------------------------------------

    /**
     * Compile WHERE, HAVING statements
     *
     * Escapes identifiers in WHERE and HAVING statements at execution time.
     *
     * Required so that aliases are tracked properly, regardless of wether
     * where(), or_where(), having(), or_having are called prior to from(),
     * join() and dbprefix is added only if needed.
     *
     * @param	string	$key    field name compare
     * @param   string  $value  value compare
     * @return	string	SQL statement
     */
    protected function _compile_where_comparison($key, $value) {
        foreach ($this->comparison as $comp_key => $comp_val) {
            if(strpos($key, $comp_val) !== FALSE) {
                return array(
                    'key' => trim(str_replace($comp_val,'', $key)),
                    'value' => array(
                        $comp_key => $value
                    )
                );
            }
        }

        return array('key' => trim($key), 'value' => $value);
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
        if(!empty($by) && !is_array($by)) {
            $by = explode(',', $by);
        }

        $group = array();

        if(empty($by)) {
            $group = null;
        }else {
            foreach ($by as $key=>$value) {
                $group[$value] = '$'.trim($value);
            }
        }

        $this->qb_groupby['$group']['_id'] = $group;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * HAVING
     *
     * Separates multiple calls with 'AND'.
     *
     * @param    string $key
     * @param    string $value
     * @param    bool $escape
     * @return    object
     */
    public function having($key, $value = NULL, $escape = NULL)
    {
        $this->_wh('qb_having', $key, $value);
    }

    // --------------------------------------------------------------------

    /**
     * OR HAVING
     *
     * Separates multiple calls with 'OR'.
     *
     * @param    string $key
     * @param    string $value
     * @param    bool $escape
     * @return    object
     */
    public function or_having($key, $value = NULL, $escape = NULL)
    {
        $this->_wh('qb_having', $key, $value, ' OR ');
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

        $this->qb_groupby['$group'][$alias]['$'.$type] = '$'.$select;

        if(empty($this->qb_groupby['$group']['_id'])) {
            $this->group_by(null, FALSE);
        }

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

    /**
     * Select Average
     *
     * Generates a SELECT AVG(field) portion of a query
     *
     * @param    string    the field
     * @param    string    an alias
     * @return    CI_DB_query_builder
     */

    public function select_avg($select = '', $alias = '')
    {
        $this->_max_min_avg_sum($select, $alias, 'AVG');
    }

    // --------------------------------------------------------------------

    /**
     * Select Sum
     *
     * Generates a SELECT SUM(field) portion of a query
     *
     * @param    string    the field
     * @param    string    an alias
     * @return    CI_DB_query_builder
     */
    public function select_sum($select = '', $alias = '')
    {
        $this->_max_min_avg_sum($select, $alias, 'SUM');
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
     * "Count All Results" query
     *
     * Generates a platform-specific query string that counts all records
     * returned by an Query Builder query.
     *
     * @param    string
     * @return    int
     */
    public function count_all_results($table = '')
    {
        // query database
        $result = $this->get($table);

        return count($result['result']);
    }

    // --------------------------------------------------------------------

}
/**
 * End of Class Mongo_query_builder
 */