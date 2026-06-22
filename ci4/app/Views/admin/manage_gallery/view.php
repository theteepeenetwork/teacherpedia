<style>
    img {
        max-width: 60vh;
    }
</style>

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
                        <h5><?php //echo !empty($image['title']) ? $image['title'] : ''; 
                            ?></h5>
                        <?php //if (!empty($image['file_name'])) { 
                        ?>
                        <div class="img-box">
                            <img src="<?php echo $image->link; ?>">
                        </div>
                        <?php //} 
                        ?>
                    </div>
                    <a href="<?php echo base_url('admin/manage_gallery'); ?>" class="btn btn-primary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
    <!--end card content -->
</div>
</div>
</div>