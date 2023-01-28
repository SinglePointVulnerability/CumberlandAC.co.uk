<?php
	echo '<script>
		function displayResult(a) {
			var btnSet1 = "<img class = \\"btn btn-lvl2 btn-Newsletter\\" src = \\"' . auto_version('img/btn-Newsletter.png') . '\\" onClick=\\"location.href=\'newsletter.php\'\\">\\n" +
						  "<img class = \\"btn btn-lvl2 btn-Gallery\\" src = \\"' . auto_version('img/btn-Gallery.png') . '\\" onClick=\\"location.href=\'gallery.php\'\\">\\n" +
						  "<img class = \\"btn btn-lvl2 btn-Documents\\" src = \\"' . auto_version('img/btn-Documents.png') . '\\"  onClick=\\"location.href=\'documents.php\'\\">\\n" +
						  "<div class = \\"btn\\"></div>\\n" +
						  "<div class = \\"btn\\"></div>\\n";
						  
			var btnSet2 = "<img class = \\"btn btn-lvl2 btn-Training\\" src = \\"' . auto_version('img/btn-Training.png') . '\\"  onClick=\\"location.href=\'training.php\'\\">\\n" +
						  "<img class = \\"btn btn-lvl2 btn-SocialEvents\\" src = \\"' . auto_version('img/btn-SocialEvents.png') . '\\" onClick=\\"location.href=\'social-events.php\'\\">\\n" +
						  "<img class = \\"btn btn-lvl2 btn-Races\\" src = \\"' . auto_version('img/btn-Races.png') . '\\"  onClick=\\"location.href=\'races.php\'\\">" +
						  "<div class = \\"btn\\"></div>\\n" +
						  "<div class = \\"btn\\"></div>\\n";		
																 
			if (a == "btnMed") {
				document.getElementById("lvl2-btns").innerHTML = btnSet1;
			}
			else if (a == "btnCal") {
				document.getElementById("lvl2-btns").innerHTML = btnSet2;
			}
			else {
				document.getElementById("lvl2-btns").innerHTML = "";
			}	
		}
		</script>';
?>