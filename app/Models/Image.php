<?php

namespace App\Models;

use CodeIgniter\Model;

class Image extends Model
{

    function __construct()
    {
        $this->table = 'images';
    }

    /* 
     * Returns rows from the database based on the conditions 
     * @param array filter data based on the passed parameters 
     */
    public function getRows($params = array())
    {
        $db = db_connect();
        $builder = $db->table('images');
        $builder->select('*');
        $query = $builder->get();
        $rowArray = $query->getResultArray();
        return $rowArray;

        if (array_key_exists("where", $params)) {
            foreach ($params['where'] as $key => $val) {
                $builder->getWhere($key, $val);
            }
        }

        if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
            $result = $this->db->count_all_results();
        } else {
            if (array_key_exists("id", $params)) {
                $builder->getWhere('id', $params['id']);
                $query = $builder->get();
                $result = $query->getRowArray();
            } else {
                $builder->orderBy('created', 'desc');
                if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->db->limit($params['limit'], $params['start']);
                } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->db->limit($params['limit']);
                }

                $query = $builder->get();
                $result = ($query->getFieldCount() > 0) ? $query->getResultArray() : FALSE;
            }
        }

        // Return fetched data 
        return $result;
    }

    public function view($id)
    {
        $db = db_connect();
        $builder = $db->table('images');
        $query = $builder->getWhere(['id' => $id]);
        $result = $query->getRow();

        return $result;
    }

    public function upload_images($data)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('images');
        $builder->insert($data);
        //$query = $this-db->query("SELECT * FROM resources_numeracy ORDER BY id DESC LIMIT 20");
    }

    /* 
     * Insert image data into the database 
     * @param $data data to be insert based on the passed parameters 
     */
    /*public function insert($data = array())
    {
        if (!empty($data)) {
            // Add created and modified date if not included 
            if (!array_key_exists("created", $data)) {
                $data['created'] = date("Y-m-d H:i:s");
            }
            if (!array_key_exists("modified", $data)) {
                $data['modified'] = date("Y-m-d H:i:s");
            }

            // Insert member data 
            $insert = $this->db->insert($this->table, $data);

            // Return the status 
            return $insert ? $this->db->insert_id() : false;
        }
        return false;
    }

    /* 
     * Update image data into the database 
     * @param $data array to be update based on the passed parameters 
     * @param $id num filter data 
     */
    public function update_image($data, $id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('images');
        $row = $builder->where(['id' => $id]);
        $result = $row->update($data);
        return $result;
    }

    /* 
     * Delete image data from the database 
     * @param num filter data based on the passed parameter 
     */
    public function image_delete($id)
    {
        // Delete member data 
        $db = db_connect();
        $builder = $db->table('images');
        $query = $builder->getWhere(['id' => $id]);
        $row = $query->getRow();
        $link = $row->link;

        $query = $builder->where('id', $id)->delete();

        // Return the status 

        return $link;
    }
}
