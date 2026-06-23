<div class="main__cards">
    <div class="card">
        <div class="card__header">
            <div class="card__header-title text-light">Add <strong>Resource</strong>
                <!--<a href="#" class="card__header-link text-bold">View All</a>-->
            </div>
        </div>
        <div class="card__main">
            <!-- Insert content here -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">

                        <div class="container">
                            <h2>Gallery Images Management</h2>

                            <!-- Display status message -->
                            <?php if (!empty($success_msg)) { ?>
                                <div class="col-xs-12">
                                    <div class="alert alert-success"><?php echo $success_msg; ?></div>
                                </div>
                            <?php } elseif (!empty($error_msg)) { ?>
                                <div class="col-xs-12">
                                    <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                                </div>
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-12 head">
                                    <h5><?php echo $page_title; ?></h5>
                                    <!-- Add link -->
                                    <div class="float-right">
                                        <a href="<?php echo base_url('admin/manage_gallery/add'); ?>" class="btn btn-success"><i class="plus"></i> Upload Image</a>
                                    </div>
                                </div>

                                <!-- Data list table -->
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="10%"></th>
                                            <th width="20%">Title</th>
                                            <th width="10%">Alt</th>
                                            <th width="100%">Link</th>
                                            <th width="100%">HTML Link</th>
                                            <th width="9%">Created</th>
                                            <th width="8%">Status</th>
                                            <th width="18%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($gallery)) {
                                            $i = 0;
                                            foreach ($gallery as $row) {
                                                $i++;
                                                $image = !empty($row['file_name']) ? '<img src="' . $row['link'] . '" />' : '';
                                                $statusLink = ($row['status'] == 1) ? site_url('manage_gallery/block/' . $row['id']) : site_url('manage_gallery/unblock/' . $row['id']);
                                                $statusTooltip = ($row['status'] == 1) ? 'Click to Inactive' : 'Click to Active';
                                        ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $image; ?></td>
                                                    <td><?php echo $row['title']; ?></td>
                                                    <td><?php echo $row['alt']; ?></td>
                                                    <td><textarea><?php echo $row['link']; ?></textarea>
                                                    </td>
                                                    <td><textarea id="<?php echo 'image' . $row['id']; ?>"><?php echo $row['html_link']; ?></textarea><button onclick="copy('<?php echo 'image' . $row['id'] . "'"; ?>)" class="btn btn-success">Copy</button>
                                                        <div class="snackbar" id="snackbarimage<?php echo $row['id']; ?>">Link copied</div>
                                                    </td>
                                                    <td><?php echo $row['created']; ?></td>
                                                    <td><a href="<?php echo $statusLink; ?>" title="<?php echo $statusTooltip; ?>"><span class="badge <?php echo ($row['status'] == 1) ? 'badge-success' : 'badge-danger'; ?>"><?php echo ($row['status'] == 1) ? 'Active' : 'Inactive'; ?></span></a></td>
                                                    <td>
                                                        <a href="<?php echo '/admin/manage_gallery/load/' . $row['id']; ?>" class="btn btn-primary">view</a>
                                                        <a href="<?php echo base_url('/admin/manage_gallery/edit/' . $row['id']); ?>" class="btn btn-warning">edit</a>
                                                        <a href="<?php echo base_url('/admin/manage_gallery/image_delete/' . $row['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete data?')?true:false;">delete</a>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td colspan="6">No image(s) found...</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function copy(link) {
        /* Get the text field */
        var copyText = document.getElementById(link);

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

        function snackbar(link) {
            // Get the snackbar DIV
            link = 'snackbar' + link;
            var x = document.getElementById(link);

            // Add the "show" class to DIV
            x.className = "show";

            // After 3 seconds, remove the show class from DIV
            setTimeout(function() {
                x.className = "snackbar";
            }, 3000);
        }

        snackbar(link);
        /* Alert the copied text */
        //alert("Copied the text: " + copyText.value);
    }
</script>