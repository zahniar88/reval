<?php
defined("BASEPATH") OR die("No direct access allowed");

require_once __DIR__ . "/../core/Database/InsertQuery.php";
require_once __DIR__ . "/../core/Database/SelectQuery.php";
require_once __DIR__ . "/../core/Database/MergeWithQuery.php";
require_once __DIR__ . "/../core/Database/JoinQuery.php";

class Entity extends CI_Model {

    use InsertQuery, SelectQuery, MergeWithQuery, JoinQuery;

    /**
     * define default table name
     * @var string
     */
    protected $table = "";

    /**
     * define default cols name
     * @var array
     */
    protected $cols = ["*"];

    /**
     * define column can insert or update
     * @var array
     */
    protected $fillable = [];

    /**
     * define default timestamp
     * @var true
     */
    protected $timestamps = true;

    /**
     * for generating query
     * @var string
     */
    protected $selectRaw = "",
                $where = "",
                $join = "",
                $orderBy = "",
                $groupBy = "",
                $limit = "";

    /**
     * get default time
     * @return string 
     */
    public function current_time()
    {
        return date("Y-m-d H:i:s");
    }

}