<?php
$pageContents .= <<< EOF
<script>

var select = document.getElementById("themes");
if(select.addEventListener) {
	var stylecall = document.getElementById("stylecall");
	
	var wampStyle = localStorage.getItem("wampStyle");
	if(wampStyle !== null) {
	    stylecall.setAttribute("href", "wampthemes/" + wampStyle + "/style.css");
	    selectedOption = document.getElementById(wampStyle);
	    selectedOption.setAttribute("selected", "selected");
	}
	else {
	    localStorage.setItem("wampStyle","classic");
	    selectedOption = document.getElementById("classic");
	    selectedOption.setAttribute("selected", "selected");
	}
	
	select.addEventListener("change", function(){
	    var styleName = this.value;
	    stylecall.setAttribute("href", "wampthemes/" + styleName + "/style.css");
	    localStorage.setItem("wampStyle", styleName);
	})
}
</script>
EOF;
?>
