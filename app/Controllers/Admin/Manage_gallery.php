<?php

namespace App\Controllers\Admin;

use App\Models\Image;
use RuntimeException;

class Manage_gallery extends Admin_Controller
{
    protected $helpers = ['url', 'text', 'filesystem'];

    public function index()
    {
        $data  = [];
        $image = new Image();

        $con = [
            'where' => [
                'status' => 1,
            ],
        ];

        $data['gallery']      = $image->getRows($con);
        $data['page_title']   = 'Images Gallery';
        $data['success_msg']  = session()->getFlashdata('success_msg');
        $data['error_msg']    = session()->getFlashdata('error_msg');
        $data['main_content'] = view('admin/manage_gallery/index', $data);

        // Load the list page view
        return view('dashboard', $data);
    }

    public function load($id)
    {
        $image = new Image();

        // Check whether id is not empty
        if (! empty($id)) {
            $data['image']        = $image->view($id);
            $data['page_title']   = 'View Image';
            $data['main_content'] = view('admin/manage_gallery/view', $data);

            return view('dashboard', $data);
        }

        return redirect()->to('/admin/manage_gallery/index');
    }

    public function add()
    {
        $data = [];

        // Public home for the gallery images (served over HTTP).
        $date      = date('y-m');
        $imagesRel = 'assets/img/gallery/' . $date;
        $imagesDir = FCPATH . $imagesRel;

        if (! is_dir($imagesDir)) {
            mkdir($imagesDir, 0775, true);
        }

        // If the add request is submitted
        if ($this->request->getMethod() == 'post') {
            // Validate submitted form data
            if (! $this->validate('admin_image_upload')) {
                $data['validation'] = $this->validator;
            } else {
                $image = $this->request->getFile('image');

                if ($image !== null && ! $image->isValid()) {
                    throw new RuntimeException($image->getErrorString() . '(' . $image->getError() . ')');
                }

                if ($image !== null && $image->isValid() && ! $image->hasMoved()) {
                    $image_title = (string) $this->request->getPost('title');
                    $ext         = $image->getExtension();
                    $new_name    = remove_invisible_characters($image_title) . '_' . rand(100, 999) . '_' . session()->get('id') . '.' . $ext;
                    $file_name   = str_replace(' ', '-', $new_name);

                    $image->move($imagesDir, $file_name);

                    if ($image->hasMoved()) {
                        $link = '/' . $imagesRel . '/' . $file_name;

                        // Uploaded file data
                        $imgData = [
                            'title'     => $this->request->getPost('title'),
                            'file_name' => $file_name,
                            'alt'       => $this->request->getPost('alt'),
                            'created'   => date('y/m/d'),
                            'html_link' => '<img src="' . $link . '" alt="' . $this->request->getPost('alt') . '">',
                            'link'      => $link,
                            'status'    => 1,
                        ];

                        $image_model     = new Image();
                        $data['result']  = $image_model->upload_images($imgData);

                        session()->setFlashdata('success_msg', 'Image has been uploaded successfully.');

                        return redirect()->to('/admin/manage_gallery/index');
                    }

                    session()->setFlashdata('error_msg', 'Some problems occurred, please try again.');
                }
            }
        }

        $data['page_title']   = 'Upload Image';
        $data['action']       = 'Upload';
        $data['main_content'] = view('admin/manage_gallery/add-edit', $data);

        // Load the add page view
        return view('dashboard', $data);
    }

    public function edit($id)
    {
        $image = new Image();

        // Get image data
        $data['image']        = $image->view($id);
        $data['page_title']   = 'Update Image';
        $data['action']       = 'Edit';
        $data['main_content'] = view('admin/manage_gallery/edit_image', $data);

        return view('dashboard', $data);
    }

    public function update_image($id)
    {
        $image = new Image();
        $title = $this->request->getPost('title');
        $alt   = $this->request->getPost('alt');

        $image_data = $image->view($id);

        if ($image_data === null) {
            session()->setFlashdata('error_msg', 'Image not found.');

            return redirect()->to('/admin/manage_gallery/index');
        }

        $original_file_name = $image_data->file_name;

        $ext       = substr(strrchr($image_data->file_name, '.'), 1);
        $file_name = str_replace(' ', '-', $title . '.' . $ext);

        // Keep the same directory the original file lives in.
        $imageRel = trim(dirname($image_data->link), '/');
        $imageDir = FCPATH . $imageRel;
        $link     = '/' . $imageRel . '/' . $file_name;

        $data = [
            'title'     => $title,
            'alt'       => $alt,
            'file_name' => $file_name,
            'html_link' => '<img src="' . $link . '" alt="' . $alt . '">',
            'link'      => $link,
        ];

        $oldPath = $imageDir . '/' . $original_file_name;
        $newPath = $imageDir . '/' . $file_name;

        if ($original_file_name !== $file_name && file_exists($oldPath) && ! file_exists($newPath)) {
            @rename($oldPath, $newPath);
        }

        $image->update_image($data, $id);

        session()->setFlashdata('success_msg', 'Image has been updated successfully.');

        return redirect()->to('/admin/manage_gallery/index');
    }

    public function block($id)
    {
        $image = new Image();

        // Check whether id is not empty
        if ($id) {
            // Update image status
            $update = $image->update_image(['status' => 0], $id);

            if ($update) {
                session()->setFlashdata('success_msg', 'Image has been blocked successfully.');
            } else {
                session()->setFlashdata('error_msg', 'Some problems occurred, please try again.');
            }
        }

        return redirect()->to('/admin/manage_gallery/index');
    }

    public function unblock($id)
    {
        $image = new Image();

        // Check whether id is not empty
        if ($id) {
            // Update image status
            $update = $image->update_image(['status' => 1], $id);

            if ($update) {
                session()->setFlashdata('success_msg', 'Image has been activated successfully.');
            } else {
                session()->setFlashdata('error_msg', 'Some problems occurred, please try again.');
            }
        }

        return redirect()->to('/admin/manage_gallery/index');
    }

    public function image_delete($id)
    {
        $image = new Image();

        // Check whether id is not empty
        if ($id) {
            // Delete gallery data, returns the stored link of the removed row.
            $link = $image->image_delete($id);

            if ($link > '') {
                // Remove the file from the public directory.
                $filePath = FCPATH . ltrim($link, '/');
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            session()->setFlashdata('success_msg', 'Image has been removed successfully.');
        } else {
            session()->setFlashdata('error_msg', 'Some problems occurred, please try again.');
        }

        return redirect()->to('/admin/manage_gallery/index');
    }
}
