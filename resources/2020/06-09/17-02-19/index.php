<head>
    <style>
    .form-button {
        background-color: var(--theme-2);
        /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 25px;
        ;
    }
    </style>
</head>

<form class="needs-validation" id="questionsform" action="<?php echo $action ?>" method="post" name="form">
    <input class="form-group" style="margin: auto">
    <div style="width: 100%;">
        <label for="sheetName">1. Give your sheet a name (Optional): </label>
        <input class="form-control" type="text" placeholder="Mental Starter" name="sheetName" />
        <br />
    </div>

    <label for="answer_array">2. Include answers on question sheet? (Do not show if selecting missing number
        questions)</label>
    <br />
    <input type="radio" name="answer_array" value="yes">
    <label for="yes">Yes</label>
    <input type="radio" name="answer_array" value="no" default>
    <label for="no">No</label>
    <br />
    <label>3. Select Year 3 objectives</label>
    <div id="year4">
        <?php include "year3table.php"; ?>
    </div>
    <br />
    <button class="form-button" type="submit">Submit</button>

    <input class="form-button" type="reset" value="Reset" />
    <p>Press reset to clear all questions from all year groups. </p>
</form>
</div>