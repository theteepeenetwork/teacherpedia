<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $title;?></title>


  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
  <!-- Latest compiled and minified CSS -->
  <link media="print" type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/print.css') ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Kite+One&display=swap" rel="stylesheet">

  <link {csp-style-nonce} rel="stylesheet" type="text/css" href="/assets/css/resource-sheet.css" />

</head>

<body>
  <div id="infobox">
    <FORM>
      <INPUT TYPE="button" class="button" onClick="history.go(0)" VALUE="Generate new questions">
    </FORM>
  </div>

<?php echo $worksheet; //footer is added to generator.php code to add logo and data at end of each page. found in templates/worksheets ?>