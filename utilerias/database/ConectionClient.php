<?php

/* namespace utilerias\database; */



class ConectionClient {

    private $required_parameter_values = Array("name", "value");
    private $required_associate_value = Array("operator");
    private $db_config_parameters = Array("db_name", "db_user", "db_password", "db_server");
    private $associate = Array("columns" => FALSE, "values" => FALSE, "set" => FALSE, "where" => FALSE);
    private $invalid_parameter_chars = Array("\"", ".", "->", ">", "'");

    public function __construct($db_data = NULL) {
        if (!$db_data) {
            $db_prefix = "conexion";
            $direccion = dirname(__FILE__);
            $jsondata = file_get_contents($direccion."/"."config");
            $db_config =JSON_DECODE($jsondata, true);
            $db_data= $db_config[$db_prefix];
            
        }
        $this->setDBConfigByContainerParameter($db_data);
    }

    private function setDBConfigByContainerParameter($db_data = "") {
        $this->conn = Array("db_info" => $db_data, "conn_object" => NULL, "statement_object" => NULL, "query" => Array("string" => "", "parameters" => Array()));
    }

    private function connect() {
        $this->conn["conn_string"] = "mysql:host={$this->conn["db_info"]['db_server']};dbname={$this->conn["db_info"]["db_name"]}";  /* port={$this->conn["db_info"]['db_port']} */
        try {
            $this->conn["conn_object"] = new \PDO($this->conn["conn_string"], $this->conn["db_info"]['db_user'], $this->conn["db_info"]['db_password']);
            return Array("status" => TRUE, "error" => "");
        } catch (\PDOException $e) {
            return Array("status" => FALSE, "error" => $e->getMessage());
        }
    }

    public function setQuery($query = "") {
        if (!is_string($query) || $query == "" || $query == NULL) {
            return Array("status" => FALSE, "error" => "You must to specify a valid Query String");
        }
        $this->conn["query"]["string"] = $query;
        return Array("status" => TRUE, "error" => "");
    }

    public function setQueryParameters(array $parameters = Array()) {

        if ($this->conn["query"]["string"] == "") {
            return Array("status" => FALSE, "error" => "You must to specify a Query String before Set Parameters");
        }

        if (!is_array($parameters)) {
            return Array("status" => FALSE, "error" => "You must to specify a valid Query Parameters Array");
        }

        $this->conn["query"]["parameters"] = Array();

        $this->associate["set"] = strpos($this->conn["query"]["string"], "{set}") ? TRUE : FALSE;
        if ($this->associate["set"]) {
            if (!array_key_exists("set", $parameters) || COUNT($parameters["set"]) == 0) {
                return Array("status" => FALSE, "error" => "You must to especify <b>'set'</b> sub-arrays into parameters AND it needs to have at least one element");
            }

            $this->conn["query"]["parameters"]["set"] = Array();
            foreach ($parameters["set"] as $parameter) {
                foreach ($this->required_parameter_values as $required_value) {
                    if (!array_key_exists($required_value, $parameter)) {
                        return Array("status" => FALSE, "error" => "Update Parameter: $parameter is missing: <b>$required_value</b>");
                    }
                }
                array_push($this->conn["query"]["parameters"]["set"], $parameter);
            }
        } else {
            if (array_key_exists("set", $parameters)) {
                return Array("status" => FALSE, "error" => "You must to specify <b>{set}</b> label inside Query String because a <b>'set'</b> sub-array was specified into parameters");
            }
        }

        $this->associate["where"] = strpos($this->conn["query"]["string"], "{where}") ? TRUE : FALSE;
        if ($this->associate["where"]) {
            $this->conn["query"]["parameters"]["where"] = Array();

            if (!array_key_exists("where", $parameters) || COUNT($parameters["where"]) == 0) {
                //return Array("status" => FALSE, "error" => "You must to especify <b>'where'</b> sub-arrays into parameters AND it needs to have at least one element");
            } else {
                foreach ($parameters["where"] as $parameter) {
                    foreach ($this->required_parameter_values as $required_value) {
                        if (!array_key_exists($required_value, $parameter)) {
                            $parameter_name = array_key_exists("name", $parameter) ? $parameter["name"] : "undefined";
                            return Array("status" => FALSE, "error" => "Condition Parameter <b>$parameter_name</b> is missing: <b>$required_value</b>");
                        }
                    }
                    array_push($this->conn["query"]["parameters"]["where"], $parameter);
                }
            }
        } else {
            if (array_key_exists("where", $parameters)) {
                return Array("status" => FALSE, "error" => "You must to specify <b>{where}</b> label inside the Query String because a <b>'where'</b> sub-array was specified into parameters");
            }
        }

        if (!$this->associate["set"] && !$this->associate["where"]) {

            $this->associate["columns"] = strpos($this->conn["query"]["string"], "{columns}") ? TRUE : FALSE;
            $this->associate["values"] = strpos($this->conn["query"]["string"], "{values}") ? TRUE : FALSE;

            if ($this->associate["columns"] && !$this->associate["values"]) {
                //no {values} specified
                return Array("status" => FALSE, "error" => "You must to specify <b>{values}</b> label inside the Query String because a <b>{columns}</b> was specified");
            } elseif (!$this->associate["columns"] && $this->associate["values"]) {
                //no {columns} specified
                return Array("status" => FALSE, "error" => "You must to specify <b>{columns}</b> label inside the Query String because a <b>{values}</b> was specified");
            } elseif ($this->associate["columns"] && $this->associate["values"]) {
                if (COUNT($parameters) == 0) {
                    return Array("status" => FALSE, "error" => "You must to specify at least one element in the Query Parameters");
                }

                $this->conn["query"]["parameters"]["columns"] = Array();
                $this->conn["query"]["parameters"]["values"] = Array();

                foreach ($parameters as $parameter) {
                    foreach ($this->required_parameter_values as $required_value) {
                        if (!array_key_exists($required_value, $parameter)) {
                            $parameter_name = array_key_exists("name", $parameter) ? $parameter["name"] : "undefined";
                            return Array("status" => FALSE, "error" => "Condition Parameter <b>$parameter_name</b> is missing: <b>$required_value</b>");
                        }
                    }
                    array_push($this->conn["query"]["parameters"]["columns"], trim($parameter["name"]));
                    array_push($this->conn["query"]["parameters"]["values"], $parameter);
                }
            } else {
                foreach ($parameters as $parameter) {
                    foreach ($this->required_parameter_values as $required_value) {
                        if (!array_key_exists($required_value, $parameter)) {
                            $parameter_name = array_key_exists("name", $parameter) ? $parameter["name"] : "undefined";
                            return Array("status" => FALSE, "error" => "Condition Parameter <b>$parameter_name</b> is missing: <b>$required_value</b>");
                        }
                    }
                    array_push($this->conn["query"]["parameters"], $parameter);
                }
            }
        }
        return Array("status" => TRUE, "error");
    }

    public function execQueryString($query = "", array $parameters = Array()) {
        $result_set_query = $this->setQuery($query);
        if (!$result_set_query["status"]) {
            return $result_set_query;
        }

        $result_set = $this->setQueryParameters($parameters);
        if (!$result_set["status"]) {
            return $result_set;
        }

        return $this->exec();
    }

    public function exec() {
        $result_connect = $this->connect();
        if (!$result_connect["status"]) {
            return $this->errorResponse(Array("message" => $result_connect["error"]));
        }

        $result_prepare = $this->prepareQuery();
        if (!$result_prepare["status"]) {
            return $this->errorResponse($result_prepare["error"]);
        }

        $attempt = 0;
        query_execution:
        $result_execute = $this->conn["statement_object"]->execute();
        if ($result_execute == FALSE) {
            if (++$attempt < 3) {
                sleep(200 / 1000 /* ms */);
                goto query_execution;
            }
            $array_error = $this->conn["statement_object"]->errorInfo();

            if (isset($array_error[2])) {
                $error_tmp = explode("\n", $array_error[2]);
                if (isset($error_tmp[1]) && strpos($error_tmp[1], "LINE 1") >= 0) {
                    $array_error[2] = $error_tmp[0];
                }
            }

            $error = Array("string" => "Internal Error while trying to execute Query");
            if (is_array($array_error) && COUNT($array_error) > 0) {
                $error = Array(
                    "string" => "SQLSTATE error code -- " . $array_error[0] . " <br>Driver-specific error code -- " . $array_error[1] . " <br>Driver-specific error message -- " . $array_error[2],
                    "message" => $array_error[2],
                    "sqlstate_code" => $array_error[0],
                    "driver_code" => $array_error[1]
                );
            }
            return $this->errorResponse($error);
        }

        $result_data = $this->conn["statement_object"]->fetchAll(\PDO::FETCH_ASSOC);
        return $this->successResponse($result_data);
    }

    private function successResponse($data = "") {
        if (!is_array($data)) {
            throw new \Exception("You must to specify a valid data array to return the MySQL Response");
        }
        $result = Array("status" => TRUE, "query" => "", "data" => $data, "error" => "");
        if ($this->conn["query"]["string"] != "") {
            $result["query"] = $this->conn["query"]["string"];
        }
        if ($this->conn["conn_object"] != NULL) {
            $this->closeConn();
        }
        unset($result['query']);
        unset($result['error']);
        return $result;
    }

    private function prepareQuery() {
        if ($this->conn["query"]["string"] == "") {
            return Array("status" => FALSE, "error" => "Query String is empty");
        }

        if ($this->associate["set"] || $this->associate["where"] || $this->associate["columns"] || $this->associate["values"]) {
            $result_associate = $this->associateParameters();
            if (!$result_associate["status"]) {
                return $result_associate;
            }
        }

        if (strpos($this->conn["query"]["string"], "{schema}") != FALSE && is_string($this->conn["db_info"]["db_schema"]) && $this->conn["db_info"]["db_schema"] != "") {
            $this->conn["query"]["string"] = str_replace("{schema}", '"' . $this->conn["db_info"]["db_schema"] . '"', $this->conn["query"]["string"]);
        }

        $this->conn["statement_object"] = $this->conn["conn_object"]->prepare($this->conn["query"]["string"]);
        if (!$this->conn["statement_object"]) {
            $array_error = $this->conn["conn_object"]->errorInfo();
            $error = Array("string" => "Internal Error while trying to execute Query");
            if (is_array($array_error) && COUNT($array_error) > 0) {
                $error = Array(
                    "string" => "SQLSTATE error code -- " . $array_error[0] . " <br>Driver-specific error code -- " . $array_error[1] . " <br>Driver-specific error message -- " . $array_error[2],
                    "message" => $array_error[2],
                    "sqlstate_code" => $array_error[0],
                    "driver_code" => $array_error[1]
                );
            }
            return Array("status" => FALSE, "error" => $error);
        }

        if (COUNT($this->conn["query"]["parameters"]) > 0) {
            $result_bind = $this->setBindParameters();
            if (!$result_bind["status"]) {
                return $result_bind;
            }
        }
        return Array("status" => TRUE, "error" => "");
    }

    private function setBindParameters() {
        if ($this->associate["set"]) {
            foreach ($this->conn["query"]["parameters"]["set"] as $parameter) {
                $result_bind = $this->bindParameter($parameter);
                if (!$result_bind["status"]) {
                    return $result_bind;
                }
            }
        }

        if ($this->associate["where"]) {
            if (COUNT($this->conn["query"]["parameters"]["where"]) > 0) {
                foreach ($this->conn["query"]["parameters"]["where"] as $parameter) {
                    $result_bind = $this->bindParameter($parameter);
                    if (!$result_bind["status"]) {
                        return $result_bind;
                    }
                }
            }
        }

        if (!$this->associate["set"] && !$this->associate["where"]) {
            if ($this->associate["columns"] && $this->associate["values"]) {
                foreach ($this->conn["query"]["parameters"]["values"] as $parameter) {
                    $result_bind = $this->bindParameter($parameter);
                    if (!$result_bind["status"]) {
                        return $result_bind;
                    }
                }
            } else {
                foreach ($this->conn["query"]["parameters"] as $parameter) {
                    $result_bind = $this->bindParameter($parameter);
                    if (!$result_bind["status"]) {
                        return $result_bind;
                    }
                }
            }
        }
        return Array("status" => TRUE, "error" => "");
    }

    private function errorResponse(array $error = Array()) {
        if (!is_array($error) || COUNT($error) == 0) {
            throw new \Exception("You must to specify a valid error to return the Postgres Response");
        }
        $result = Array("status" => FALSE, "query" => "", "data" => (isset($error['message'])) ? $error['message'] : Array(), "error" => $error);
        if ($this->conn["query"]["string"] != "") {
            $result["query"] = $this->conn["query"]["string"];
        }
        if ($this->conn["conn_object"] != NULL) {
            $this->closeConn();
        }
        if (!$result['status']) {
            $error_code = "Undefined";
            if (isset($result['error']) && isset($result['error']['sqlstate_code'])) {
                $error_code = $result['error']['sqlstate_code'];
            }
            $error_desc = "Query Error";
            if (isset($result['query'])) {
                $error_desc = $result['query'];
            }
            print_r($result['data']);
            print_r('<br>');
            print_r($error_desc);
            print_r('<br>');
            print_r($error_code);
            die();
        }
        unset($result['query']);
        unset($result['error']);
        return $result;
    }

    private function closeConn() {
        $this->conn["query"] = Array("string" => "", "parameters" => Array());
        $this->conn["conn_object"] = NULL;
        $this->conn["statement_object"] = NULL;
        $this->associate = Array("columns" => FALSE, "values" => FALSE, "set" => FALSE, "where" => FALSE);
    }

}

?>
