<?php

namespace App\Models;

use CodeIgniter\Model;

class Search extends Model
{
    protected $table = 'resources';


    /* public function search($term)
    {
        $pager = \Config\Services::pager();
        $db = db_connect();
        $builder = $db->table('resources');
        $query = $builder->where('resource_description');
        $search = $query->like($term);
        $paged = $search->paginate(5);
        return $paged;
    }*/
}
