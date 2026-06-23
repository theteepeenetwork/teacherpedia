<?php

namespace App\Models;

use CodeIgniter\Model;

class ResourcesModel extends Model
{
    protected $table = 'resources';
    protected $primaryKey = 'id';

    /**
     * Canonical home for generated resource code.
     * Stored DB links are relative to app/Views, e.g.
     * "resources_generated/2025/07-01/17-02-09".
     */
    public const VIEW_BASE = 'resources_generated';

    /**
     * Normalise a stored resource "link" to a view path under app/Views.
     *
     * New resources are stored under "resources_generated/...". Legacy rows
     * were stored under "resources/..."; rewrite that prefix so the app keeps
     * working whether or not the DB migration has been run yet.
     */
    public static function viewBase(?string $link): string
    {
        $link = trim((string) $link, '/');

        if ($link === '' || str_starts_with($link, self::VIEW_BASE . '/')) {
            return $link;
        }

        if (str_starts_with($link, 'resources/')) {
            return self::VIEW_BASE . '/' . substr($link, strlen('resources/'));
        }

        return $link;
    }

    /**
     * Insert a keyword or, if it already exists, bump its usage count.
     * Returns the keyword id.
     */
    public function upsert_keyword(string $word): int
    {
        $word = strtolower(trim($word));
        if ($word === '') {
            return 0;
        }

        $builder  = $this->db->table('keywords');
        $existing = $builder->where('word', $word)->get()->getRow();

        if ($existing !== null) {
            $this->db->table('keywords')
                ->where('word', $word)
                ->set('count', 'count + 1', false)
                ->update();

            return (int) $existing->id;
        }

        $this->db->table('keywords')->insert(['word' => $word, 'count' => 1]);

        return (int) $this->db->insertID();
    }

    /**
     * Archive a resource into deleted_resources and remove it from the live
     * resources table (which removes it from the website).
     */
    public function delete_resource(array $archive, $id)
    {
        $this->db->table('deleted_resources')->insert($archive);

        return $this->db->table('resources')->delete(['id' => $id]);
    }


    public function load_latest_resources($slug = false)
    {
        $db = db_connect();
        $builder = $db->table('resources');
        $query = $builder->get(10);
        $array = $query->getResult();

        return $array;
    }

    public function get_random_feature()
    {
        $db = db_connect();
        $query = $db->query('SELECT * FROM resources ORDER BY RAND() LIMIT 1');
        $array = $query->getRow();

        return $array;
    }

    public function load_resource($slug)
    {
        $db = db_connect();
        $builder = $db->table('resources');
        $builder = $builder->getWhere(['slug' => $slug]);
        $array = $builder->getRow();

        return $array;
    }
    public function edit_resource($id)
    {
        $db = db_connect();
        $builder = $db->table('resources');
        $builder = $builder->getWhere(['id' => $id]);
        $array = $builder->getRow();

        return $array;
    }

    public function load_action($id)
    {
        $db = db_connect();
        $builder = $db->table('resources');
        $builder = $builder->getWhere(['id' => $id]);
        $array = $builder->getRow();

        return $array;
    }

    public function get_resources()
    {

        $db = db_connect();
        $builder = $db->table('resources');
        //$builder->where('keywords');
        $builder = $builder->get();
        $query = $builder->getResult();

        return $query;
    }

    public function search($number, $term)
    {
        $db = db_connect();
        $builder = $db->table('resources');
        //$builder->where('keywords');
        $builder = $builder->like('keywords', $term);
        $builder = $builder->get();
        $query = $builder->getResult();

        return $query;
    }

    public function load_keystage()
    {
        //Query the data table for every record and row
        $query = $this->db->query("SELECT * FROM category WHERE parent_id = 1");
        return $query;
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

    public function load_topics($arg_subject = null)
    {
        if (isset($arg_subject)) {

            $string = "SELECT * FROM category WHERE parent_id = ?";
            $query  = $this->db->query($string, $arg_subject);
            return $query;
        }
    }

    public function upload_resource($data)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('resources');
        $builder->insert($data);
        //$query = $this-db->query("SELECT * FROM resources_numeracy ORDER BY id DESC LIMIT 20");
    }

    public function update_resource($data, $id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('resources');
        return $builder->update($data, ["id" => $id]);
        //$query = $this-db->query("SELECT * FROM resources_numeracy ORDER BY id DESC LIMIT 20");
    }
}



