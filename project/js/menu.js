	var menuHolder = document.getElementById('menuHolder')
	var siteBrand = document.getElementById('siteBrand')

	function menuToggle() {
	  if (menuHolder.className === "drawMenu") menuHolder.className = ""
	  else menuHolder.className = "drawMenu"
	}
	if (window.innerWidth < 426) siteBrand.innerHTML = "CIS"
	window.onresize = function() {
	  if (window.innerWidth < 420) siteBrand.innerHTML = "CIS"
	  else siteBrand.innerHTML = "Teaching in CIS"
	}