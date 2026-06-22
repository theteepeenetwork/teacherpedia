<?php
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

    $data                  = array();
    $dirName                 = 'resources/';
    $date_object             = new DateTime();
    $year                    = date('Y');
    //Month-day-id_of_author
    $month_day               = date("d-m") . '-' . $this->session->id;
    $time                    = $date_object->format('H-i-s');
    $dirLoc                  = $dirName . $year . '/' . $month_day . '/' . $time;

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
        $index_created = 'index.php created';
    } else {
        $index_created = 'index.php failed';
    }
    if ($this->create_code_files($dirLoc, '/' . $action_name, $_POST['generator'])) {
        $action_created = $action_name . ' created';
    } else {
        $action_created = $action_name . ' failed';
    }
    if (isset($_POST['supporting_code1']) || isset($_POST['supporting_code2']) || isset($_POST['supporting_code3'])) {
        if ($this->create_code_files($dirLoc, '/' . $supporting_code1_name, $_POST['supporting_code1']) && $this->create_code_files($dirLoc, '/' . $supporting_code2_name, $_POST['supporting_code2']) && $this->create_code_files($dirLoc, '/' . $supporting_code3_name, $_POST['supporting_code3'])) {
            $supporting_code_created = 'Supporting code created';
        } else {
            $supporting_code_created = 'Supporting code failed';
        }
    }

    /*************************************************************************
     **************************************************************************
     ************                                                **************
     ************               Upload images                    **************
     ************                                                **************
     **************************************************************************
     **************************************************************************
     *************************************************************************/

    $config['upload_path']   = $dirLoc;
    $config['allowed_types'] = '*';
    $config['remove_spaces'] = TRUE;

    $this->load->library('upload', $config);
    $this->upload->initialize($config);

    //upload banner
    $banner_result = $this->upload->do_upload('banner');

    //upload thumb
    $thumb_result = $this->upload->do_upload('thumb');

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
            $word = preg_replace('/\s/', '', $word);
            array_push($keywords_cleaned, $word);
        }

        //If keyword is already in database, increase count. Else add. 
        foreach ($keywords_cleaned as $word) {
            $query       = $this->db->get_where('keywords', array(
                'word' => $word
            ));
            $keywords_db = $query->row();
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
                $this->db->insert('keywords', $data);
                if ($word) {
                    $data['added_keywords'] = array();
                    array_push($data['added_keywords'], $word);
                }
                $id = $this->db->insert_id();
                array_push($keyword_id_array, $id);
            }
            $keywords_to_insert .= $word . ', ';
        }
        $keywords_to_insert  = substr($keywords_to_insert, 0, -2);
        $data['keyword_ids'] = $keyword_id_array;
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
        'resource_author' => $this->session->id,
        'resource_description' => $_POST['resource_description'],
        'resource_excerpt' => $_POST['resource_excerpt'],
        'link' => $dirLoc,
        'keywords' => $keywords_to_insert,
        'action' => $action_name,
        'level' => $_POST['subscriber_level'],
        'year' => $_POST['year'],
        'category' => $_POST['keystage'] . ',' . $_POST['subjects'] . ',' . $_POST['topics'],
        'resource_banner' => 'images/banners_thumbnails/' . $year . '/' . $month_day . '/' . $time . '/' . $_FILES['banner']['name'],
        'resource_thumb' => $dirLoc . '/' . $_FILES['thumb']['name'],
        'slug' => url_title($_POST['resource_name'], 'dash', true),
        'dateAdded' => Date('Y/m/d')
    );

    $data['result']        = $this->Tpresources->upload_resource($toInsert);
    $dir_contents    = array_diff(scandir($dirLoc), array(
        '.',
        '..'
    ));

    $data['page_title']    = 'Basic Form';
    $data['upload_report'] = array(
        'resource_dir' => $dirLoc,
        'resource_dir_contents' => $dir_contents

    );

    $data['main'] = $this->load->view('admin/resources/add_resource_successful', $data, TRUE);
    $this->load->view('dashboard', $data);


    $data['report'] = array(
        'directory' => $dirLoc,
        'action_name' => $_POST['action_name'] . ' = ' . $action_name,
        'supporting_code1' => $_POST['supporting_code1_name'] . ' = ' . $supporting_code1_name,
        'supporting_code2' => $_POST['supporting_code2_name'] . ' = ' . $supporting_code2_name,
        'supporting_code3' => $_POST['supporting_code3_name'] . ' = ' . $supporting_code3_name,
        'index_created' => $index_created,
        'action_created' => $action_created,
        'supporting_created' => $supporting_code_created,
        'Directory_created' => $created_dir,
        'banner_image' => $banner_result,
        'thumb_image' => $thumb_result,



    );
}
