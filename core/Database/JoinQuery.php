<?php
defined("BASEPATH") OR die("No direct access allowed");

/**
 * 
 */
trait JoinQuery
{

    protected $childJoinCols = [];
    protected $parentJoinCols = [];
    
    /**
     * joining table
     * @param mixed $destinations 
     * @param string $primary_key 
     * @param mixed|null $foreign_key 
     * @param string $mode 
     * @return $this 
     */
    public function join($destinations, $primary_key = "id", $foreign_key = null, $mode = "")
    {
        $extract = explode(":", $destinations);
        $table = $extract[0];
        $alias = $extract[1] ?? $table;

        $foreign_key = isset($foreign_key) ? $foreign_key : $this->table . "_" . $primary_key;

        $this->childJoinCols = array_merge($this->childJoinCols, $this->getChildColumns($table, $foreign_key, $alias));
        $this->join .= "$mode JOIN $table " . ($alias != $table ? $alias : "") . " ON $this->table.$primary_key = $alias.$foreign_key\n";

        return $this;
    }

    /**
     * get columns parent
     * @return array 
     */
    public function getParentColumns()
    {
        $table = $this->table;
        $query = "SHOW COLUMNS FROM $table";
        $prepare = $this->db->query($query);

        $hidden = $this->hidden;
        $columns = array_filter(array_column($prepare->result_array(), "Field"), function($key) use ($hidden) {
            return !in_array($key, $hidden);
        }, ARRAY_FILTER_USE_BOTH);

        $columns = array_map(function ($cols) use ($table) {
            return "$table.$cols";
        }, $columns);

        return $columns;
    }

    /**
     * get columns name
     * @param mixed $table 
     * @return array 
     */
    protected function getChildColumns($table, $foreign_key, $alias)
    {
        $query = "SHOW COLUMNS FROM $table";
        $prepare = $this->db->query($query);

        $columns = array_filter(array_column($prepare->result_array(), "Field"), function($key) use ($foreign_key) {
            return $key != $foreign_key;
        }, ARRAY_FILTER_USE_BOTH);

        $columns = array_map(function ($cols) use ($alias) {
            return "$alias.$cols AS $alias" . "_" . "$cols";
        }, $columns);

        return $columns;
    }

}
