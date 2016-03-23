<?php

/**
 * Created by PhpStorm.
 * User: Mahedi Azad
 * Date: 23-Mar-16
 * Time: 03:21 PM
 */
class Users_data  extends CI_Model
{
    public function __construct()
    {

        parent::__construct();
        $this->load->database();

    }

    public function get_users_data()
    {
       /* $this->db->select("*");
        $this->db->from('users');
        return $this->db->get()->row();*/

        $sql = "SELECT * FROM users";
        $query = $this->db->query($sql);
        return $query->result_array();

    }
}