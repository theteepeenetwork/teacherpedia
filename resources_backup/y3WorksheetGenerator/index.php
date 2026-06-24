    <form class="needs-validation" id="questionsform" action="<?php echo base_url();?>resources/loadSheet/y3WorksheetGenerator/generator" method="post" name="form">
    <div class="form-group" style="margin: auto">
        <div style="width: 100%;">
            <h3>Use this text box to give your sheet a custom name: <input class="form-control" type="text" placeholder="Group 1, Group 2, Magpies, Red Table" name="sheetName" /></h3>
            <br />
        </div>
        <div id="year1" style="display:none">
        </div>
        <div id="year2" style="display:none">
        </div>
        <div id="year3" style="">
            <?php include "year3table.html"; ?>
        </div>
        <div id="year4" style="display:none">
            <?php include "year4table.html"; ?>
        </div>
        <div id="year5" style="display:none">
            <?php include "year5table.html"; ?>
        </div>
        <div id="year6" style="display:none">
            <?php include "year6table.html"; ?>
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

    function y1() {
        hideall();
        document.getElementById("year1").style.display = "block";
        document.getElementById("year1table").checked = true;
    }

    function y2() {
        hideall();
        document.getElementById("year2").style.display = "block";
        document.getElementById("year2table").checked = true;
    }

    function y3() {

        hideall();
        document.getElementById("year3").style.display = "block";
        document.getElementById("year3table").checked = true;
    }

    function y4() {
        hideall();
        document.getElementById("year4").style.display = "block";
        document.getElementById("year4table").checked = true;
    }

    function y5() {
        hideall();
        document.getElementById("year5").style.display = "block";
        document.getElementById("year5table").checked = true;
    }

    function y6() {
        hideall();
        document.getElementById("year6").style.display = "block";
        document.getElementById("year6table").checked = true;
    }

    function hideall() {
        document.getElementById("year1").style.display = "none";
        document.getElementById("year2").style.display = "none";
        document.getElementById("year3").style.display = "none";
        document.getElementById("year4").style.display = "none";
        document.getElementById("year5").style.display = "none";
        document.getElementById("year6").style.display = "none";
        //document.getElementById("questionsform").reset();
    }


</script>