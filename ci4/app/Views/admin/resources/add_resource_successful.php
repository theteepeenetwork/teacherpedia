<style>
    .code {
        height: 500px;
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
                        <h3 class="box-title m-b-0">Form Upload Success</h3>
                        <p class="text-muted m-b-30 font-13">Please check the details carefully to ensure your resource has loaded successfully.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Directory Name: </h3><?php echo $report['directory']; ?></h3>
                        <br>
                        <p class="text-muted m-b-30 font-13">Directory Contents</p>
                        <?php
                        foreach ($report['contents'] as $row) {
                            echo $row . '<br/>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Keywords: </h3>
                        <p class="text-muted m-b-30 font-13">Existing Keywords</p>
                        <?php
                        if (isset($existing_keywords) && sizeof($existing_keywords) > 0) {
                            foreach ($existing_keywords as $row) {
                                echo $row . '<br/>';
                            }
                        } else {
                            echo "No existing keywords used";
                        }

                        ?></p>
                        <p class="text-muted m-b-30 font-13">Added Keywords</p>
                        <?php
                        if (isset($added_keywords) && sizeof($added_keywords) > 0) {
                            foreach ($added_keywords as $row) {
                                echo $row . '<br/>';
                            }
                        } else {
                            echo "No new keywords added";
                        }
                        ?></p>
                    </div>
                </div>
            </div>

            <!--end card content -->

        </div>
    </div>