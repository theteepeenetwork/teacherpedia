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
            <form class="form-horizontal" id="uploadForm" action="/admin/resources/do_uploads" method="post" name="form" enctype="multipart/form-data">
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
                        <label class=" control-label" for="resource_name">Resource Name</label>
                        <div class="">
                            <input id="resource_name" name="resource_name" type="text" placeholder="" class="form-control input-md" required>
                            <span class="help-block">Name of the resource that is being uploaded</span>
                        </div>
                    </div>
                    <!-- Textarea -->
                    <div class="form-group">
                        <label class=" control-label" for="resource_description">Resource Description</label>
                        <div class="">
                            <textarea class="form-control" id="resource_description" name="resource_description" required></textarea>
                        </div>
                    </div>

                    <!--Resource excerpt-->
                    <div class="form-group">
                        <label class=" control-label" for="resource_excerpt">Resource Excerpt</label>
                        <div class="">
                            <textarea class="form-control" id="resource_excerpt" name="resource_excerpt" required></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class=" control-label" for="keywords">Keywords/hashtags</label>
                        <div class="">
                            <textarea class="form-control" id="keywords" name="keywords" required></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class=" control-label" for="year">Year Group</label>
                        <div class="">
                            <select name="year" required>
                                <option value="EYFS">EYFS</option>
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
                        <label class="control-label">Category</label>
                        <div>
                            <label>Keystage</label>
                            <select name="keystage" class='keystage' required>
                                <option></option>
                                <?php
                                foreach ($category_keystage->getResult() as $row) {
                                    echo '<option value=' . $row->id . '>' . $row->title . '</option>';
                                }
                                ?>
                            </select>
                            <div>
                                <label>Subject:</label>
                                <select name="subjects" class="subjects" required>
                                    <option></option>
                                </select>
                            </div>
                            <div>
                                <label>Topic:</label>
                                <select name="topics" class="topics" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Multiple Radios (inline) -->
                    <div class="form-group">
                        <label class=" control-label" for="subscriber_level">Subscriber Level</label>
                        <div class="">
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

                    <!-- Form Code -->
                    <div class="form-group">
                        <label class=" control-label" for="form_name">Form Name (must be index.php)</label>
                        <div class="">
                            <input id="form_name" name="form_name" type="text" placeholder="index.php" class="form-control input-md">
                            <span class="help-block">This name is automatically set to index.php. It is the first view loaded by the customer when viewing this resource. It should contain the form that the customer can select their content on before generating their resource.</span>
                        </div>
                        <label class=" control-label " for="form_code">Form Code</label>
                        <div class="">
                            <textarea class="form-control" rows="25" id="form_code" name="form_code"></textarea>
                        </div>
                    </div>

                    <!-- Action Code -->
                    <div class="form-group">
                        <label class=" control-label" for="action_name">Form Action Name</label>
                        <div class="">
                            <input id="action_name" name="action_name" type="text" placeholder="" class="form-control input-md">
                            <span class="help-block">This must be the file that is called by the action in your form.</span>
                        </div>
                        <label class=" control-label" for="generator">Form Action Code</label>
                        <div class="">
                            <textarea class="form-control" rows="25" id="generator" name="generator"></textarea>
                        </div>
                    </div>

                    <!-- Accordian containing optional code -->
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Optional Supporting Code 1
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class=" control-label" for="supporting_code1_name">Supporting Code Name 1</label>
                                        <div class="">
                                            <input id="supporting_code1_name" name="supporting_code1_name" type="text" placeholder="" class="form-control input-md">
                                            <span class="help-block">For your 'includes' or libraries.</span>
                                        </div>
                                        <label class=" control-label" for="supporting_code1">Supporting Code 1</label>
                                        <div class="">
                                            <textarea class="form-control" rows="25" id="supporting_code1" name="supporting_code1"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Optional Supporting Code 2
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class=" control-label" for="supporting_code2_name">Supporting Code Name 2</label>
                                        <div class="">
                                            <input id="supporting_code2_name" name="supporting_code2_name" type="text" placeholder="" class="form-control input-md">
                                            <span class="help-block">For your 'includes' or libraries.</span>
                                        </div>
                                        <label class=" control-label" for="supporting_code2">Supporting Code 2</label>
                                        <div class="">
                                            <textarea class="form-control" rows="25" id="supporting_code2" name="supporting_code2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Optional Supporting Code 3
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class=" control-label" for="supporting_code3_name">Supporting Code Name 3</label>
                                    <div class="">
                                        <input id="supporting_code3_name" name="supporting_code3_name" type="text" placeholder="" class="form-control input-md">
                                        <span class="help-block">For your 'includes' or libraries.</span>
                                    </div>
                                    <label class=" control-label" for="supporting_code3">Supporting Code 3</label>
                                    <div class="">
                                        <textarea class="form-control" rows="25" id="supporting_code3" name="supporting_code3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br />
                    <!-- Upload Box - edit to be for banner and thumb -->
                    <div class="form-group">
                        <label class=" control-label" for="banner">Banner Image</label>
                        <div class="col-md-5">
                            <!--<input type="file" name="userfiles[]" size="20" multiple />-->
                            <input type="file" name="banner" required>

                        </div>
                    </div>

                    <!-- Upload Box - edit to be for banner and thumb -->
                    <div class="form-group">
                        <label class=" control-label" for="thumb">Thumb Image</label>
                        <div class="col-md-5">
                            <!--<input type="file" name="userfiles[]" size="20" multiple />-->
                            <input type="file" name="thumb" required>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class=" control-label" for="thumb">Other</label>
                        <div class="col-md-5">
                            <!--<input type="file" name="userfiles[]" size="20" multiple />-->
                            <input type="file" name="others" id="fileToUpload">

                        </div>
                    </div>

                    <!-- Button -->
                    <div class="form-group">
                        <label class=" control-label" for="submit">Submit</label>
                        <div class="">
                            <button id="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!--end card content -->
        </div>
        </fieldset>
        <br />
        <br />
        </form>

        <script>
            //Get subject
            $(document).ready(function() {
                $("select.keystage").on("change", function() {
                    console.log("keystage change");
                    var selectedKeystage = $(".keystage option:selected").val();
                    console.log("Keystage = " + selectedKeystage);
                    $.ajax({
                        type: "POST",
                        url: "/admin/resources/get_subjects",
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
                        url: "/admin/resources/get_topics",
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



    </div>
</div>

<!--
<div class="card">
    <div class="card__header">
        <div class="card__header-title text-light">Recent <strong>Documents</strong>
            <a href="#" class="card__header-link text-bold">View All</a>
        </div>
        <div class="settings">
            <div class="settings__block"><i class="fas fa-edit"></i></div>
            <div class="settings__block"><i class="fas fa-cog"></i></div>
        </div>
    </div>
    <div class="card">
        <div class="documents">
            <div class="document">
                <div class="document__img"></div>
                <div class="document__title">tesla-patents</div>
                <div class="document__date">07/16/2018</div>
            </div>
            <div class="document">
                <div class="document__img"></div>
                <div class="document__title">yearly-budget</div>
                <div class="document__date">09/04/2018</div>
            </div>
            <div class="document">
                <div class="document__img"></div>
                <div class="document__title">top-movies</div>
                <div class="document__date">10/10/2018</div>
            </div>
            <div class="document">
                <div class="document__img"></div>
                <div class="document__title">trip-itinerary</div>
                <div class="document__date">11/01/2018</div>
            </div>
        </div>
    </div>
</div>
<div class="card card--finance">
    <div class="card__header">
        <div class="card__header-title text-light">Monthly <strong>Spending</strong>
            <a href="#" class="card__header-link text-bold">View All</a>
        </div>
        <div class="settings">
            <div class="settings__block"><i class="fas fa-edit"></i></div>
            <div class="settings__block"><i class="fas fa-cog"></i></div>
        </div>
    </div>
    <div id="chartdiv"></div>
</div>
</div>
                            -->