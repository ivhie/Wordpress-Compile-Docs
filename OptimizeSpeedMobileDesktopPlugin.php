
First install : Seraphinite Accelerator (Base, cache only)
Second : Download the extend and install - "Seraphinite Accelerator (Extended, limited)"
Third : Deactivate the old plugin

Notes :
1. Disable mo din pala yung lazy loading sa elementor settings
2. Add this script on footer to hide the water mark
3. Enable the gd extension in PHP settings.
4. IF Jetpack is install simply go to ( Performance & speed- disabled accelarate )
5. If litespeed was installed, expect to have an issue. try to install again the litespeed and turn off the cache and purge all 
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
