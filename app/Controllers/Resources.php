<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ResourcesModel;
use App\Models\Search;

class Resources extends BaseController
{
    public function view($page = '')
    {
        $session = \Config\Services::session();
        $data['title'] = ucfirst($page); // Capitalize the first letter
        //call Tpresources class to load resources database

        //$this->load->library('pagination');

        //pass database result to view as array
        $data['title']   = ucfirst($page);
        $data['breadcrumbs'] = $this->request->getUri()->getSegments();
        $data['main']    = view('resources/' . $page, $data);

        return view('index', $data);
    }

    public function search($page = '')
    {
        if (isset($_POST['search_input'])) {
            $term = $_POST['search_input'];
        }
        $resourcesModel = new Search();

        $resources_name = $resourcesModel->builder()
            ->like('resource_name', $term)
            ->paginate(5);

        $resources_keywords = $resourcesModel->builder()
            ->like('keywords', $term)
            ->paginate(5);

        $resources_description = $resourcesModel->builder()
            ->like('resource_description', $term)
            ->paginate(5);

        $data['table'] = array_merge($resources_name, $resources_keywords, $resources_description);

        $data['pager'] = $resourcesModel->pager;

        $data['title']   = ucfirst('Search');
        $data['main']    = view('resources/' . $page, $data);
        return view('index', $data);
    }

    public function subjects($page = '', $subject = '')
    {
        $data['title'] = $page;

        $resources = new ResourcesModel();
        $number = 12;
        $query = $resources->get_resources($number, $subject);
        $results = '';

        if (count($query) > 0) {
            $results = $this->create_list_table($query);
        } else {
            $results = "We're still working hard to create some exciting new " . ucfirst($subject) .  " resources. Check back soon!";
        }

        $data['table'] = $results;
        $data['breadcrumbs'] = $this->request->getUri()->getSegments();
        $data['main'] = view('resources/results', $data);
        return view('index', $data);
    }

    public function load($page = '')
    {
        //Get resource details from database
        //slug is last segment of the URI. Get total to get last.
        $uri     = $this->request->getUri();
        $segment = $uri->getTotalSegments();
        $slug    = $uri->getSegment($segment);

        $resources = new ResourcesModel();
        $row = $resources->load_resource($slug);

        if ($row === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Resource not found: ' . $slug);
        }

        // Resource code now lives under app/Views/resources_generated/...
        // and is rendered through the view layer (no longer web-served).
        $base = ResourcesModel::viewBase($row->link);

        // Capitalize the first letter
        $data['title']       = ucfirst($row->resource_name);
        $data['banner']      = $row->resource_banner;
        $data['thumbnail']   = $row->resource_thumb;
        $data['description'] = $row->resource_description;
        $data['level']       = $row->level;
        $data['dir']         = $base;
        $data['action']      = '/resource/loadSheet/' . $row->id;
        $data['form']        = view($base . '/index', $data);

        $data['main'] = view('templates/resource', $data);
        return view('index', $data);
    }

    public function loadSheet($id = null)
    {
        $data['title'] = ucfirst('Resource'); // Capitalize the first letter
        $uri     = $this->request->getUri();
        $segment = $uri->getTotalSegments();
        $id      = $uri->getSegment($segment);

        $resources = new ResourcesModel();
        $row = $resources->load_action($id);

        if ($row === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Resource not found: ' . $id);
        }

        // Render the generator (action) file as a view; strip any .php so the
        // view finder does not double-append the extension.
        $base   = ResourcesModel::viewBase($row->link);
        $action = pathinfo((string) $row->action, PATHINFO_FILENAME);

        return view($base . '/' . $action, $data);
    }


    public function create_list_table($array)
    {
        $results = array();

        foreach ($array as $row) {
            $line =
                '<div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                    <a href="' . $row->slug . '/' . $row->slug .  '"><img style="min-width: 50px;" class="img-fluid thumbnail" ' . 'src="' . $row->resource_thumb . '"></img></a>
                    </div>
                    <div class="middle col-lg-7 col-md-7 col-sm-7">
                        <a href="/resource/' . $row->slug . '">' . $row->resource_name . '</a><br />'
                . $row->resource_excerpt . '</>
                    </div>
                    <div class="middle col-lg-2 col-md-2 col-sm-2">Year ' . $row->year . '</Year>
                    </div>
                </div></a>' .
                '<hr class="featurette-divider">';

            array_push($results, $line);
        }
        return $results;
    }
}
