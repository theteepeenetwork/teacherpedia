    <form class="needs-validation form-group" id="questionsform" action="<?php echo $action; ?>" method="post" name="form">

    <div class="form-group row">
        <label for="question" class="col-sm-2 col-form-label col-form-label-lg">Step 1:</label>
        <label for="question" class="col-sm-4 col-form-label col-form-label-lg">(Optional) Enter a question:</label>
        <div class="col-sm-6">
            <input class="form-control form-control-lg" id="question" type="text" name="question" maxlength="60" placeholder="Example - Why was Mr Mushroom so popular?" /><br /><br />
        </div>
    </div>
        
        
        
    <div class="form-group row">
        <label for="code" class="col-sm-2 col-form-label col-form-label-lg">Step 2:</label>
        <label for="code" class="col-sm-4 col-form-label col-form-label-lg">Enter the code to crack (8 word limit)</label>
        <div class="col-sm-6">
            <input class="form-control form-control-lg" type="text" name="code" maxlength="60" id="code" placeholder="He was a fungi to know" required /><br /><br />
        </div>
    </div>
    
    <div class="form-group row">
        <label for="top" class="col-sm-2 col-form-label col-form-label-lg">Step 3:<br /></label>
        <label for="top" class="col-sm-4 col-form-label col-form-label-lg">Choose the maximum number of digits for the top number.<br /></label>
        
        <div class="col-sm-6">
            <table class="table table-striped" id="top">
              <tr>
                <td><input type="radio" name="topNumber" value="1"> 1</td>
                <td><input type="radio" name="topNumber" value="2"> 2</td>
                <td><input type="radio" name="topNumber" value="3"> 3</td>
                <td><input type="radio" name="topNumber" value="4"> 4</td>
                <td><input type="radio" name="topNumber" value="5"> 5</td>
                <td><input type="radio" name="topNumber" value="6"> 6</td>
                <td><input type="radio" name="topNumber" value="7"> 7</td>
              </tr>
            </table>
        </div>
    </div>

    <div class="form-group row">
        <label for="bottom" class="col-sm-2 col-form-label col-form-label-lg">Step 4:<br /></label>
        <label for="bottom" class="col-sm-4 col-form-label col-form-label-lg">Choose the maximum number of digits for the bottom number.<br /></label>
        <div class="col-sm-6">
            <table class="table table-striped" id="bottom">
            <tr>
                <td><input type="radio" name="bottomNumber" value="1"> 1</td>
                <td><input type="radio" name="bottomNumber" value="2"> 2</td>
                <td><input type="radio" name="bottomNumber" value="3"> 3</td>
                <td><input type="radio" name="bottomNumber" value="4"> 4</td>
                <td><input type="radio" name="bottomNumber" value="5"> 5</td>
                <td><input type="radio" name="bottomNumber" value="6"> 6</td>
                <td><input type="radio" name="bottomNumber" value="7"> 7</td>
             </tr>
          </table>
    </div>

        <br/>
        <button type="submit" class="btn btn-lg btn-primary">Submit</button>
    </div>
    <br/>
    </form>
