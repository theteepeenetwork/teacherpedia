<head>
<script type="text/javascript"  id="debugbar_loader" data-time="1588690384" src="http://localhost:8888/index.php?debugbar"></script><script type="text/javascript"  id="debugbar_dynamic_script"></script><style type="text/css"  id="debugbar_dynamic_style"></style>

<script type="text/javascript"  id="debugbar_loader" data-time="1588690369" src="http://localhost:8888/index.php?debugbar"></script><script type="text/javascript"  id="debugbar_dynamic_script"></script><style type="text/css"  id="debugbar_dynamic_style"></style>

<script type="text/javascript"  id="debugbar_loader" data-time="1588690297" src="http://localhost:8888/index.php?debugbar"></script><script type="text/javascript"  id="debugbar_dynamic_script"></script><style type="text/css"  id="debugbar_dynamic_style"></style>

<script type="text/javascript"  id="debugbar_loader" data-time="1588690159" src="http://localhost:8888/index.php?debugbar"></script><script type="text/javascript"  id="debugbar_dynamic_script"></script><style type="text/css"  id="debugbar_dynamic_style"></style>

<script type="text/javascript"  id="debugbar_loader" data-time="1588689792" src="http://localhost:8888/index.php?debugbar"></script><script type="text/javascript"  id="debugbar_dynamic_script"></script><style type="text/css"  id="debugbar_dynamic_style"></style>


</head>
<div class="container">
    <h1>Arithmetic questions</h1>
    <form class="needs-validation" id="questionsform" action="<?php echo $action?>" method="post" name="form">
    <div class="form-group" style="margin: auto">
        <p>Select the year group and add questions. To add questions from another year, just change year and add more.</p>
        <div style="width: 100%;">
            Worksheet Title: <input class="form-control" type="text" placeholder="Mental Starter" name="sheetName" />
            <br />
        </div>

        <div id="year4" style="">
            <?php @include "year4table.php"; ?>
        </div>
    
        <br/>
        <button type="submit">Submit</button>
    </div>
    <br/>
    <input type="reset" value="Reset" /><p>Press reset to clear all questions from all year groups. </p>
    </form>
</div>

<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        valthisform();
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();


</script>