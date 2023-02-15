<?php
require_once 'ConectionClient.php';

class SQLModel {

    protected $ConectionClient;

    public function __construct($db_data = NULL) {
        $this->ConectionClient = new ConectionClient();
    }

    public function executeQuery($qry) {
        
        return $this->ConectionClient->execQueryString($qry);
    }

    public function selectFromTable($table, $fields = array(), $where = array(), $order = array()) {
        $qry = 'SELECT';
        $qry .= $this->buildSelectFields($fields);
        $qry .= ' FROM';
        $qry .= ' `' . $table . '` ';
        $qry .= $this->buildWhere($where);
        $qry .= $this->buildOrderBy($order);
        return $this->ConectionClient->execQueryString($qry);
    }

    public function insertIntoTable($table, $values) {
        $cols = $this->getColumnsValues($values);
        $qry = ' INSERT INTO  ' . $table;
        $qry .= ' (' . $cols['columns'] . ') ';
        $qry .= ' VALUES(' . $cols['values'] . ')';
        return $this->ConectionClient->execQueryString($qry);
    }

    public function updateFromTable($table, $values, $where = array()) {
        $qry = 'UPDATE `' . $table . '` SET ';
        $qry .= $this->getValuesToUpdate($values);
        $qry .= $this->buildWhere($where);
        return $this->ConectionClient->execQueryString($qry);
    }

    public function deleteFromTable($table, $where = array()) {
        $qry = 'DELETE FROM `' . $table . '` ';
        $qry .= $this->buildWhere($where);
        return $this->ConectionClient->execQueryString($qry);
    }

    private function buildSelectFields($fields) {
        if (!(is_array($fields) && count($fields) > 0)) {
            return " * ";
        }

        $qry = " ";

        foreach ($fields as $key => $value) {
            if (substr($value, 0, 1) == "'" && substr($value, -1) == "'") {
                $value = "'" . substr($value, 1, -1) . "'";
            }
            $qry .= '`' . $value . '`';
            if (next($fields)) {
                $qry .= ', ';
            }
        }

        return $qry;
    }

    public function buildWhere($where) {
        if (!(is_array($where) && count($where) > 0)) {
            return "";
        }

        $qry = " WHERE";
        $qry .= $this->buildParameters($where);
        return $qry;
    }

    public function buildOrderBy($order) {
        if (!(is_array($order) && count($order) > 0)) {
            return "";
        }

        $qry = " ORDER BY";
        $qry .= $this->buildParametersOrderBy($order);
        return $qry;
    }

    private function buildParametersOrderBy($param) {
        if (!(is_array($param) && count($param) > 0)) {
            return "";
        }

        $qry_param = " ";
        foreach ($param as $key => $value) {
            $qry_param .= '"' . str_replace('"', '', $key) . '"';
            if (substr($value, 0, 1) == "'" && substr($value, -1) == "'") {
                $value = "'" . substr($value, 1, -1) . "'";
            }
            $qry_param .= ($value == "") ? " IS NULL " : " " . $value;
            if (next($param)) {
                $qry_param .= ', ';
            }
        }

        return $qry_param;
    }

    private function buildParameters($param) {
        if (!(is_array($param) && count($param) > 0)) {
            return "";
        }

        $qry_param = " ";
        foreach ($param as $key => $value) {
            /* Si tiene parentesis el key, lo dejamos como viene */
            if (strpos($key, "(") && strpos($key, ")") || strpos($key, ".")) {
                $qry_param .= $key;
            } else {
                $qry_param .= '`' . str_replace('`', '', $key) . '`';
            }

            /* Si el valor tiene operadores relacionales, construimos la condicion */
            $operator = "=";
            if ((is_array($value) && count($value) > 0)) {
                $operator = $value['operator'];
                $value = $value['value'];
            }

            if (substr($value, 0, 1) == "'" && substr($value, -1) == "'") {
                $value = "'" . substr($value, 1, -1) . "'";
            }
            $qry_param .= ($value == "") ? " IS NULL " : $operator . $value;

            if (next($param)) {
                $qry_param .= ' AND ';
            }
        }
        return $qry_param;
    }

    private function getColumnsValues($columns = array()) {
        if (!(is_array($columns) && count($columns) > 0)) {
            return array("columns" => "", "values" => "");
        }

        $cols = "";
        $vals = "";
        foreach ($columns as $key => $value) {
            if ($value != "") {
                $cols .= '`' . $key . '`, ';
                $vals .= "" . $value . ", ";
            }
        }
        $result = array(
            "columns" => substr($cols, 0, (strlen($cols) - 2)),
            "values" => substr($vals, 0, (strlen($vals) - 2))
        );
        return $result;
    }

    private function getValuesToUpdate($columns = array()) {
        if (!(is_array($columns) && count($columns) > 0)) {
            return "";
        }

        $vals = "";
        foreach ($columns as $key => $value) {
            $value = (is_numeric($value)) ? $value : "'" . $value . "'";
            $value = (trim($value) != "") ? trim($value) : "null";
            $vals .= " `" . $key . "` = " . $value . ", ";
        }
        return substr($vals, 0, (strlen($vals) - 2));
    }

}
