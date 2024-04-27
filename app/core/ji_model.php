<?php

/**
 * Define all general method for all tables
 *   For class models
 *
 * @package Core\Model
 * @since 1.0
 */
class JI_Model extends \SENE_Model
{
    /** @var string  */
    public $tbl;

    /** @var string  */
    public $tbl_as;

    /** @var array  */
    public $labels;

    /** @var array  */
    public $columns;

    /** @var array  */
    public $datatables;

    /** @var array  */
    public $point_of_view;

    /** @var string  */
    public $validation_message;

    public function __construct()
    {
        parent::__construct();
        $this->validation_message = '';
        date_default_timezone_set('Asia/Jakarta');
    }

    /**
     * Generates validation message
     *
     * @param  string  $validation_item               Validation item name
     *
     * @return mixed                     Current validation string or current class object
     */
    public function validation_message($validation_item = '')
    {
        if (strlen($validation_item)) {
            if (strlen($this->validation_message) == 0) {
                $this->validation_message = 'Missing required column(s): ';
            }
            $this->validation_message .= $validation_item . ', ';

            return $this;
        } else {
            return rtrim($this->validation_message, ', ');
        }
    }

    /**
     * Generates data for inserting into database table
     *
     * @return array generic array contains key as column name with the value
     */
    public function data_parameters()
    {
        $data_parameters = array();
        foreach ($this->columns as $key => $val) {
            $data_parameters[$key] = $val->value;
        }
        return $data_parameters;
    }

    /**
     * Insert a row data
     *
     * @param  array   $d   Contain associative array that represent the pair of column and value
     * @return int          Return last ID if succeed, otherwise will return 0
     */
    public function set($d)
    {
        $this->db->insert($this->tbl, $d, 0, 0);
        return $this->db->last_id;
    }

    /**
     * get last id after insert executed
     *
     * @return int          Return last ID if succeed, otherwise will return 0
     */
    public function last_id()
    {
        return $this->db->last_id;
    }

    /**
     * Update a row data by supplied ID
     *
     * @param  int      $id    Positive integer
     * @return boolean         True if succeed, otherwise false
     */
    public function update($id, $d)
    {
        $this->db->where("is_deleted", 1, "AND", "<>");
        $this->db->where('id', $id);
        return $this->db->update($this->tbl, $d, 0);
    }

    /**
     * Delete row data by ID
     *
     * @param  int      $id    Positive integer
     * @return boolean         True if succeed, otherwise false
     */
    public function del($id)
    {
        $this->db->where("id", $id);
        return $this->db->update($this->tbl, [
            "is_deleted" => 1,
            "deleted_at" => date("Y-m-d H:i:s"),
        ]);
    }

    /**
     * Get single row data by ID
     *
     * @param  int      $id     Positive integer
     * @return stdClass         Will return single row object, otherwise will return empty object
     */
    public function id($id)
    {
        $this->db->where("is_deleted", 1, "AND", "<>");
        $this->db->where("id", $id);
        return $this->db->from($this->tbl, $this->tbl_as)->get_first('', 0);
    }

    /**
     * Open the database transaction session
     * @return boolean True if succeed, otherwise false
     */
    public function trans_start()
    {
        $r = $this->db->autocommit(0);
        if ($r) {
            return $this->db->begin();
        }
        return false;
    }

    /**
     * Execute `commit` SQL command
     * @return boolean True if succeed, otherwise false
     */
    public function trans_commit()
    {
        return $this->db->commit();
    }

    /**
     * Rollback the database transaction session
     * @return boolean True if succeed, otherwise false
     */
    public function trans_rollback()
    {
        return $this->db->rollback();
    }

    /**
     * Finalize the database transaction session
     * @return boolean True if succeed, otherwise false
     */
    public function trans_end()
    {
        return $this->db->autocommit(1);
    }

    /**
     * List all table columns
     *   If $key provided, it will show current key if exists
     *   Otherwise will produce stdClass / empty class
     *
     * @param string $key    key or name of column
     *
     * @return array         Current defined columns in array
     */
    public function columns($key = '')
    {
        if (strlen($key) > 0) {
            if (isset($this->columns[$key])) {
                return $this->columns[$key];
            } else {
                return new stdClass();
            }
        }
        return $this->columns;
    }

    /**
     * Define a column default value into a table by a key / name of column
     *
     * @param string  $column_name   key or name of column
     * @param mixed   $value         Current default value
     *
     * @return array                 Current defined columns in array
     */
    public function default_value($column_name = '', $value = '')
    {
        if (is_string($column_name) && strlen($column_name) > 0) {
            if (!isset($this->columns[$column_name])) {
                trigger_error('$column_name = ' . $column_name . ' undefined!');
                return;
            }
            $this->columns[$column_name]->default = $value;
        }
        return $this->columns;
    }

    /**
     * Define columns default value into a table by set of array values
     *   If supplied make sure the array length are equal with defined columns
     *
     * @param array   $column_name   key or name of column
     * @param mixed   $value         Current default value
     *
     * @return array                 Current defined columns in array
     */
    public function default_values($column_names = array())
    {
        if (!is_array($column_names)) {
            trigger_error('$column_names is not an array!');
            return;
        }

        $c = count($column_names);
        if ($c > 0) {
            if ($c != count($this->columns)) {
                trigger_error('$column_names array length not matched with defined columns!');
                return;
            }
            $i = 0;
            foreach ($this->columns as $key => $value) {
                $this->columns[$key]->default = isset($column_names[$i]) ? $column_names[$i] : '';
                $i++;
            }
        }

        return $this->columns;
    }

    public function defined_input($method = 'post')
    {
        switch (strtolower($method)) {
            case 'get':
                $di = $_GET;
            case 'request':
                $di = $_REQUEST;
            default:
                $di = $_POST;
        }

        $defined_input = array();
        foreach ($this->columns as $key => $value) {
            $defined_input[$key] = '';
            if (isset($defined_input[$key])) {
                $defined_input[$key] = $di[$key];
            }
        }

        return $defined_input;
    }

    /**
     * Validates the current input by method name
     *
     * @param  string   $method               Can be post, get, or request. Default: post
     *
     * @return boolean           True if valid, false if not valid
     */
    public function validates($method = 'post')
    {
        $result = true;
        switch (strtolower($method)) {
            case 'get':
                $di = $_GET;
            case 'request':
                $di = $_REQUEST;
            default:
                $di = $_POST;
        }

        foreach ($this->columns as $key => $value) {
            if ($value->required && !isset($di[$key])) {
                $result = false;
                $this->validation_message($key);
            } else if ($value->required && isset($di[$key]) && $value->is_enum()) {
                $result = $value->validate_enum($di[$key]);
                if (!$result) {
                    $this->validation_message($key);
                }
            } else if (!$value->required && !isset($di[$key])) {
                $this->columns[$key]->value = $this->columns[$key]->default;
            } else {
                $this->columns[$key]->value = $di[$key];
            }
        }
        return $result;
    }

    /**
     * Execute insert or update operation after run \JI_Model::validates method into particular table
     *
     * @return int Last inserted ID or 1 for edit operation, otherwise return 0
     */
    public function save($id = 0)
    {
        if (is_int($id) && $id > 0) {
            return $this->update($id, $this->data_parameters());
        } else {
            $this->db->insert($this->tbl, $this->data_parameters(), 0, 0);
            return $this->db->last_id;
        }
    }

    /**
     * Global scoped procedure(s) for all of table when marked as deleted
     *
     * @return JI_Model this class
     */
    public function scoped()
    {
        $this->db->where_as("$this->tbl_as.is_deleted", $this->db->esc('0'));
        return $this;
    }

    /**
     * Global scoped procedure(s) for all of table when is active and not deleted
     *
     * @return JI_Model this class
     */
    public function active()
    {
        $this->db->where_as("$this->tbl_as.is_deleted", $this->db->esc('0'));
        $this->db->where_as("$this->tbl_as.is_active", $this->db->esc('1'));
        return $this;
    }

    /**
     * Get rendered HTML for labelled column
     *
     * @param string $column_name name of defined column label
     * @param mixed  $value       value for current defined column
     *
     * @return string formatted string label in html
     */
    public function label($column_name, $value)
    {
        return $this->labels[$column_name]->html($value);
    }


    /**
     * Encrypt the string by executing AES_ENCRYPT SQL command
     *
     * @param  string $s plain string
     *
     * @return string      encrypt command
     */
    public function encrypt($s)
    {
        return 'AES_ENCRYPT(' . $this->db->esc($s) . ',"' . $this->config->database->enckey . '")';
    }

    /**
     * Decrypt the string by executing AES_DECRYPT SQL command
     *
     * @param  string $s decrypted string
     *
     * @return string      decrypt command
     */
    public function decrypt($s)
    {
        return 'AES_DECRYPT(' . $s . ',"' . $this->config->database->enckey . '")';
    }

    /**
     * Validate sent data before continue to next process
     *
     * @param  array $data Data to validate
     * @param  object $context Controller class name where you call this method
     * @param  string $purpose Your purpose to use this method (read, update, insert, delete)?
     * @param  array $rules the rules used to validate $data
     */

    public function validate(
        $data,
        $purpose,
        $rules
    ) {
        $model = $this;
        $dataToReturn = array();
        foreach ($rules as $key => $value) {
            foreach ($value as $rule) {
                $tmp = explode(':', $rule);
                $keyAs = $key;
                foreach ($value as $as) {
                    $ruleAs = explode(":", $as);
                    if ($ruleAs[0] === "as") {
                        if (count($ruleAs) >= 2) {
                            $keyAs = $ruleAs[1];
                        }
                    }
                }
                if ($tmp[0] === 'required') {
                    $space = 0;
                    if (isset($data[$key])) {
                        $param = $data[$key];
                        foreach (str_split((string)$param) as $u) {
                            if ($u == ' ') $space++;
                        }
                        if (strlen((string)$param) === 0 or strlen((string)$param) === $space) {
                            return [
                                "result" => false,
                                "message" => '"' . $keyAs . '" wajib diisi'
                            ];
                            die;
                        }
                    } else {
                        return [
                            "result" => false,
                            "message" => '"' . $keyAs . '" wajib diisi'
                        ];
                        die;
                    }
                }

                if (isset($data[$key])) {
                    if ($tmp[0] === 'max') {
                        if (strlen((string)$param) > $tmp[1]) {
                            return [
                                "result" => false,
                                "message" => 'Panjang karakter "' . $keyAs . '" tidak boleh lebih dari ' . $tmp[1]
                            ];
                            die;
                        }
                    }
                    if ($tmp[0] === 'min') {
                        if (strlen((string)$param) < $tmp[1]) {
                            return [
                                "result" => false,
                                "message" => 'Panjang karakter "' . $keyAs . '" tidak boleh kurang dari ' . $tmp[1]
                            ];
                            die;
                        }
                    }
                    if ($tmp[0] === 'email') {
                        $is_invalid = false;
                        $separate_email = explode('@', $param);
                        if (count($separate_email) !== 2) {
                            $is_invalid = true;
                        } else {
                            $domain = explode('.', $separate_email[1]);
                            if (count($domain) < 2) {
                                $is_invalid = true;
                            } else {
                                if (strlen(end($domain)) < 2) {
                                    $is_invalid = true;
                                }
                            }
                        }
                        if ($is_invalid) {
                            return [
                                "result" => false,
                                "message" => '"' . $keyAs . '" bukan email atau tidak valid'
                            ];
                            die;
                        }
                    }
                    if ($tmp[0] === 'unique') {
                        $sql = '';
                        if ($purpose === 'update') {
                            $sql = "select count(*) as count from {$model->tbl} where $key='$param' and id!='$data[id]'";
                            $model->db->select_as("count(*)", "count");
                            $model->db->where($key, $param, 'AND', '=');
                            $model->db->where("id", $data['id'], 'AND', '!=');
                        } else {
                            $sql = "select count(*) as count from {$model->tbl} where $key='$param'";
                            $model->db->select_as("count(*)", "count");
                            $model->db->where($key, $param);
                        }
                        $checkTbl = (int) $model->db->get()[0]->count;
                        if ($checkTbl > 0) {
                            return [
                                "result" => false,
                                "message" => '"' . $key . '" tidak boleh sama dengan "' . $keyAs . '" yang lain'
                            ];
                            die;
                        }
                    }
                }
            }
            if (isset($data[$key])) $dataToReturn[$key] = $data[$key];
        }
        return [
            "result" => true,
            "data" => $dataToReturn
        ];
    }
}
