<?php
defined("BASEPATH") OR die("No direct access allowed");

/**
 * 
 */
trait InsertQueryBuilder
{
    
    /**
     * creating data to database
     * @param array $params 
     * @return array|false 
     */
    public function create($params = [])
    {
        // filter value
        $fillable = $this->fillable;
        $params = array_filter($params, function($val, $key) use ($fillable) {
            return in_array($key, $fillable);
        }, ARRAY_FILTER_USE_BOTH);

        // mapping
        $data = array_map(function($val) {
            return "'$val'";
        }, $params);

        // if have timestamps
        if ( $this->timestamps ) {
            $data["created_at"] = "'" . $this->current_time() . "'";
        }

        // make query
        $query = "
            INSERT INTO $this->table
                (" . implode(", ", array_keys($data)) . ")
            VALUES
                (" . implode(", ", array_values($data)) . ")
        ";

        $prepare = $this->db->query($query);

        return $prepare ? $params : false;
    }

}
