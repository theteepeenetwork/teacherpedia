<style>
    body {
        margin: auto;
    }



    table {
        width: 100%;
    }

    .my-form {
        text-align: center;
        margin: 0 auto;
    }
</style>
<div>
    <h3>Choose the tables</h3>
    <div id="warning">

    </div>
    <div>
        <form class=" form-group" name="xtableselector" action="<?php echo $action; ?>" onsubmit="return validateForm()" method="post">
            <div class="this-container">
                <div class="row">
                    <div class="col-sm">
                        <input class="form-check-input " type="checkbox" name="multipliers[]" id="2" value="2"> 2x <br />
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="3" value="3"> 3x <br />
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="4" value="4"> 4x <br />
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="5" value="5"> 5x <br />
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="6" value="6"> 6x <br />
                    </div>
                    <div class="col-sm">
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="7" value="7"> 7x <br />
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="8" value="8"> 8x <br />
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="9" value="9"> 9x <br />
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="10" value="10"> 10x <br />
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="11" value="11"> 11x <br />
                    </div>
                    <div class="col-sm">
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="12" value="12"> 12x <br />
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="20" value="20"> 20x <br />
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="100" value="100"> 100x <br />
                        <input class="form-check-input" type="checkbox" name="multipliers[]" id="1000" value="1000"> 1000x <br />
                    </div>
                </div>
                <br />
            </div>
            <button class="btn btn-primary btn-lg" type="submit">Go!</button>
    </div>
    </form>
    <br />

</div>
</div>
<script>


</script>

</div>