<head>


</head>

<h1>Multi-Objective Generator</h1>
<form class="needs-validation" id="questionsform" action="<?php echo $action ?>" method="post" name="form">
    <input class="form-group" style="margin: auto">
    <p>The form below contains all number and place value objectives. Select up to 25 questions from the dropdown buttons next to each objective. Submit at the bottom to generate a worksheet with infinitely renewable quesitons. </p>

    <div style="width: 100%;">
        <label for="sheetName">1. Worksheet Title: </label>
        <input class="form-control" type="text" placeholder="Mental Starter" name="sheetName" />
        <br />
    </div>

    <label for="answer_array">2. Include answers on question sheet? (Do not show if selecting missing number questions)</label>
    <br />
    <input type="radio" name="answer_array" value="yes">
    <label for="yes">Yes</label>
    <input type="radio" name="answer_array" value="no" default>
    <label for="no">No</label>
    <br />
    <label>3. Select Year 5 objectives</label>
    <div id="year5">
        <?php @include "year5table.php"; ?>
    </div>
    <br />
    <button type="submit">Submit</button>

    <br />
    <input type="reset" value="Reset" />
    <p>Press reset to clear all questions from all year groups. </p>
</form>
</div>