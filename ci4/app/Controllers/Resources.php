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
        $data['breadcrumbs'] = $this->request->uri->getSegments();;
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

    public function subjects($page = '', $subject)
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
        $data['breadcrumbs'] = $this->request->uri->getSegments();
        $data['main'] = view('resources/results', $data);
        return view('index', $data);
    }

    public function load($page = '')
    {
        //Get resource details from database
        //slug is last segment of the URI. Get total to get last. 
        $segment = $this->request->uri->getTotalSegments();
        $slug = $this->request->uri->getSegment($segment);

        $resources = new ResourcesModel();
        $row = $resources->load_resource($slug);
        // Capitalize the first letter
        $data['title']       = ucfirst($row->resource_name);
        $data['banner']      = $row->resource_banner;
        $data['thumbnail']   = $row->resource_thumb;
        $data['description'] = $row->resource_description;
        $data['level']       = $row->level;
        $data['dir']         = "resources/" . $row->link;
        $data['action']      = '/resources/loadSheet/' . $row->id;
        $data['form']        = view('../../../' . $row->link . '/index', $data);


        $data['main'] = view('templates/resource', $data);
        return view('index', $data);
    }

    public function loadSheet($id)
    {
        $session = \Config\Services::session();
        $resourcesModel = new ResourcesModel();
    
        $row = $resourcesModel->load_action($id);
    
        $data['title'] = $row->resource_name; // Capitalize the first letter
        $data['footer'] = view("../../app/Views/templates/worksheets/worksheet-page-footer.php", $data);
        $data['worksheet'] = view('../../../' . $row->link . '/' . $row->action, $data);
        
        $urlstring = '../../../' . $row->link . '/' . $row->action;
    
        return view('worksheet', $data);
    }
    

    public function load_test_resources($page = '')
    {
        $data['title'] = ucfirst($page); // Capitalize the first letter
        //call Tpresources class to load resources database
        $db = new ResourcesModel();

        //pagination
        $data['base_url']   = "/resources/load_test_resources/numeracy";
        $data['total_rows'] = $db->num_rows();
        $data['per_page']   = 12;
        $data['num_links']  = 5;
        $resources          = $db->get_test_resources($data['per_page']);

        // call function to create array from database results. 
        $results = $this->create_list_table($resources);

        //save results to table and pass to view
        $data['resources'] = $results;


        $this->pagination->initialize($data);



        $data['links'] = $this->pagination->create_links();

        //pass database result to view as array
        $data['main'] = $this->load->view('resources/' . $page, $data, TRUE);
        $this->load->view('index', $data);
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