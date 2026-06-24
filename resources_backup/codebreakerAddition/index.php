    <form class="needs-validation" id="questionsform" action="<?php echo base_url();?>resources/loadSheet/codebreakerAddition/generator" method="post" name="form">
    Enter the code to crack (8 word limit)<input style="width: 100%"type="text" name="code" maxlength="30" /><br />     
    
    Choose the maximum number of digits for the top number.<br />
        <table style="width:100%">
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

        Choose the maximum number of digits for the bottom number. <br />
        <table style="width:100%">
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

        <br/>
        <button type="submit">Submit</button>
    </div>
    <br/>
    </form>
