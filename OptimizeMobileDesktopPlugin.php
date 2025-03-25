
First install : Seraphinite Accelerator (Base, cache only)
Second : Download the extend and install - "Seraphinite Accelerator (Extended, limited)"
Third : Deactivate the old plugin

Add this script on footer
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
