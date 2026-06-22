<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Admin_Controller;
use CodeIgniter\Controller;
use App\Models\ResourcesModel;
use DateTime;
use Laminas\Escaper\Exception\RuntimeException;
use DirectoryIterator;

class Resources extends Admin_Controller
{

    public function index($page = 'main')
    {
        $data                 = array();
        $data['page_title']   = 'Dashboard';
        $data['main_content'] = view('admin/' . $page, $data);
        return view('dashboard', $data);
    }

    public function confirm($id)
    {
        $data        = array();
        $query       = $this->db->get_where('resources', array(
            'id' => $id
        ));
        $data['row'] = $query->row();

        $data['page_title']   = 'Confirm Delete';
        $data['main_content'] = $this->load->view('admin/resources/form_edit_confirm', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    public function add_resource($page = '')
    {
        $resources_db = new ResourcesModel();

        $data = array();

        $data['page_title']        = 'Add Resource';
        $query                     = $resources_db->load_keystage();
        $query_subject             = $resources_db->load_subject();
        $data['category_keystage'] = $query;
        $data['category_subject']  = $query_subject;

        $data['main_content'] = view('admin/resources/add_resource' . $page, $data);
        return view('dashboard', $data);
    }

    public function list_resources()
    {
        $data               = array();
        $data['page_title'] = 'List of Resources';

        $resources_db = new ResourcesModel();
        $data['resources'] = $resources_db->get_resources();

        $data['main_content'] = view('admin/resources/form_edit_resource', $data);
        return view('dashboard', $data);
    }

    public function edit_resource($id)
    {
        $dir = '';
        $data               = array();
        $data['page_title'] = 'Edit Resource';
        $string             = "";
        if ($id > 0) {

            $resources_db = new ResourcesModel();
            $query = $resources_db->edit_resource($id);
            //$dirs = $query->resource_name;
            $path = '../' . $query->link;
            $files = scandir($path);
            $files = preg_grep('/^([^.])/', $files);
            $count = sizeof($files);
            $data = array_slice($files, 0);

            foreach ($data as $file) {
                $data[$file] = file_get_contents('../' . $query->link . '/' . $file);
            }

            $data = array_slice($data, $count);
            $codearea = 0;
            foreach ($data as $files => $file_value) {
                $string .= '<div class="form-group"><label class="" for="resource_description">' . $files . '</label><br><div class="col-md-12"><pre><textarea id="code' . $codearea . '" rows="400" cols="50" name="' . $files . '">' . $file_value . '</textarea></code></pre></div></div><script>var editor = CodeMirror.fromTextArea(document.getElementById("code' . $codearea . '"), {lineNumbers: true,gutter: true,lineWrapping: true,});</script>';
                $codearea++;
            }
            $data['row']        = $query;
        }

        $data['files']      = $string;

        $data['page_title'] = "Edit Resource";

        $data['main_content'] = view('admin/resources/form_edit', $data);

        return view('dashboard', $data);
    }

    public function delete_resource($id)
    {
        //set new directory to move deleted resources to
        $dirName = 'deleted/';

        //get resource to be deleted
        $query = $this->db->get_where('resources', array(
            'id' => $id
        ));
        $row   = $query->row();

        //make new directories for images and resources in the /deleted folder
        if (!file_exists($dirName)) {
            mkdir($dirName);
        }
        $uri       = explode('/', $row->link);
        $uri_image = explode('/', $row->resource_banner);

        //resource
        if (!file_exists($dirName . $uri[0])) {
            mkdir($dirName . $uri[0]);
        }

        if (!file_exists($dirName . $uri[0] . '/' . $uri[1])) {
            mkdir($dirName . $uri[0] . '/' . $uri[1]);
        }

        if (!file_exists($dirName . $uri[0] . '/' . $uri[1] . '/' . $uri[2])) {
            mkdir($dirName . $uri[0] . '/' . $uri[1] . '/' . $uri[2]);
        }

        if (!file_exists($dirName . $uri[0] . '/' . $uri[1] . '/' . $uri[2] . '/' . $uri[3])) {
            mkdir($dirName . $uri[0] . '/' . $uri[1] . '/' . $uri[2] . '/' . $uri[3]);
        }


        //banner
        if (!file_exists($dirName . $uri_image[0])) {
            mkdir($dirName . $uri_image[0]);
        }

        if (!file_exists($dirName . $uri_image[0] . '/' . $uri_image[1])) {
            mkdir($dirName . $uri_image[0] . '/' . $uri_image[1]);
        }

        if (!file_exists($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2])) {
            mkdir($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2]);
        }

        if (!file_exists($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2] . '/' . $uri_image[3])) {
            mkdir($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2] . '/' . $uri_image[3]);
        }

        if (!file_exists($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2] . '/' . $uri_image[3] . '/' . $uri_image[4])) {
            mkdir($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2] . '/' . $uri_image[3] . '/' . $uri_image[4]);
        }

        //remove file name from image link retrived from database
        $res = substr($row->resource_banner, 0, strrpos($row->resource_banner, '/'));


        //move resources to /deleted folder
        if (!$this->dir_is_empty('application/views/' . $row->link)) {
            $dir_contents = array_diff(scandir('application/views/' . $row->link), array(
                '.',
                '..'
            ));
            foreach ($dir_contents as $file) {
                rename('application/views/' . $row->link . '/' . $file, 'deleted/' . $row->link . '/' . $file);
                if (file_exists('application/views' . $row->link . $file)) {
                    unlink('application/views' . $row->link . $file);
                }
            }
            if (file_exists('application/views/' . $row->link)) {
                rmdir('application/views/' . $row->link);
            }
        }

        //move images to /deleted folder

        $dir = 'public_html/' . $res;
        if (!$this->dir_is_empty($dir)) {
            $image_dir_contents = array_diff(scandir($dir), array(
                '.',
                '..'
            ));
            foreach ($image_dir_contents as $file) {
                copy($dir . '/' . $file, 'deleted/' . $res . '/' . $file);
                if (file_exists($dir . '/' . $file)) {
                    unlink($dir . '/' . $file);
                }
            }
            if (file_exists($dir)) {
                rmdir($dir);
            }
        }


        //record deleted resource in deleted_resources database
        $toInsert = array(
            'old_id' => $row->id,
            'resource_name' => $row->resource_name,
            'resource_author' => $row->resource_author,
            'resource_description' => $row->resource_description,
            'resource_excerpt' => $row->resource_excerpt,
            'link' => $row->link,
            'year' => $row->year,
            'level' => $row->level,
            'resource_banner' => $row->resource_banner,
            'resource_thumb' => $row->resource_thumb,
            'slug' => $row->slug,
            'deleted_date' => Date('Y/m/d'),
            'deleted_by' => $this->session->id
        );

        //delete resource from resources database - removes from website.
        $data['result'] = $this->Tpresources->delete_resource($toInsert, $row->id);

        //display view
        $data['resources']            = $this->db->get('resources');
        $data['page_title']           = 'Resource Successfully deleted';
        $data['deleted_successfully'] = '<div class="col-sm-12">
                                    <div class="white-box">
                                        <h3 class="box-title m-b-0">Resource Deleted</h3>
                                        <p class="text-muted m-b-30">The resource was deleted successfully.</p>
                                    </div>
                                </div>';
        $data['prevent_resumit']      = true;
        $data['main_content']         = $this->load->view('admin/resources/form_edit_resource', $data, TRUE);
        //redirect to prevent resubmit
        redirect(base_url() . 'admin/resources/list_resources');
    }

    //upload new resource to database

    /*************************************************************************
     **************************************************************************
     ************                                                **************
     ************         DO NOT CHANGE DIRECTORY STRUCTURE      **************
     ************                                                **************
     **************************************************************************
     **************************************************************************
     *************************************************************************/
    function do_uploads($page = "")
    {
        /*************************************************************************
         **************************************************************************
         ************                                                **************
         ************               Variables                        **************
         ************                                                **************
         **************************************************************************
         **************************************************************************
         *************************************************************************/

        $data                    = array();
        //$slug                    = url_title($_POST['resource_name'], 'dash', true);
        $dirName                 = 'resources/';
        $new_dir                 = '../' . $dirName;
        $date_object             = new DateTime();
        $year                    = date('Y');
        //Month-day-id_of_author
        $month_day               = date("d-m");
        $time                    = $date_object->format('H-i-s');
        $dirLoc                  = $dirName . $year . '/' . $month_day . '/' . $time;
        $new_dirloc              = '../' . $dirName . $year . '/' . $month_day . '/' . $time;
        $images_dir              =  'assets/img/banners_thumbnails/';
        $this_images_dir         =  'assets/img/banners_thumbnails/' . $year . '/' . $month_day . '/' . $time;
        $resources_db            = new ResourcesModel();
        $banner_name             = '';
        $thumb_name              = '';

        /*************************************************************************
         **************************************************************************
         ************                                                **************
         ************               Clean filenames                  **************
         ************                                                **************
         **************************************************************************
         **************************************************************************
         *************************************************************************/

        $action_name           = $this->clean_file_names($_POST['action_name']);
        $supporting_code1_name = $this->clean_file_names($_POST['supporting_code1_name']);
        $supporting_code2_name = $this->clean_file_names($_POST['supporting_code2_name']);
        $supporting_code3_name = $this->clean_file_names($_POST['supporting_code3_name']);

        /*************************************************************************
         **************************************************************************
         ************                                                **************
         ************               Create directory                 **************
         ************                                                **************
         **************************************************************************
         **************************************************************************
         *************************************************************************/

        $created_dir = $this->make_dir($dirName, $year, $month_day, $time);

        /*************************************************************************
         **************************************************************************
         ************                                                **************
         ************               Write files                      **************
         ************                                                **************
         **************************************************************************
         **************************************************************************
         *************************************************************************/

        //write code files to created diretory
        if ($this->create_code_files($dirLoc, "/index.php", $_POST['form_code'])) {
            $index_created = 'index.php failed';
        } else {
            $index_created = 'index.php created';
        }
        if ($this->create_code_files($dirLoc, '/' . $action_name, $_POST['generator'])) {
            $action_created = $action_name . ' failed';
        } else {
            $action_created = $action_name . ' created';
        }
        if (!empty($_POST['supporting_code1']) || !empty($_POST['supporting_code2']) || !empty($_POST['supporting_code3'])) {
            if (!empty($_POST['supporting_code1'])) {
                $this->create_code_files($dirLoc, '/' . $supporting_code1_name, $_POST['supporting_code1']);
            }
            if (!empty($_POST['supporting_code2'])) {
                $this->create_code_files($dirLoc, '/' . $supporting_code2_name, $_POST['supporting_code2']);
            }
            if (!empty($_POST['supporting_code3'])) {
                $this->create_code_files($dirLoc, '/' . $supporting_code3_name, $_POST['supporting_code3']);
            }


            /*if ($this->create_code_files($dirLoc, '/' . $supporting_code1_name, $_POST['supporting_code1']) && $this->create_code_files($dirLoc, '/' . $supporting_code2_name, $_POST['supporting_code2']) && $this->create_code_files($dirLoc, '/' . $supporting_code3_name, $_POST['supporting_code3'])) {
                $supporting_code_created = 'Supporting code failed';
            } else {
                $supporting_code_created = 'Supprting code created';
            }*/
        }

        /*************************************************************************
         **************************************************************************
         ************                                                **************
         ************               Upload images       - not working             **************
         ************                                                **************
         **************************************************************************
         **************************************************************************
         *************************************************************************/

    /*
        $banner = $this->request->getFile('banner');
        $thumb = $this->request->getFile('thumb');

        if($banner === null) {
            
           }

        if (!$banner->isValid() && !$thumb->isValid()) {
            throw new RuntimeException($banner->getErrorString() . '(' . $banner->getError() . ')');
            throw new RuntimeException($thumb->getErrorString() . '(' . $thumb->getError() . ')');
        }

        if ($banner->isValid() && !$banner->hasMoved()) {
            $original = $banner->getName();
            $banner_extension = pathinfo($original, PATHINFO_EXTENSION);
            $banner_name = $slug . '_banner.' . $banner_extension;
            $this->make_dir($images_dir, $year, $month_day, $time);
            $banner->move($images_dir . '/' . $year . '/' . $month_day . '/' . $time, $banner_name);
        }
        if ($thumb->isValid() && !$thumb->hasMoved()) {
            $original = $thumb->getName();
            $thumb_extension = pathinfo($original, PATHINFO_EXTENSION);
            $thumb_name = $slug . '_thumb.' . $thumb_extension;
            $this->make_dir($images_dir, $year, $month_day, $time);
            $thumb->move($images_dir . '/' . $year . '/' . $month_day . '/' . $time, $thumb_name);
        }
    */


        //upload banner 
        /*if ($this->upload->do_upload('banner')) {
            $banner_result = 'Banner uploaded to ' . $dirLoc;
        }

        //upload thumb
        if ($this->upload->do_upload('thumb')) {
            $thumb_result = 'Thumb uploaded to ' . $dirLoc;
        }*/

        /*************************************************************************
         **************************************************************************
         ************                                                **************
         ************               Keyword                          **************
         ************                                                **************
         **************************************************************************
         **************************************************************************
         *************************************************************************/

        $data['existing_keywords'] = array();
        $data['added_keywords']    = array();

        if ($_POST['keywords'] != '') {
            $keyword_id_array   = array();
            $keywords_to_insert = '';
            $keywords           = explode(',', $_POST['keywords']);
            $data['keywords']   = $keywords;
            $keywords_cleaned   = array();
            foreach ($keywords as $word) {
                $word = strtolower($word);
                //$word = preg_replace('/\s/', '', $word);
                array_push($keywords_cleaned, $word);
            }

            //If keyword is already in database, increase count. Else add. 
            foreach ($keywords_cleaned as $word) {
                $db      = \Config\Database::connect();
                $builder = $db->table('keywords');

                $builder->getWhere([
                    'word' => $word
                ]);
                $keywords_db = $builder->get();
                if (isset($keywords_db->word)) {
                    $db_word = $keywords_db->word;
                } else {
                    $db_word = '';
                }
                if ($db_word == $word) {
                    $this->db->where('word', $word);
                    $this->db->set('count', 'count+1', FALSE);
                    $this->db->update('keywords');
                    $query     = $this->db->get_where('keywords', array(
                        'word' => $word
                    ));
                    $query_row = $query->row();
                    $id        = $query_row->id;
                    array_push($keyword_id_array, $id);
                    if ($word == null) {
                        $word = "";
                    }
                    array_push($data['existing_keywords'], $word);
                } else {
                    $data = array(
                        'word' => $word,
                        'count' => 1
                    );
                    $builder->replace($data);
                    if ($word) {
                        $data['added_keywords'] = array();
                        array_push($data['added_keywords'], $word);
                    }
                    //array_push($keyword_id_array, $id);
                }
                $keywords_to_insert .= $word . ', ';
            }
            $keywords_to_insert  = substr($keywords_to_insert, 0, -2);
            //$data['keyword_ids'] = $keyword_id_array;
        }

        /*************************************************************************
         **************************************************************************
         ************                                                **************
         ************               Insert into array                **************
         ************                                                **************
         **************************************************************************
         **************************************************************************
         *************************************************************************/
        $toInsert = array(
            'resource_name' => $_POST['resource_name'],
            'resource_author' => session()->get('id'),
            'resource_description' => $_POST['resource_description'],
            'resource_excerpt' => $_POST['resource_excerpt'],
            'link' => $dirLoc,
            'keywords' => $keywords_to_insert,
            'action' => $action_name,
            'level' => $_POST['subscriber_level'],
            'year' => $_POST['year'],
            'category' => $_POST['keystage'] . ',' . $_POST['subjects'] . ',' . $_POST['topics'],
            'resource_banner' => '/' . $this_images_dir . '/' . $banner_name,
            'resource_thumb' => '/' . $this_images_dir . '/' . $thumb_name,
            'slug' => url_title($_POST['resource_name'], '-', true),
            'dateAdded' => Date('Y/m/d')
        );

        $data['result']        = $resources_db->upload_resource($toInsert);
        $dir_contents    = array_diff(scandir($dirLoc), array(
            '.',
            '..',
            '.php'
        ));

        $data['page_title']    = 'Basic Form';

        $data['report'] = array(
            //'delete' => $delete,
            'directory' => $dirLoc,
            'contents' => $dir_contents,
            'action_name' => $_POST['action_name'] . ' = ' . $action_name,
            'supporting_code1' => $_POST['supporting_code1_name'] . ' = ' . $supporting_code1_name,
            'supporting_code2' => $_POST['supporting_code2_name'] . ' = ' . $supporting_code2_name,
            'supporting_code3' => $_POST['supporting_code3_name'] . ' = ' . $supporting_code3_name,
            'index_created' => $index_created,
            'action_created' => $action_created,
            //'supporting_created' => $supporting_code_created,
            'Directory_created' => $created_dir,
            //'banner_image' => $banner_result,
            //'thumb_image' => $thumb_result,
        );


        /*************************************************************************
         **************************************************************************
         ************                                                **************
         ************               Move from Public to Root         **************
         ************                                                **************
         **************************************************************************
         **************************************************************************
         *************************************************************************/
        $this->make_dir($new_dir, $year, $month_day, $time);
        // images folder creatio
        // Get array of all source files
        $files = scandir($dirLoc);
        // Identify directories
        $source = $dirLoc . '/';
        $destination = $new_dirloc . '/';
        // Cycle through all source files
        foreach ($files as $file) {
            if (in_array($file, array(".", ".."))) continue;
            // If we copied this successfully, mark it for deletion
            if (copy($source . $file, $destination . $file)) {
                $delete[] = $source . $file;
            }
        }
        // Delete all successfully-copied files
        foreach ($delete as $file) {
            unlink($file);
        }

        /*************************************************************************
         **************************************************************************
         ************                                                **************
         ************               Load View                        **************
         ************                                                **************
         **************************************************************************
         **************************************************************************
         *************************************************************************/
        $data['main_content'] = view('admin/resources/add_resource_successful', $data);
        return view('dashboard', $data);
    }

    function update_resource($id)
    {
        $data = array();
        //database
        $to_update = array(
            'resource_name' => $_POST['resource_name'],
            'resource_description' => $_POST['resource_description'],
            'resource_excerpt' => $_POST['resource_excerpt'],
        );

        $resources_db = new ResourcesModel();
        $data['result']        = $resources_db->update_resource($to_update, $id);

        // end database

        //starts update files
        $query = $resources_db->load_resource($id);
        $dirs = $query->link;
        $dirName_resource        = '../';
        $dirLoc_resource         = $dirName_resource . $dirs;

        $sliced_files = array();
        $dir = '../';
        $path = $dir . '/' . $dirs;
        $files = scandir($path);
        $files = preg_grep('/^([^.])/', $files);
        $count = sizeof($files);
        $sliced_files = array_slice($files, 0);


        $data['match'] = '';
        $tester = '';
        foreach ($_POST as $key => $post) {
            $key = str_replace("_", ".", $key);
            foreach ($sliced_files as $file) {
                $data['match'] .= $file . ' ~  ' . $key . '<br>';
                if ($file == $key) {
                    $stream = fopen($path . '/' . $key, "w");
                    fwrite($stream, $post);
                    $data['match'] .= 'dir = ' . $path . '/' . $key . '<br>';
                }
            }
        }





        /*foreach ($data as $file) {
            $data[$file] = fwrite($dir . '/' . $dirs->link . '/' . $file);
        }*/

        //$data = array_slice($sliced_files, $count);




        /*create_code_files($dirLoc_resource, "/index.php", $_POST['form_code']);
        //write form action code
        create_code_files($dirLoc_resource, '/' . $action_name, $_POST['generator']);
        //write optional code
        create_code_files($dirLoc_resource, '/' . $supporting_code1_name, $_POST['supporting_code1']);
        create_code_files($dirLoc_resource, '/' . $supporting_code2_name, $_POST['supporting_code2']);
        create_code_files($dirLoc_resource, '/' . $supporting_code3_name, $_POST['supporting_code3']);*/

        //end update files



        if ($update) {
            //$data                 = array();
            $data['resource_name'] = $_POST['resource_name'];
            $data['report']       = 'Successfully edited';
            $data['tester'] = $tester;
            $data['sliced_files'] = $sliced_files;
            $data['post'] = $_POST;
            $data['page_title']   = 'Successful Edit';
            //$data['report']       = 'Successful Edit';
            $data['main_content'] = view('admin/resources/edit_success', $data);
            return view('dashboard', $data);
        } else {
            $data                 = $data;
            $data['page_title']   = 'Something went wrong';
            $data['report']       = 'Update failed';
            $data['id'] = $id;
            $data["resource_name"] = $_POST['resource_name'];
            $data["reason"] = $result;
            $data['main_content'] = view('admin/resources/edit_success', $data);
            return view('dashboard', $data);
        }
    }

    function update_test_resource($id)
    {
        $data = array();
        //database
        $to_update = array(
            'resource_name' => $this->request->getVar('resource_name'),
            'resource_description' => $this->request->getVar('resource_description'),
            'year' => $this->request->getVar('year_group'),
        );

        $resources_db = new ResourcesModel();
        $update = $resources_db->update_test_resource($to_update, $id);
        // end database

        //starts update files
        $query = $resources_db->load_test_resource($id);
        $dirs = $query->link;
        $dirName_resource        = '../';
        $dirLoc_resource         = $dirName_resource . $dirs;

        $sliced_files = array();
        $path = $dirs;
        $files = scandir($path);
        $files = preg_grep('/^([^.])/', $files);
        $count = sizeof($files);
        $sliced_files = array_slice($files, 0);


        $data['match'] = '';
        $tester = '';
        foreach ($_POST as $key => $post) {
            $key = str_replace("_", ".", $key);
            foreach ($sliced_files as $file) {
                $data['match'] .= $file . ' ~  ' . $key . '<br>';
                if ($file == $key) {
                    $stream = fopen($path . '/' . $key, "w");
                    fwrite($stream, $post);
                    $data['match'] .= 'dir = ' . $path . '/' . $key . '<br>';
                }
            }
        }

        /*foreach ($data as $file) {
            $data[$file] = fwrite($dir . '/' . $dirs->link . '/' . $file);
        }*/

        //$data = array_slice($sliced_files, $count);




        /*create_code_files($dirLoc_resource, "/index.php", $_POST['form_code']);
        //write form action code
        create_code_files($dirLoc_resource, '/' . $action_name, $_POST['generator']);
        //write optional code
        create_code_files($dirLoc_resource, '/' . $supporting_code1_name, $_POST['supporting_code1']);
        create_code_files($dirLoc_resource, '/' . $supporting_code2_name, $_POST['supporting_code2']);
        create_code_files($dirLoc_resource, '/' . $supporting_code3_name, $_POST['supporting_code3']);*/

        //end update files



        if ($update) {
            //$data                 = array();
            $data['tester'] = $tester;
            $data['sliced_files'] = $sliced_files;
            $data['post'] = $update;
            $data['page_title']   = 'Successful Edit';
            $data['report']       = 'Successful Edit';
            $data['main_content'] = view('admin/resources/edit_success', $data);
            return view('dashboard', $data);
        } else {
            $data                 = array();
            $data['page_title']   = 'Something went wrong';
            $data['post'] = $update;
            $data['id'] = $id;
            $data['main_content'] = view('admin/resources/edit_success', $data);
            return view('dashboard', $data);
        }
    }

    function get_subjects()
    {
        $resources_db = new ResourcesModel();
        if (isset($_POST["keystage"])) {
            // Capture selected country
            $keystage = $_POST["keystage"];

            $query         = $resources_db->load_subject($keystage);
            $subject_table = $query->getResult();

            // Display city dropdown based on country name
            echo '<option>-</option>';
            foreach ($subject_table as $value) {
                echo "<option value=" . $value->id . ">" . $value->title . "</option>";
            }
        }
    }

    function get_topics()
    {
        $resources_db = new ResourcesModel();
        if (isset($_POST['subject'])) {
            // Capture selected country
            $subject     = $_POST['subject'];
            $query       = $resources_db->load_topics($subject);
            $topic_table = $query->getResult();

            // Display city dropdown based on country name
            echo '<option>-</option>';
            foreach ($topic_table as $value) {
                echo "<option value=" . $value->id . ">" . $value->title . "</option>";
            }
        }
    }

    function add_topic()
    {
        if (isset($_POST["keystage"])) {
            // Capture selected country
            $keystage = $_POST["keystage"];

            $query = $this->Tpresources->load_subject();

            $subject_table = $query->result();

            // Display city dropdown based on country name
            if ($keystage !== 'Select') {
                echo '<option></option>';
                foreach ($subject_table as $value) {
                    echo "<option>" . $value->subject . "</option>";
                }
                echo '<input></input>';
            }
        }
    }

    // ****************************************************************************************************************************
    // ****************************************************************************************************************************
    // ****************************************************************************************************************************
    // TEST FUNCTIONS
    // ****************************************************************************************************************************
    // ****************************************************************************************************************************
    // ****************************************************************************************************************************
    public function test_resource()
    {
        $data = array();
        $test_resources_db = new ResourcesModel();
        $data['page_title']        = 'Test Resource';
        $query                     = $test_resources_db->load_keystage();
        $query_subject             = $test_resources_db->load_subject();
        $data['category_keystage'] = $query;
        $data['category_subject']  = $query_subject;

        $data['main_content'] = view('admin/resources/create_test_resource', $data);
        return view('dashboard', $data);
    }

    public function list_test_resources()
    {
        $data               = array();
        $data['page_title'] = 'List of Resources';

        $resources_model = new ResourcesModel();
        $data['resources'] = $resources_model->get_test_resources();

        $data['main_content'] = view('admin/resources/form_edit_test_resource', $data);
        return view('dashboard', $data);
    }

    public function form_elements()
    {
        $data               = array();
        $data['page_title'] = 'Form elements';

        $data['main_content'] = $this->load->view('admin/resources/form_elements', $data, TRUE);
        $this->load->view('admin/index', $data);
    }


    public function edit_test_resource($id)
    {
        $dir = '';
        $data               = array();
        $data['page_title'] = 'Edit Test Resource';
        $string             = "";
        if ($id > 0) {

            $resources_db = new ResourcesModel();
            $query = $resources_db->load_test_resource($id);
            //$dirs = $query->resource_name;
            $path = $query->link;
            $files = scandir($path);
            $files = preg_grep('/^([^.])/', $files);
            $count = sizeof($files);
            $data = array_slice($files, 0);

            foreach ($data as $file) {
                $data[$file] = file_get_contents($query->link . '/' . $file);
            }

            $data = array_slice($data, $count);
            $codearea = 0;
            foreach ($data as $files => $file_value) {
                $string .= '<div class="form-group"><label class="" for="resource_description">' . $files . '</label><br><div class="col-md-12"><pre><textarea id="code' . $codearea . '" rows="400" cols="50" name="' . $files . '">' . $file_value . '</textarea></code></pre></div></div><script>var editor = CodeMirror.fromTextArea(document.getElementById("code' . $codearea . '"), {lineNumbers: true,gutter: true,lineWrapping: true,});</script>';
                $codearea++;
            }
            $data['row']        = $query;
        }

        $data['files']      = $string;

        $data['page_title'] = "Edit Resource";

        $data['main_content'] = view('admin/resources/form_edit_test', $data);

        return view('dashboard', $data);
    }

    public function delete_test_resource($id)
    {
        //set new directory to move deleted resources to
        $dirName = 'deleted/';

        //get resource to be deleted
        $query = $this->db->get_where('test_resources', array(
            'id' => $id
        ));
        $row   = $query->row();

        //make new directories for images and resources in the /deleted folder
        if (!file_exists($dirName)) {
            mkdir($dirName);
        }
        $uri       = explode('/', $row->link);
        $uri_image = explode('/', $row->resource_banner);

        //resource
        if (!file_exists($dirName . $uri[0])) {
            mkdir($dirName . $uri[0]);
        }

        if (!file_exists($dirName . $uri[0] . '/' . $uri[1])) {
            mkdir($dirName . $uri[0] . '/' . $uri[1]);
        }

        if (!file_exists($dirName . $uri[0] . '/' . $uri[1] . '/' . $uri[2])) {
            mkdir($dirName . $uri[0] . '/' . $uri[1] . '/' . $uri[2]);
        }

        if (!file_exists($dirName . $uri[0] . '/' . $uri[1] . '/' . $uri[2] . '/' . $uri[3])) {
            mkdir($dirName . $uri[0] . '/' . $uri[1] . '/' . $uri[2] . '/' . $uri[3]);
        }

        if (!file_exists($dirName . $uri[0] . '/' . $uri[1] . '/' . $uri[2] . '/' . $uri[3] . '/' . $uri[4])) {
            mkdir($dirName . $uri[0] . '/' . $uri[1] . '/' . $uri[2] . '/' . $uri[3]  . '/' . $uri[4]);
        }

        //banner
        /*
        if (!file_exists($dirName . $uri_image[0])) {
            mkdir($dirName . $uri_image[0]);
        }
        
        if (!file_exists($dirName . $uri_image[0] . '/' . $uri_image[1])) {
            mkdir($dirName . $uri_image[0] . '/' . $uri_image[1]);
        }
        
        if (!file_exists($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2])) {
            mkdir($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2]);
        }
        
        if (!file_exists($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2] . '/' . $uri_image[3])) {
            mkdir($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2] . '/' . $uri_image[3]);
        }
        
        if (!file_exists($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2] . '/' . $uri_image[3] . '/' . $uri_image[4])) {
            mkdir($dirName . $uri_image[0] . '/' . $uri_image[1] . '/' . $uri_image[2] . '/' . $uri_image[3] . '/' . $uri_image[4]);
        }*/

        //remove file name from image link retrived from database
        $res = substr($row->resource_banner, 0, strrpos($row->resource_banner, '/'));


        //move resources to /deleted folder
        $dir = 'application/views' . '/' . $row->link;

        if (!$this->dir_is_empty($dir)) {
            $dir_contents = array_diff(scandir($dir), array(
                '.',
                '..'
            ));
            foreach ($dir_contents as $file) {
                rename($dir . '/' . $file, 'deleted/' . $row->link . '/' . $file);
                if (file_exists($dir . $file)) {
                    unlink($dir . $file);
                }
            }
            if (file_exists($dir)) {
                rmdir($dir);
            }
        }

        //move images to /deleted folder

        $dir = 'public_html/' . $res;
        if (!$this->dir_is_empty($dir)) {
            $image_dir_contents = array_diff(scandir($dir), array(
                '.',
                '..'
            ));
            foreach ($image_dir_contents as $file) {
                copy($dir . '/' . $file, 'deleted/' . $res . '/' . $file);
                if (file_exists($dir . '/' . $file)) {
                    unlink($dir . '/' . $file);
                }
            }
            if (file_exists($dir)) {
                rmdir($dir);
            }
        }


        //record deleted resource in deleted_resources database
        $toInsert = array(
            'old_id' => $row->id,
            'resource_name' => $row->resource_name,
            'resource_author' => $row->resource_author,
            'resource_description' => $row->resource_description,
            'resource_excerpt' => $row->resource_excerpt,
            'link' => $row->link,
            'year' => $row->year,
            'level' => $row->level,
            'resource_banner' => $row->resource_banner,
            'resource_thumb' => $row->resource_thumb,
            'slug' => $row->slug,
            'deleted_date' => Date('Y/m/d'),
            'deleted_by' => $this->session->id
        );

        //delete resource from resources database - removes from website.
        $data['result'] = $this->Tpresources->delete_test_resource($toInsert, $row->id);

        //display view
        $data['resources']            = $this->db->get('resources');
        $data['page_title']           = 'Resource Successfully deleted';
        $data['deleted_successfully'] = '<div class="col-sm-12">
                                    <div class="white-box">
                                        <h3 class="box-title m-b-0">Resource Deleted</h3>
                                        <p class="text-muted m-b-30">The resource was deleted successfully.</p>
                                    </div>
                                </div>';
        $data['prevent_resumit']      = true;
        $data['main']         = $this->load->view('admin/resources/form_edit_resource', $data, TRUE);
        //redirect to prevent resubmit
        redirect(base_url() . 'admin/resources/list_test_resources');
    }

    //wo

    //upload new resource to database

    function create_test($page = "")
    {
        $data                  = array();
        $data['banner_upload'] = array();
        $data['upload_report'] = array();
        $resource_name         = url_title($this->request->getVar('resource_name'));


        //create dates for directories
        $dirName                 = '../test_resources/';
        $template_loc            = '../test_resources/template/';
        $date_object             = new DateTime();
        $year                    = date('Y');
        $month_day               = date("d-m");
        $time                    = $date_object->format('H-i-s-v');
        $dirLoc_resource         = $dirName . $year . '/' . $month_day . '/' . $time;
        //$dirLoc_resource_db_view = '../test_resources/test' . '/' . $year . '/' . $month_day . '/' . $time;

        //copy directory
        $this->make_dir($dirName, $year, $month_day, $time);
        copy($template_loc . 'index.php', $dirLoc_resource . '/' . 'index.php');
        copy($template_loc . 'generator.php', $dirLoc_resource . '/' . 'generator.php');
        //rename($dirName_resource . '/' . 'template', $dirName_resource . '/' . $resource_name);

        //insert into testing resources database
        $toInsert = array(
            'resource_name' => $_POST['resource_name'],
            'resource_author' => session()->get('id'),
            'resource_description' => $_POST['resource_description'],
            'resource_excerpt' => $_POST['resource_excerpt'],
            'link' => $dirLoc_resource,
            //'keywords' => $keywords_to_insert,
            //'action' => /*$dirLoc_resource_db_view . '/' . */ $action_name, // changed so form only calls echo $action from database
            'level' => $_POST['subscriber_level'],
            'year' => $_POST['year'],
            'category' => $_POST['keystage'] . ',' . $_POST['subjects'] . ',' . $_POST['topics'],
            //'resource_banner' => 'images/banners_thumbnails/' . $year . '/' . $month_day . '/' . $time . '/' . $_FILES['banner']['name'],
            //'resource_thumb' => $dirLoc . '/' . $_FILES['thumb']['name'],
            'slug' => url_title($_POST['resource_name'], 'dash', true),
            'dateAdded' => Date('Y/m/d')
        );

        $resources_db = new ResourcesModel();
        $resources_db->upload_test_resource($toInsert);
        //$this->session->set_userdata('resource_id', $this->db->insert_id());

        /*if (!isset($data['errors'])) {
            $data['errors'] = array(
                'no errors' => 'no errors to report'
            );
        }*/

        $data['main_content'] = view('admin/resources/add_resource' . $page, $data);
        return view('dashboard', $data);
    }

    public function test_confirm($id)
    {
        $data        = array();
        $query       = $this->db->get_where('test_resources', array(
            'id' => $id
        ));
        $data['row'] = $query->row();

        $data['page_title']   = 'Confirm Delete';
        $data['main'] = $this->load->view('admin/resources/form_edit_confirm', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    //function to check if dir is empty.
    function dir_is_empty($dirname)
    {
        if (!is_dir($dirname))
            return false;
        foreach (scandir($dirname) as $file) {
            if (!in_array($file, array(
                '.',
                '..',
                '.svn',
                '.git'
            )))
                return false;
        }
        return true;
    }

    function make_dir($dirName, $year, $month_day, $time)
    {
        if (!file_exists($dirName)) {
            mkdir($dirName);
            $data['exist'] = $dirName;
        }

        if (!file_exists($dirName . '/' . $year)) {
            mkdir($dirName . $year);
            $data['exist'] = $dirName . $year;
        }

        if (!file_exists($dirName . $year . '/' . $month_day)) {
            mkdir($dirName . $year . '/' . $month_day);
            $data['exist'] = $dirName . $year . '/' . $month_day;
        }

        if (!file_exists($dirName . $year . '/' . $month_day . '/' . $time)) {
            mkdir($dirName . $year . '/' . $month_day . '/' . $time);
            $data['exist'] = $dirName . $year . '/' . $month_day . '/' . $time;
            return 'Success';
        } else {
            return 'Failed';
        }
    }

    function create_code_files($dir, $file_name, $form_code)
    {
        $theFile = fopen($dir . $file_name, "w") or die("Unable to open file!");
        $txt = $form_code;
        fwrite($theFile, $txt);
        fclose($theFile);
    }

    function clean_file_names($name)
    {
        //remove whitespace
        $action_name = trim($name);
        //remove file type, if entered. 
        $temp        = explode('.', $name);
        $name        = $temp[0] . '.php';
        return $name;
    }
}