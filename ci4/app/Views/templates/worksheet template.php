

<head>
<link media="print" type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/print.css') ?>" />

<style>

    body {
      background: rgb(204,204,204); 
      font-family: arial;
    }
    page {
      background: white;
      display: block;
      margin: 0 auto;
      margin-bottom: 0.5cm;
    }
    page[size="A4"] {  
      width: 21cm;
      height: 98vh; 
    }

    .innerPage {
        padding: 10px;
    }

    .button {
      background-color: #4CAF50; /* Green */
      border: none;
      color: white;
      padding: 15px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: auto;
    }

    #refreshbutton  {
      margin: auto;
    }


    #header {
      display:none;
    }

    #sheetFooter {
      width: 100%;
      position: fixed;
      bottom: 0px;
      display: none;
    }

#bootstrap-overrides {
  display: none;
}


@media print {
  #infobox, .print {
    page-break-before: always;
  }
#infobox, #header, #footer {display:none;}
#sheetFooter {display: block;}

}


</style>

</head>
<body>
    <div id="infobox">
        <FORM>
            <INPUT id="refreshbutton" TYPE="button" class="button" onClick="history.go(0)" VALUE="Generate new questions">
        </FORM>
    </div>

<page size="A4">
        <div class="innerPage">
        </div>
        <div class="footer"></div>
</page>


<page size="A4">
        <div class="innerPage">
        </div>
        <div class="footer"></div>
</page>
    
</body>
</html>
