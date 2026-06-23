<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    public function upload_images($data)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('images');
        $builder->insert($data);
        //$query = $this-db->query("SELECT * FROM resources_numeracy ORDER BY id DESC LIMIT 20");
    }
}



/*
class Tpresources extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function load_database()
    { {
            //Query the data table for every record and row
            $DB2   = $this->load->database('default', TRUE);
            $query = $DB2->query("SELECT * FROM resources");
            return $query;
        }
    }

    public function load_test_database()
    { {
            //Query the data table for every record and row
            $DB2   = $this->load->database('default', TRUE);
            $query = $DB2->query("SELECT * FROM test_resources");
            return $query;
        }
    }

    public function get_resources($per_page, $subject)
    {

        $data = array();
        switch ($subject) {
            case 'numeracy':
                $category = 23;
                break;
            case 'English':
                $category = 22;
                break;
            default:
                $category = 0;
                break;
        }

        $query = $this->db->like('category', $category)->get('resources', $per_page, $this->uri->segment(4));

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return false;
    }

    public function get_test_resources($per_page)
    {

        $query = $this->db->select('id, resource_thumb, resource_name, year, resource_excerpt, link')->get('test_resources', $per_page, $this->uri->segment(4));

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return false;
    }

    public function load_latest_resources()
    {
        //Query the data table for every record and row
        $DB2   = $this->load->database('default', TRUE);
        $query = $DB2->query("SELECT * FROM resources ORDER BY id DESC LIMIT 20");
        return $query;
    }

    public function upload_resource($data)
    {
        $this->db->insert('resources', $data);
        //$query = $this-db->query("SELECT * FROM resources_numeracy ORDER BY id DESC LIMIT 20");
    }

    public function upload_test_resource($data)
    {
        $this->db->insert('test_resources', $data);
        //$query = $this-db->query("SELECT * FROM resources_numeracy ORDER BY id DESC LIMIT 20");
    }


    public function delete_resource($data, $id)
    {
        $this->db->insert('deleted_resources', $data);
        $this->db->delete('resources', array(
            'id' => $id
        ));
    }

    public function delete_test_resource($data, $id)
    {
        $this->db->insert('deleted_resources', $data);
        $this->db->delete('test_resources', array(
            'id' => $id
        ));
    }

    function get_random_feature()
    {
        $query = $this->db->query('SELECT * FROM resources ORDER BY RAND() LIMIT 1');
        return $query;
    }



    public function load_keystage()
    { {
            //Query the data table for every record and row
            $query = $this->db->query("SELECT * FROM category WHERE parent_id = 1");
            return $query;
        }
    }



    public function load_subject($arg_keystage = null)
    {
        //Query the data table for every record and row
        if (isset($arg_keystage)) {

            $string = "SELECT * FROM category WHERE parent_id = ?";
            $query  = $this->db->query($string, $arg_keystage);
            return $query;
        }
    }

    //working

    public function load_topics($arg_subject = null)
    {
        if (isset($arg_subject)) {
            //fetch data with parent id of keystage and subject title
            $topic = $this->db->get_where('category', array(
                'parent_id' => $arg_subject
            ));

            return $topic;
        }
    }

    public function search($keyword)
    {
        return $data = array(
            '1' => "Hello",
        );
    }
}*/
