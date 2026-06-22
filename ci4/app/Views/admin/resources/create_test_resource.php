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
                        <h3 class="box-title m-b-0">Resource Upload Form</h3>
                        <p class="text-muted m-b-30 font-13"> Upload your resource to the live database </p>
                        <form class="form-horizontal" id="uploadForm" action="/admin/resources/create_test" method="post" name="form" enctype="multipart/form-data">
                            <fieldset>
                                <!-- Form Name -->
                                <legend>Upload Resource</legend>
                                <?php if (isset($dirName)) {
                                    echo $dirName;
                                    echo $exist;
                                }; ?>
                                <?php if (isset($exist)) {
                                    echo $exist;
                                }; ?>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="resource_name">Resource Name</label>
                                    <div class="col-md-5">
                                        <input id="resource_name" name="resource_name" type="text" placeholder="" class="form-control input-md" value="Test" required></input>
                                        <span class="help-block">Name of the resource that is being uploaded</span>
                                    </div>
                                </div>
                                <!-- Textarea -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="resource_description">Resource Description</label>
                                    <div class="col-md-4">
                                        <textarea class="form-control" id="resource_description" name="resource_description" required>Test Description</textarea>
                                    </div>
                                </div>
                                <!--Resource excerpt-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="resource_excerpt">Resource Excerpt</label>
                                    <div class="col-md-4">
                                        <textarea class="form-control" id="resource_excerpt" name="resource_excerpt" required>Test Excerpt</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="keywords">Keywords/hashtags</label>
                                    <div class="col-md-4">
                                        <textarea class="form-control" id="keywords" name="keywords">Test</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="year">Year Group</label>
                                    <div class="col-md-4">
                                        <select name="year" required>
                                            <option value="KS1">KS1</option>
                                            <option value="KS2">KS2</option>
                                            <option value="LKS2">LKS2</option>
                                            <option value="UKS2">UKS2</option>
                                            <option value="1">Year 1</option>
                                            <option value="2">Year 2</option>
                                            <option value="3">Year 3</option>
                                            <option value="4">Year 4</option>
                                            <option value="5">Year 5</option>
                                            <option value="6">Year 6</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Category</label>
                                    <div class="col-md-4">
                                        <label>Keystage</label>
                                        <select name="keystage" class='keystage' required>
                                            <option>Test</option>
                                        </select>
                                        <div>
                                            <label>Subject:</label>
                                            <select name="subjects" class="subjects" required>
                                                <option>Test</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label>Topic:</label>
                                            <select name="topics" class="topics" required>
                                                <option>Test</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Multiple Radios (inline) -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="subscriber_level">Subscriber Level</label>
                                    <div class="col-md-4">
                                        <label class="radio-inline" for="subscriber_level-0">
                                            <input type="radio" name="subscriber_level" id="subscriber_level-0" value="free" checked="checked"> Free
                                        </label>
                                        <label class="radio-inline" for="subscriber_level-1">
                                            <input type="radio" name="subscriber_level" id="subscriber_level-1" value="basic"> Basic
                                        </label>
                                        <label class="radio-inline" for="subscriber_level-2">
                                            <input type="radio" name="subscriber_level" id="subscriber_level-2" value="premium"> Premium
                                        </label>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="submit">Submit</label>
                                    <div class="col-md-4">
                                        <button id="submit" name="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                    </div>
                    </fieldset>
                    <br />
                    <br />
                    </form>
                </div>
            </div>
        </div>
        <script>
            //Get subject
            $(document).ready(function() {
                $("select.keystage").on("change", function() {
                    console.log("keystage change");
                    var selectedKeystage = $(".keystage option:selected").val();
                    console.log("Keystage = " + selectedKeystage);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>admin/resources/get_subjects",
                        data: {
                            keystage: selectedKeystage
                        }
                    }).done(function(data) {
                        $("select.subjects").html(data);
                    });
                });
            });

            //get topic
            $(document).ready(function() {
                $("select.subjects").on("change", function() {
                    console.log("subject change");
                    var selectedKeystage = $(".keystage option:selected").val();
                    var selectedSubject = $(".subjects option:selected").val();
                    console.log('SelectedSubject = ' + selectedSubject);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>admin/resources/get_topics",
                        data: {
                            subject: selectedSubject,
                            keystage: selectedKeystage
                        }
                    }).done(function(data) {
                        $("select.topics").html(data);
                    });
                });
            });
        </script>
        <!--end card content -->
    </div>
</div>
</div>