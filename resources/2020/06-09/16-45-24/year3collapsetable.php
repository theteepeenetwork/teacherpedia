<!DOCTYPE html>
  <html>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
  .accordion {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    margin: 3px;
    padding: 18px;
    width: 100%;
    border: none;
    border-radius: 9px; 
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
  }

  .panel-inner{

  }

  .panel-inner-below {
      background-color: red;
  }
  .panel-inner-meeting {
      background-color: orange;
  }
  .panel--inner-above {
      background-color: green;
  }
  
  .active, .accordion:hover {
    background-color: #ccc;
  }
  
  .panel {
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
  }
  </style>
  </head>
  <body>
  
  <h2>Animated Accordion</h2>
  <p>Click on the buttons to open the collapsible content.</p>
  
  <div class="accordion">Counting</div>
  <div class="panel">
    <div class="panel-inner panel-inner-below">
        H1
        <table>
          <tr>
          <td>
          </td>
          </tr>

        </table>
    </div>
    <div class="panel-inner panel-inner-meeting">
        H1
    </div>
    <div class="panel-inner panel-inner-above">
        H1
    </div>
  </div>
  
  <div class="accordion">Comparing Numbers</div>
  <div class="panel">
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
  </div>
  
  <div class="accordion">Identifying, representing & estimating numbers</div>
  <div class="panel">
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
  </div>
  
  <script>
  var acc = document.getElementsByClassName("accordion");
  var i;
  
  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var panel = this.nextElementSibling;
      if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      } 
    });
  }
  </script>
  
  </body>
  </html>
  