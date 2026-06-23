<?php

namespace App\Controllers\Admin;

use App\Models\ResourcesModel;
use DateTime;
use RuntimeException;

class Resources extends Admin_Controller
{
    public function index($page = 'main')
    {
        $data                 = [];
        $data['page_title']   = 'Dashboard';
        $data['main_content'] = view('admin/' . $page, $data);
        return view('dashboard', $data);
    }

    public function confirm($id)
    {
        $db                 = \Config\Database::connect();
        $data               = [];
        $data['row']        = $db->table('resources')->getWhere(['id' => $id])->getRow();
        $data['page_title'] = 'Confirm Delete';

        $data['main_content'] = view('admin/resources/form_edit_confirm', $data);
        return view('dashboard', $data);
    }

    public function add_resource($page = '')
    {
        $resources_db = new ResourcesModel();

        $data = [];
        $data['page_title']        = 'Add Resource';
        $data['category_keystage'] = $resources_db->load_keystage();
        $data['category_subject']  = $resources_db->load_subject();

        $data['main_content'] = view('admin/resources/add_resource' . $page, $data);
        return view('dashboard', $data);
    }

    public function list_resources()
    {
        $data               = [];
        $data['page_title'] = 'List of Resources';

        $resources_db      = new ResourcesModel();
        $data['resources'] = $resources_db->get_resources();

        $data['main_content'] = view('admin/resources/form_edit_resource', $data);
        return view('dashboard', $data);
    }

    public function edit_resource($id)
    {
        $data               = [];
        $data['page_title'] = 'Edit Resource';
        $string             = '';

        if ($id > 0) {
            $resources_db = new ResourcesModel();
            $query        = $resources_db->edit_resource($id);

            $path  = APPPATH . 'Views/' . ResourcesModel::viewBase($query->link);
            $files = is_dir($path) ? preg_grep('/^([^.])/', scandir($path)) : [];

            $codearea = 0;
            foreach ($files as $file) {
                $file_value = file_get_contents($path . '/' . $file);
                $string .= '<div class="form-group"><label class="" for="resource_description">' . $file . '</label><br><div class="col-md-12"><pre><textarea id="code' . $codearea . '" rows="400" cols="50" name="' . $file . '">' . esc($file_value) . '</textarea></pre></div></div><script>var editor = CodeMirror.fromTextArea(document.getElementById("code' . $codearea . '"), {lineNumbers: true,gutter: true,lineWrapping: true,});</script>';
                $codearea++;
            }

            $data['row'] = $query;
        }

        $data['files']        = $string;
        $data['main_content'] = view('admin/resources/form_edit', $data);
        return view('dashboard', $data);
    }

    public function delete_resource($id)
    {
        $resources_db = new ResourcesModel();
        $db           = \Config\Database::connect();

        $row = $db->table('resources')->getWhere(['id' => $id])->getRow();
        if ($row === null) {
            return redirect()->to('admin/resources/list_resources');
        }

        // Move the resource code out of the live view path into writable/deleted/
        $base = ResourcesModel::viewBase($row->link);
        $src  = APPPATH . 'Views/' . $base;
        $dest = WRITEPATH . 'deleted/' . $base;
        if (is_dir($src)) {
            if (! is_dir(dirname($dest))) {
                mkdir(dirname($dest), 0775, true);
            }
            @rename($src, $dest);
        }

        // Move the public banner/thumb directory out of the docroot too.
        if (! empty($row->resource_banner)) {
            $imgRel = trim(dirname($row->resource_banner), '/');
            $imgSrc = FCPATH . $imgRel;
            if (is_dir($imgSrc)) {
                $imgDest = WRITEPATH . 'deleted/' . $imgRel;
                if (! is_dir(dirname($imgDest))) {
                    mkdir(dirname($imgDest), 0775, true);
                }
                @rename($imgSrc, $imgDest);
            }
        }

        $archive = [
            'old_id'               => $row->id,
            'resource_name'        => $row->resource_name,
            'resource_author'      => $row->resource_author,
            'resource_description' => $row->resource_description,
            'resource_excerpt'     => $row->resource_excerpt,
            'link'                 => $row->link,
            'year'                 => $row->year,
            'level'                => $row->level,
            'resource_banner'      => $row->resource_banner,
            'resource_thumb'       => $row->resource_thumb,
            'slug'                 => $row->slug,
            'deleted_date'         => date('Y/m/d'),
            'deleted_by'           => session()->get('id'),
        ];

        $resources_db->delete_resource($archive, $row->id);

        return redirect()->to('admin/resources/list_resources');
    }

    /**
     * Create a new resource: write the author-supplied code files to the
     * canonical (non-public) resources view directory, upload the banner /
     * thumbnail images, record keywords, and insert the resource row so the
     * resource is immediately available and auto-generates on the public site.
     */
    public function do_uploads($page = '')
    {
        helper(['url', 'text', 'filesystem']);

        $request      = $this->request;
        $resources_db = new ResourcesModel();

        $slug        = url_title($request->getPost('resource_name'), '-', true);
        $date_object = new DateTime();
        $year        = date('Y');
        $month_day   = date('d-m');
        $time        = $date_object->format('H-i-s');

        // Canonical home for generated code (rendered through the view layer,
        // NOT served directly over HTTP).
        $relDir   = ResourcesModel::VIEW_BASE . '/' . $year . '/' . $month_day . '/' . $time;
        $writeDir = APPPATH . 'Views/' . $relDir;

        // Public home for the banner / thumbnail images (served over HTTP).
        $imagesRel = 'assets/img/banners_thumbnails/' . $year . '/' . $month_day . '/' . $time;
        $imagesDir = FCPATH . $imagesRel;

        if (! is_dir($writeDir) && ! mkdir($writeDir, 0775, true) && ! is_dir($writeDir)) {
            throw new RuntimeException('Unable to create resource directory: ' . $writeDir);
        }

        // ---- Clean file names ----
        $action_name           = $this->clean_file_names($request->getPost('action_name'));
        $supporting_code1_name = $this->clean_file_names($request->getPost('supporting_code1_name'));
        $supporting_code2_name = $this->clean_file_names($request->getPost('supporting_code2_name'));
        $supporting_code3_name = $this->clean_file_names($request->getPost('supporting_code3_name'));

        // ---- Write code files (once) ----
        $this->create_code_files($writeDir, 'index.php', (string) $request->getPost('form_code'));
        $this->create_code_files($writeDir, $action_name, (string) $request->getPost('generator'));

        foreach ([
            [$supporting_code1_name, $request->getPost('supporting_code1')],
            [$supporting_code2_name, $request->getPost('supporting_code2')],
            [$supporting_code3_name, $request->getPost('supporting_code3')],
        ] as [$name, $code]) {
            if (! empty($code)) {
                $this->create_code_files($writeDir, $name, (string) $code);
            }
        }

        // ---- Upload images ----
        $banner_name = '';
        $thumb_name  = '';
        $banner      = $request->getFile('banner');
        $thumb       = $request->getFile('thumb');

        if ($banner !== null && $banner->isValid() && ! $banner->hasMoved()) {
            if (! is_dir($imagesDir)) {
                mkdir($imagesDir, 0775, true);
            }
            $banner_name = $slug . '_banner.' . $banner->getExtension();
            $banner->move($imagesDir, $banner_name);
        }
        if ($thumb !== null && $thumb->isValid() && ! $thumb->hasMoved()) {
            if (! is_dir($imagesDir)) {
                mkdir($imagesDir, 0775, true);
            }
            $thumb_name = $slug . '_thumb.' . $thumb->getExtension();
            $thumb->move($imagesDir, $thumb_name);
        }

        // ---- Keywords ----
        $keywords_to_insert = '';
        $existing_keywords  = [];
        $keywordsInput      = (string) $request->getPost('keywords');
        if ($keywordsInput !== '') {
            $cleaned = [];
            foreach (explode(',', $keywordsInput) as $word) {
                $word = strtolower(trim($word));
                if ($word === '') {
                    continue;
                }
                $resources_db->upsert_keyword($word);
                $cleaned[]           = $word;
                $existing_keywords[] = $word;
            }
            $keywords_to_insert = implode(', ', $cleaned);
        }

        // ---- Insert resource row ----
        $toInsert = [
            'resource_name'        => $request->getPost('resource_name'),
            'resource_author'      => session()->get('id'),
            'resource_description' => $request->getPost('resource_description'),
            'resource_excerpt'     => $request->getPost('resource_excerpt'),
            'link'                 => $relDir,
            'keywords'             => $keywords_to_insert,
            'action'               => $action_name,
            'level'                => $request->getPost('subscriber_level'),
            'year'                 => $request->getPost('year'),
            'category'             => $request->getPost('keystage') . ',' . $request->getPost('subjects') . ',' . $request->getPost('topics'),
            'resource_banner'      => $banner_name !== '' ? '/' . $imagesRel . '/' . $banner_name : '',
            'resource_thumb'       => $thumb_name !== '' ? '/' . $imagesRel . '/' . $thumb_name : '',
            'slug'                 => $slug,
            'dateAdded'            => date('Y/m/d'),
        ];

        $resources_db->upload_resource($toInsert);

        // ---- Report ----
        $data                      = [];
        $data['page_title']        = 'Resource added';
        $data['existing_keywords'] = $existing_keywords;
        $data['added_keywords']    = $existing_keywords;
        $data['report']            = [
            'directory'      => $relDir,
            'contents'       => array_values(array_diff(scandir($writeDir), ['.', '..'])),
            'action_name'    => $action_name,
            'index_created'  => 'index.php created',
            'action_created' => $action_name . ' created',
        ];

        $data['main_content'] = view('admin/resources/add_resource_successful', $data);
        return view('dashboard', $data);
    }

    public function update_resource($id)
    {
        $resources_db = new ResourcesModel();

        // Update the database fields.
        $to_update = [
            'resource_name'        => $this->request->getPost('resource_name'),
            'resource_description' => $this->request->getPost('resource_description'),
            'year'                 => $this->request->getPost('year_group'),
        ];
        $update = $resources_db->update_resource($to_update, $id);

        // Update the code files. The edit form names each textarea after its
        // file; PHP mangles dots/spaces in POST keys to underscores, so match
        // each real file against its mangled key.
        $query = $resources_db->edit_resource($id);
        $path  = APPPATH . 'Views/' . ResourcesModel::viewBase($query->link);
        $files = is_dir($path) ? preg_grep('/^([^.])/', scandir($path)) : [];

        foreach ($files as $file) {
            $mangled = str_replace(['.', ' '], '_', $file);
            $content = $this->request->getPost($mangled);
            if ($content !== null) {
                file_put_contents($path . '/' . $file, $content);
            }
        }

        $data                 = [];
        $data['id']           = $id;
        $data['page_title']   = $update ? 'Successful Edit' : 'Something went wrong';
        $data['report']       = $update ? 'Successful Edit' : 'Update failed';
        $data['main_content'] = view('admin/resources/edit_success', $data);
        return view('dashboard', $data);
    }

    public function get_subjects()
    {
        $resources_db = new ResourcesModel();
        if ($this->request->getPost('keystage')) {
            $keystage = $this->request->getPost('keystage');
            $query    = $resources_db->load_subject($keystage);

            echo '<option>-</option>';
            foreach ($query->getResult() as $value) {
                echo '<option value="' . esc($value->id, 'attr') . '">' . esc($value->title) . '</option>';
            }
        }
    }

    public function get_topics()
    {
        $resources_db = new ResourcesModel();
        if ($this->request->getPost('subject')) {
            $subject = $this->request->getPost('subject');
            $query   = $resources_db->load_topics($subject);

            echo '<option>-</option>';
            foreach ($query->getResult() as $value) {
                echo '<option value="' . esc($value->id, 'attr') . '">' . esc($value->title) . '</option>';
            }
        }
    }

    /**
     * Force a clean, safe, single-segment PHP file name.
     */
    private function clean_file_names($name): string
    {
        $name = basename(trim((string) $name)); // strip any path components
        $base = explode('.', $name)[0];
        $base = preg_replace('/[^A-Za-z0-9_\-]/', '', $base);
        if ($base === '' || $base === null) {
            $base = 'file';
        }
        return $base . '.php';
    }

    /**
     * Write author-supplied code to a file inside the resource directory.
     */
    private function create_code_files(string $dir, string $file_name, string $form_code): bool
    {
        $path = rtrim($dir, '/') . '/' . ltrim(basename($file_name), '/');
        return file_put_contents($path, $form_code) !== false;
    }
}
