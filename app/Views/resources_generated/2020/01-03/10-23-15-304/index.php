    <form class="needs-validation" id="questionsform" action="<?php echo $action?>" method="post" name="form">
    <div class="form-group" style="margin: auto">
        <div style="width: 100%;">
            <h3>Use this text box to give your sheet a custom name: <input class="form-control" type="text" placeholder="Group 1, Group 2, Magpies, Red Table" name="sheetName" /></h3>
            <br />
        </div>
        <div id="year3" style="">
            <?php @include "year3table.php"; ?>
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