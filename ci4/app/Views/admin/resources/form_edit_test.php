<head>
    <!-- codemirror -->
    <link rel="stylesheet" type="text/css" href="/assets/codemirror/lib/codemirror.css">
    <script src="/assets/codemirror/lib/codemirror.js"></script>
    <!-- end code mirror -->
    <script src="/assets/codemirror/lib/codemirror.js"></script>
    <link rel="stylesheet" href="/assets/codemirror/lib/codemirror.css">
    <script src="/assets/codemirror/mode/javascript/javascript.js"></script>
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

            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Edit Resource</h3>
                        <p class="text-muted m-b-30 font-13"> Edit live resources</p>

                        <form class="form-horizontal" id="uploadForm" action="/admin/resources/update_test_resource/<?php echo $row->id ?>" method="post" name="form" enctype="multipart/form-data">
                            <fieldset>
                                <!-- Form Name -->
                                <legend>Edit Resource</legend>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="control-label" for="resource_name">Resource Name</label>
                                    <div class="">
                                        <input id="resource_name" name="resource_name" type="text" placeholder="" class="form-control input-md" value="<?php echo $row->resource_name ?>" required>
                                        <span class="help-block">Name of the resource that is being uploaded</span>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <label class="control-label" for="resource_description">Resource Description</label>
                                    <div class="">
                                        <textarea class="form-control" id="resource_description" name="resource_description"> <?php echo $row->resource_description ?></textarea>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <label class="control-label" for="resource_excerpt">Resource Excerpt</label>
                                    <div class="">
                                        <textarea class="form-control" id="resource_description" name="resource_description"> <?php echo $row->resource_excerpt ?></textarea>
                                    </div>
                                </div>
                                <!-- Multiple Checkboxes (inline) -->
                                <div class="form-group">
                                    <label class="control-label" for="year_group">Year Group</label>
                                    <div class="">
                                        <div>Current year group = <?php echo $row->year ?></div>

                                        <select name="year_group">
                                            <option <?php echo ($row->year == 'EYFS') ? "selected" : ""; ?> value="EYFS">EYFS</option>
                                            <option <?php echo ($row->year == '1') ? "selected" : ""; ?> value="1">1</option>
                                            <option <?php echo ($row->year == '2') ? "selected" : ""; ?> value="2">2</option>
                                            <option <?php echo ($row->year == '3') ? "selected" : ""; ?> value="3">3</option>
                                            <option <?php echo ($row->year == '4') ? "selected" : ""; ?> value="4">4</option>
                                            <option <?php echo ($row->year == '5') ? "selected" : ""; ?> value="5">5</option>
                                            <option <?php echo ($row->year == '6') ? "selected" : ""; ?> value="6">6</option>
                                            <option <?php echo ($row->year == 'KS1') ? "selected" : ""; ?> value="KS1">KS1</option>
                                            <option <?php echo ($row->year == 'KS2') ? "selected" : ""; ?> value="KS2">KS2</option>
                                            <option <?php echo ($row->year == 'LKS2') ? "selected" : ""; ?> value="LKS2">LKS2</option>
                                            <option <?php echo ($row->year == 'UKS2') ? "selected" : ""; ?> value="UKS2">UKS2</option>
                                            <option <?php echo ($row->year == 'All') ? "selected" : ""; ?> value="All">All</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- index.php -->

                                <?php echo $files ?>



                                <!-- Button -->

                                <div class="form-group">
                                    <label class="control-label" for="submit">View Test</label>
                                    <div class="">
                                        <button class="btn btn-primary"><?php echo '<a style="color: white" target="_blank" href="resources/load/' . $row->link . '/' . $row->id . '>' . "View" . '</a>' ?></button>

                                    </div>
                                </div>
                                <div class="form-group">

                                    <label class="control-label" for="submit">Update</label>
                                    <div class="">
                                        <button id="submit" name="submit" class="btn btn-primary">Update</button>

                                    </div>
                                </div>
                                <!--<div class="form-group">
                        <label class="col-md-4 control-label" for="submit">Submit</label>
                        <div class="col-md-4">
                            <button id="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>-->
                            </fieldset>
                            <br />
                            <br />
                        </form>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="">Delete Resource</label>
                            <div class="col-md-4">
                                <a href="<?php echo base_url() . 'admin/resources/test_confirm/' . $row->id; ?>">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end card content -->
        </div>
    </div>
</div>