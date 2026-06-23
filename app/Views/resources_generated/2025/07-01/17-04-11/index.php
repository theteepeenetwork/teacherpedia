<head>

</head>
<div class="container">
    <h1>Arithmetic questions</h1>
    <form class="needs-validation" id="questionsform" action="<?php echo base_url();?>resources/loadSheet/y6WorksheetGenerator/generator" method="post" name="form">
    <div class="form-group" style="margin: auto">
        <p>Select the year group and add questions. To add questions from another year, just change year and add more.</p>
        <div style="width: 100%;">
            Worksheet Title: <input class="form-control" type="text" placeholder="Mental Starter" name="sheetName" />
            <br />
        </div>
        <div id="year1" style="display:none">
        </div>
        <div id="year2" style="display:none">
        </div>
        <div id="year3" style="display:none">
            <?php @include "year3table.html"; ?>
        </div>
        <div id="year4" style="display:none">
            <?php @include "year4table.html"; ?>
        </div>
        <div id="year5" style="display:none">
            <?php @include "year5table.html"; ?>
        </div>
        <div id="year6" style="">
            <?php @include "year6table.php"; ?>
        </div>
        <br/>
        <button type="submit">Submit</button>
    </div>
    <br/>
    <input type="reset" value="Reset" /><p>Press reset to clear all questions from all year groups. </p>
    </form>
</div>
