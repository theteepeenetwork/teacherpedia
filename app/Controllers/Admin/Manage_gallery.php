<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Image;
use Laminas\Escaper\Exception\RuntimeException;
use DateTime;

class Manage_Gallery extends Admin_Controller
{

    public function index()
    {
        $data = array();

        $con = array(
            'where' => array(
                'status' => 1
            )
        );
        $image = new Image();
        $data['gallery'] = $image->getRows($con);
        $data['page_title'] = 'Images Gallery';
        $data['main_content'] = view('admin/manage_gallery/index', $data);

        return view('dashboard', $data);

        // Load the list page view 


    }

    public function load($id)
    {
        $data = array();
        $image = new Image();
        // Check whether id is not empty 
        if (!empty($id)) {
            $con = array('id' => $id);
            $data['image'] = $image->view($con);
            //$data['page_title'] = $data['image']['title'];
            $data['main_content'] = view('/admin/manage_gallery/view', $data);

            return view('dashboard', $data);
        } else {
            redirect($this->controller);
        }
    }

    public function add()
    {
        $data = $imgData = array();
        $error = '';
        $session = \Config\Services::session();
        $date = date("y-m");
        $dirLoc =  'assets/img/gallery/' . $date;

        if (!is_dir($dirLoc)) {
            mkdir($dirLoc);
        }

        // If add request is submitted

        if ($this->request->getMethod() == 'post') {

            $link = '&lt;img src="&lt;?php echo base_url()?&gt; . ' . /*$this->uploadPath .*/ '/' . $_FILES['image']['name'] . '" alt="' . $this->request->getPost('alt') . '"&gt;';

            // Prepare gallery data 
            $imgData = array(
                'title' => $this->request->getPost('title'),
                'alt' => $this->request->getPost('alt'),
                //'link' => $link
            );

            // Validate submitted form data 
            if ($this->validate('admin_image_upload')) {
                $data['validation'] = $this->validator;
                // Upload image file to the server 
            } else {
                if (!empty($_FILES['image']['name'])) {
                    // File upload configuration 
                    $image = $this->request->getFile('image');
                    if (!$image->isValid()) {
                        throw new RuntimeException($image->getErrorString() . '(' . $image->getError() . ')');
                    }

                    $image_title = $this->request->getPost('title');
                    $image = $this->request->getFile('image');
                    $ext = $image->getExtension();
                    $new_name = remove_invisible_characters($image_title) . '_' . rand(100, 999) . '_' . $session->id . '.' . $ext;
                    $file_name = str_replace(' ', '-', $new_name);


                    if ($image->isValid() && !$image->hasMoved()) {
                        $image->move(
                            $dirLoc,
                            $file_name
                        );



                        // Upload file to server 
                        if ($image->hasMoved()) {
                            // Uploaded file data 
                            $data = [
                                'title' => $this->request->getPost('title'),
                                'file_name' => $file_name,
                                'alt' => $this->request->getPost('alt'),
                                'created' => Date('y/m/d'),
                                'html_link' => 'src="' . '/' . $dirLoc . '/' . $file_name . '" alt="' . $this->request->getPost('alt'),
                                'link' => '/' . $dirLoc . '/' . $file_name,

                            ];
                            $_SESSION['upload'] = $data;
                            $session->markAsFlashdata('upload');


                            $image_model = new Image();
                            $data['result']        = $image_model->upload_images($data);
                        } else {
                            $error = $this->upload->display_errors();
                        }
                    }
                }
            }
        }

        $data['page_title'] = 'Upload Image';
        $data['action'] = 'Upload';
        $data['main_content'] = view('admin/manage_gallery/add-edit', $data);

        // Load the add page view 
        return view('dashboard', $data);
    }

    public function edit($id)
    {
        $data = $imgData = array();
        $image = new Image();

        // Get image data 
        $con = array('id' => $id);
        $imgData = $image->view($con);

        $data['image'] = $imgData;
        $data['page_title'] = 'Update Image';
        $data['action'] = 'Edit';
        $data['main_content'] = view('/admin/manage_gallery/edit_image', $data);

        return view('dashboard', $data);
    }

    public function update_image($id)
    {
        $image = new Image();
        $title = $this->request->getPost('title');
        $alt = $this->request->getPost('alt');


        $image_data = $image->view($id);

        $original_file_name = $image_data->file_name;

        $ext = substr(strrchr($image_data->file_name, '.'), 1);
        $file_name = str_replace(' ', '-', $title . '.' . $ext);


        $data = [
            'title' => $title,
            'alt' => $alt,
            'file_name' => $file_name,
            'link' => '&lt;img src="' . 'img/gallery/' . $file_name . '" alt="' . $alt . '"&gt;',
        ];

        if (file_exists('img/gallery/' . $file_name)) {
            return "File exists";
        } else {
            $renamed = rename('img/gallery/' . $original_file_name, 'img/gallery/' . $file_name);

            if ($renamed) {
                echo "The file has been successfully renamed";
            } else {
                echo "The file has not been successfully renamed";
            }
        }

        $image->update_image($data, $id);

        //$data['gallery'] = $image->getRows($con);
        $data['page_title'] = 'Images Gallery';
        $data['main_content'] = view('admin/manage_gallery/index', $data);

        return view('dashboard', $data);
    }

    public function block($id)
    {
        // Check whether id is not empty 
        if ($id) {
            // Update image status 
            $data = array('status' => 0);
            $update = $this->image->update($data, $id);

            if ($update) {
                $this->session->set_userdata('success_msg', 'Image has been blocked successfully.');
            } else {
                $this->session->set_userdata('error_msg', 'Some problems occurred, please try again.');
            }
        }

        redirect($this->controller);
    }

    public function unblock($id)
    {
        // Check whether is not empty 
        if ($id) {
            // Update image status 
            $data = array('status' => 1);
            $update = $this->image->update($data, $id);

            if ($update) {
                $this->session->set_userdata('success_msg', 'Image has been activated successfully.');
            } else {
                $this->session->set_userdata('error_msg', 'Some problems occurred, please try again.');
            }
        }

        redirect($this->controller);
    }

    public function image_delete($id)
    {
        $image = new Image();
        $dirLoc =  'img/gallery';


        // Check whether id is not empty 
        if ($id) {
            //$con = array('id' => $id);
            // Delete gallery data 

            $link = $image->image_delete($id);


            if ($link > "") {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $link)) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . $link);
                }
                // Remove file from the server  

            }

            session()->setFlashdata('success_msg', 'Image has been removed successfully.');
        } else {
            session()->setFlashdata('error_msg', 'Some problems occurred, please try again.');
        }

        return $this->index();
    }

    public function file_check($str)
    {
        if (empty($_FILES['image']['name'])) {
            $this->form_validation->set_message('file_check', 'Select an image file to upload.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
