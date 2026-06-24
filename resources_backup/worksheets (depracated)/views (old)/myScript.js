//hide the unselected year groups.
	       function myFunction(yearGroup) {
	           document.getElementById('yearGroup1').style.display = "none";
	           document.getElementById('yearGroup2').style.display = "none";
	           document.getElementById('yearGroup3').style.display = "none";
	           /*document.getElementById('yearGroup4').style.display = "none";
	           document.getElementById('yearGroup5').style.display = "none";
	           document.getElementById('yearGroup6').style.display = "none";*/

	           yearGroup.style.display = "block";

	       }
	       /*myFunction();

	       window.onload = function () {
	           var ddl = document.getElementsByTagName("select");
	           for (var i = 1; i <= 10; i++) {
	               var theOption = new Option;
	               theOption.text = i;
	               theOption.value = i;
	               ddl.options[i] = theOption;
	           }
	       };*/

		//highlight cell
		function changeColor(cell) {
			if (!cell.checked) {

				cell.changeColor = "blue";
				alert("Blue");

			} else {

				cell.className = "blue";

			}

		}