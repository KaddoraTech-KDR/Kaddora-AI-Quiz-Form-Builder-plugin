<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('KAQF_Database')) {

  class KAQF_Database
  {
    private $wpdb;

    public function __construct()
    {
      global $wpdb;
      $this->wpdb = $wpdb;
    }

    /**
     * INSERT
     */
    public function insert($table, $data)
    {
      $result = $this->wpdb->insert($this->table($table), $data);

      if ($result === false) {
        KAQF_Helper::log('DB Insert Error: ' . $this->wpdb->last_error, 'error');
      }

      return $this->wpdb->insert_id;
    }

    /**
     * UPDATE
     */
    public function update($table, $data, $where)
    {
      $result = $this->wpdb->update($this->table($table), $data, $where);

      if ($result === false) {
        KAQF_Helper::log('DB Update Error: ' . $this->wpdb->last_error, 'error');
      }

      return $result;
    }

    /**
     * DELETE
     */
    public function delete($table, $where)
    {
      $result = $this->wpdb->delete($this->table($table), $where);

      if ($result === false) {
        KAQF_Helper::log('DB Delete Error: ' . $this->wpdb->last_error, 'error');
      }

      return $result;
    }

    /**
     * GET ROW
     */
    public function get_row($query, $params = [])
    {
      $sql = $this->prepare($query, $params);
      return $this->wpdb->get_row($sql, ARRAY_A);
    }

    /**
     * GET RESULTS
     */
    public function get_results($query, $params = [])
    {
      $sql = $this->prepare($query, $params);
      return $this->wpdb->get_results($sql, ARRAY_A);
    }

    /**
     * PREPARE QUERY
     */
    private function prepare($query, $params)
    {
      if (!empty($params)) {
        return $this->wpdb->prepare($query, $params);
      }
      return $query;
    }

    /**
     * TABLE PREFIX HANDLER
     */
    private function table($name)
    {
      return $this->wpdb->prefix . 'kaqf_' . $name;
    }
  }
}
