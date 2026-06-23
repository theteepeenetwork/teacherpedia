    <form class="needs-validation" id="questionsform" action="<?php echo $action ?>" method="post" name="form">
        <h3>Step 1: Choose the maximum number of digits for the top number.</h3><br />
        <table style="width:100%">
          <tr>
            <td><input type="radio" name="topNumber" value="2" onclick="hide()"> 2</td>
            <td><input type="radio" name="topNumber" value="3" onclick="hide()"> 3</td>
            <td><input type="radio" name="topNumber" value="4" onclick="hide()"> 4</td>
            <td><input type="radio" name="topNumber" value="5" onclick="hide()"> 5</td>
            <td><input type="radio" name="topNumber" value="6" onclick="hide()"> 6</td>
            <td><input type="radio" name="topNumber" value="7" onclick="hide()"> 7</td>
          </tr>
        </table>
        <br />
        <div id="bottomNumberTable" style="display: none">
        <h3>Step 2: Choose the maximum number of digits for the bottom number. </h3><br />
        <table style="width:100%">
        <tr>
            <td><div id="bottom1"><input type="radio" name="bottomNumber" value="1" onclick="showMissingSelect()"> 1</div></td>
            <td><div id="bottom2"><input type="radio" name="bottomNumber" value="2" onclick="showMissingSelect()"> 2</div></td>
            <td><div id="bottom3"><input type="radio" name="bottomNumber" value="3" onclick="showMissingSelect()"> 3</div></td>
            <td><div id="bottom4"><input type="radio" name="bottomNumber" value="4" onclick="showMissingSelect()"> 4</div></td>
            <td><div id="bottom5"><input type="radio" name="bottomNumber" value="5" onclick="showMissingSelect()"> 5</div></td>
            <td><div id="bottom6"><input type="radio" name="bottomNumber" value="6" onclick="showMissingSelect()"> 6</div></td>
            <td><div id="bottom7"><input type="radio" name="bottomNumber" value="7" onclick="showMissingSelect()"> 7</div></td>

          </tr>
      </table>
      </div>
      <div id="missingNumber" style="display: none;">
      <br />
  
      <h3>Step 3: Choose the maximum numbers that should be missing from each question.</h3>
      <table style="width:100%">
        <tr>
          <select name="remove">
            <option value="1">1</option>
            <option style="display: none" id="select2" value="2">2</option>
            <option style="display: none" id="select3" value="3">3</option>
            <option style="display: none" id="select4" value="4">4</option>
            <option style="display: none" id="select5" value="5">5</option>
          </select>
        </tr>
      </table>

        <br/>
        <button type="submit">Submit</button>
    </div>
    <br/>
    </form>

<script>
  if(performance.navigation.type == 2){
   location.reload(true);
}
// Example starter JavaScript for disabling form submissions if there are invalid fields
function hide(){
var top  = document.querySelector('input[name="topNumber"]:checked').value;
  switch(top) {
    case "2":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('bottom2').style.display = 'unset';
    document.getElementById('bottom3').style.display = 'none';
    document.getElementById('bottom4').style.display = 'none';
    document.getElementById('bottom5').style.display = 'none';
    document.getElementById('bottom6').style.display = 'none';
    document.getElementById('bottom7').style.display = 'none';
      break;
      case "3":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('bottom2').style.display = 'unset';
    document.getElementById('bottom3').style.display = 'unset';
    document.getElementById('bottom4').style.display = 'none';
    document.getElementById('bottom5').style.display = 'none';
    document.getElementById('bottom6').style.display = 'none';
    document.getElementById('bottom7').style.display = 'none';
      break;
      case "4":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('bottom2').style.display = 'unset';
    document.getElementById('bottom3').style.display = 'unset';
    document.getElementById('bottom4').style.display = 'unset';
    document.getElementById('bottom5').style.display = 'none';
    document.getElementById('bottom6').style.display = 'none';
    document.getElementById('bottom7').style.display = 'none';
      break;
      case "5":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('bottom2').style.display = 'unset';
    document.getElementById('bottom3').style.display = 'unset';
    document.getElementById('bottom4').style.display = 'unset';
    document.getElementById('bottom5').style.display = 'unset';
    document.getElementById('bottom6').style.display = 'none';
    document.getElementById('bottom7').style.display = 'none';
      break;
      case "6":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('bottom2').style.display = 'unset';
    document.getElementById('bottom3').style.display = 'unset';
    document.getElementById('bottom4').style.display = 'unset';
    document.getElementById('bottom5').style.display = 'unset';
    document.getElementById('bottom6').style.display = 'unset';
    document.getElementById('bottom7').style.display = 'none';
    break;
    case "7":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('bottom2').style.display = 'unset';
    document.getElementById('bottom3').style.display = 'unset';
    document.getElementById('bottom4').style.display = 'unset';
    document.getElementById('bottom5').style.display = 'unset';
    document.getElementById('bottom6').style.display = 'unset';
    document.getElementById('bottom7').style.display = 'unset';

      break;
    default:

  }
}

function showMissingSelect(){
  document.getElementById('missingNumber').style.display = "unset";
  var bottom  = document.querySelector('input[name="bottomNumber"]:checked').value;

  switch(bottom) {
    case "2":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('select2').style.display = 'unset';
    document.getElementById('select3').style.display = 'none';
    document.getElementById('select4').style.display = 'none';
    document.getElementById('select5').style.display = 'none';

      break;
    case "3":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('select2').style.display = 'unset';
    document.getElementById('select3').style.display = 'unset';
    document.getElementById('select4').style.display = 'none';
    document.getElementById('select5').style.display = 'none';

      break;
    case "4":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('select2').style.display = 'unset';
    document.getElementById('select3').style.display = 'unset';
    document.getElementById('select4').style.display = 'unset';
    document.getElementById('select5').style.display = 'none';

      break;
    case "5":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('select2').style.display = 'unset';
    document.getElementById('select3').style.display = 'unset';
    document.getElementById('select4').style.display = 'unset';
    document.getElementById('select5').style.display = 'unset';

      break;
      case "6":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('select2').style.display = 'unset';
    document.getElementById('select3').style.display = 'unset';
    document.getElementById('select4').style.display = 'unset';
    document.getElementById('select5').style.display = 'unset';

      break;
      case "7":
    document.getElementById('bottomNumberTable').style.display = 'unset';
    document.getElementById('select2').style.display = 'unset';
    document.getElementById('select3').style.display = 'unset';
    document.getElementById('select4').style.display = 'unset';
    document.getElementById('select5').style.display = 'unset';

      break;
    default:

  }
}
</script>