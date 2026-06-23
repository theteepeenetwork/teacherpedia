<?php

namespace App\Models;

use CodeIgniter\Model;

class Image extends Model
{
    protected $table      = 'images';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'file_name',
        'alt',
        'created',
        'html_link',
        'link',
        'status',
    ];

    /*
     * Returns rows from the database based on the conditions
     * @param array filter data based on the passed parameters
     */
    public function getRows($params = array())
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('images');
        $builder->select('*');

        if (array_key_exists('where', $params)) {
            foreach ($params['where'] as $key => $val) {
                $builder->where($key, $val);
            }
        }

        if (array_key_exists('returnType', $params) && $params['returnType'] == 'count') {
            return $builder->countAllResults();
        }

        if (array_key_exists('id', $params)) {
            $query = $builder->where('id', $params['id'])->get();

            return $query->getRowArray();
        }

        $builder->orderBy('created', 'desc');
        if (array_key_exists('start', $params) && array_key_exists('limit', $params)) {
            $builder->limit($params['limit'], $params['start']);
        } elseif (! array_key_exists('start', $params) && array_key_exists('limit', $params)) {
            $builder->limit($params['limit']);
        }

        $query = $builder->get();

        return $query->getResultArray();
    }

    public function view($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('images');
        $query   = $builder->getWhere(['id' => $id]);

        return $query->getRow();
    }

    public function upload_images($data)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('images');

        return $builder->insert($data);
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

        return $builder->where('id', $id)->update($data);
    }

    /*
     * Delete image data from the database
     * @param num filter data based on the passed parameter
     * @return string the link of the deleted row (so the file can be unlinked)
     */
    public function image_delete($id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('images');

        $row  = $builder->getWhere(['id' => $id])->getRow();
        $link = $row !== null ? $row->link : '';

        $builder->where('id', $id)->delete();

        return $link;
    }
}
