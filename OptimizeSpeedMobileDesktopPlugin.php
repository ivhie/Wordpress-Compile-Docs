
First install : Seraphinite Accelerator (Base, cache only)
Second : Download the extend and install - "Seraphinite Accelerator (Extended, limited)"
Third : Deactivate the old plugin

Notes :
1. Disable mo din pala yung lazy loading sa elementor settings
2. Add this script on footer to hide the water mark
3. Enable the gd extension in PHP settings.
<script> 
	document.addEventListener("DOMContentLoaded", function() { 
		var enlacesConImagen = document.querySelectorAll('a img\[alt="Seraphinite Accelerator"\]'); 
		enlacesConImagen.forEach(function(imagen) { 
			var enlace = imagen.closest('a'); 
			if (enlace) { 
				enlace.style.display = "none"; 
			} 
		}); 
	}); 
</script>
