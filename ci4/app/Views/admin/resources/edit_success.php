<div class="main__cards">
    <div class="card">
        <div class="card__header">
            <div class="card__header-title text-light"><?php echo $page_title ?></strong>
                <!--<a href="#" class="card__header-link text-bold">View All</a>-->
            </div>
        </div>
        <div class="card__main">
            <!-- Insert content here -->
            <div class="col-sm-12">
                <div class="white-box">
                    <p> <?php echo "report: " . $report;?></p>
                    <p> <?php echo " Data: " . $data; ?></p>
                    <p> <?php echo " id: " . $id; ?></p>
                    <p> <?php echo " resource name: " . $resource_name; ?></p>
                    <p> <?php echo " result: " . var_dump($result); ?></p>
                    <p> <?php var_dump($data);  ?></p>
                </div>
            </div>
            <!--end card content -->
        </div>
    </div>
</div>

<?php echo($resource_name); ?>