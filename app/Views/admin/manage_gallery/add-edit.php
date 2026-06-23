<head>
    <title><?php echo $page_title; ?></title>
</head>



<div class="main__cards">
    <div class="card">
        <div class="card__header">
            <div class="card__header-title text-light">Add <strong>Resource</strong>
                <!--<a href="#" class="card__header-link text-bold">View All</a>-->
            </div>
        </div>
        <div class="card__main">
            <!-- Insert content here -->

            <div class="container">
                <h1><?php echo $page_title; ?></h1>
                <hr>

                <!-- Display status message -->
                <?php
                if (session('upload') !== NULL) {
                    foreach (session()->getFlashData('upload') as $row) {
                        echo $row . '<br />';
                    }
                }
                ?>

                <div class="row">
                    <div class="">
                        <form method="post" action="" enctype="multipart/form-data" name="image_upload">
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text" name="title" class="form-control" placeholder="" value="<?php if (isset($image)) {
                                                                                                                echo $image->title;
                                                                                                            } else {
                                                                                                                echo "";
                                                                                                            } ?>">
                                <?php //echo form_error('title', '<p class="help-block text-danger">', '</p>'); 
                                ?>
                            </div>
                            <div class=" form-group">
                                <label>Alt</label>
                                <p>Alt text needs to be releavant, succinct and not keyword heavy.</p>
                                <input type="text" name="alt" class="form-control" placeholder="" value="<?php if (isset($image)) {
                                                                                                                echo $image->alt;
                                                                                                            } else {
                                                                                                                echo "";
                                                                                                            } ?>">
                                <?php //echo form_error('title', '<p class="help-block text-danger">', '</p>'); 
                                ?>
                            </div>
                            <div class="form-group">
                                <label>Images:</label>
                                <p>Make sure the file is appropriaetly named. This will form the image's link and be important for SEO.</p>
                                <input type="file" name="image" class="form-control">
                            </div>

                            <a href="<?php echo base_url('manage_gallery'); ?>" class="btn btn-secondary">Back</a>
                            <input type="hidden" name="id" value="<?php //echo !empty($image['id']) ? $image['id'] : ''; 
                                                                    ?>">
                            <input type="submit" name="imgSubmit" class="btn btn-success" value="SUBMIT">
                        </form>
                        <br>
                    </div>
                </div>
            </div>

            <!--end card content -->
        </div>
    </div>
</div>