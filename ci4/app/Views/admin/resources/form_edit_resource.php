<?php if (isset($deleted_successfully)) {
    echo $deleted_successfully;
    echo $result;
}
?>
<div class="col-sm-12">
    <div class="white-box">
        <h3 class="box-title m-b-0">Resources Table</h3>
        <p class="text-muted m-b-30">List of resources currently live</p>
        <div class="table-responsive">
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Author</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Excerpt</th>
                        <th>Description</th>
                        <th>Key Words</th>
                        <th>Year</th>
                        <th>Directory</th>
                        <th>Level</th>
                        <th>Date Added</th>
                        <th>Banner</th>
                        <th>Thumbnail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($resources as $row) {
                        echo
                            '<tr>' .
                                '<td>' . $row->resource_author . '</td>' .
                                '<td><a href="' . base_url('admin/resources/edit_resource/' . $row->id) . '">' . $row->resource_name . '</a></td>' .
                                '<td>' . $row->slug . '</td>' .
                                '<td>' . $row->resource_excerpt . '</td>' .
                                '<td>' . $row->resource_description . '</td>' .
                                '<td>' . $row->keywords . '</td>' .
                                '<td>' . $row->year . '</td>' .
                                '<td>' . $row->link . '</td>' .
                                '<td>' . $row->level . '</td>' .
                                '<td>' . $row->DateAdded . '</td>' .
                                '<td><img style="max-width: 100px" src="' . base_url($row->resource_banner) . '"><br>' . $row->resource_banner . '</td>' .
                                '<td><img style="max-width: 100px" src="' . base_url($row->resource_thumb) . '"><br>' . $row->resource_thumb . '</td>' .
                                '</tr>';
                    } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>