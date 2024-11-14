
document.addEventListener("DOMContentLoaded", function() {
    var avatar = document.querySelector(".avatar");
    var welcomeMessage = document.querySelector(".welcome-message");
  
    avatar.addEventListener("click", function() {
      welcomeMessage.style.display = "none"; 
    });
  
    setTimeout(function() {
        welcomeMessage.style.display = "none"; 
      }, 2500);

      setTimeout(function() {
        avatar.style.display = "none"; 
      }, 2500);

       
    });

var selectElement = document.getElementById("Preguntas");


selectElement.addEventListener("change", function() {
    var respuesta = selectElement.value;
    ejecutarCodigo(respuesta);
});


var generateButton = document.getElementById("generate");

var outputElement = document.getElementById("output");

generateButton.addEventListener("click", function() {
    var respuesta = selectElement.value;
    ejecutarCodigo(respuesta);
});


function ejecutarCodigo(respuesta) {
    if (respuesta === "Hoteles") {
      outputElement.textContent = "Aqui tienes los Mejores Hoteles de Parras";

      var mapContainer = document.getElementById("map-container");
        
        var iframe = document.createElement("iframe");
         iframe.src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d14411.57121940324!2d-102.19186899390192!3d25.441847090334413!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sHoteles!5e0!3m2!1ses-419!2smx!4v1694716233939!5m2!1ses-419!2smx";
         iframe.width="600"; 
         iframe.height="450";
         iframe.style.border=0; 
         iframe.allowfullscreen=""; 
         iframe.loading="lazy"; 
         iframe.referrerpolicy="no-referrer-when-downgrade";
         mapContainer.style.display = "block";
         mapContainer.innerHTML ="";

        mapContainer.appendChild(iframe);
      
       
    } else if (respuesta === "Comida") {
      outputElement.textContent = "La mejor comida y al mejor precio";
      var mapContainer = document.getElementById("map-container");
        
      var iframe = document.createElement("iframe");
        iframe.src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d14411.566668069858!2d-102.19186899802347!3d25.44188512581106!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sRestaurantes!5e0!3m2!1ses-419!2smx!4v1694716885418!5m2!1ses-419!2smx";
      
       iframe.style.border=0;
       iframe.allowfullscreen=""; 
       iframe.loading="lazy";
       iframe.referrerpolicy="no-referrer-when-downgrade";
       mapContainer.style.display = "block";
       mapContainer.innerHTML ="";
       mapContainer.appendChild(iframe);
    } else if (respuesta === "Bares") {
      outputElement.textContent = "Quieres pasar una buena fiesta, aqui tienes lo mejor";
      var mapContainer = document.getElementById("map-container");        
      var iframe = document.createElement("iframe");
        iframe.src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d14410.677607660406!2d-102.17471885412132!3d25.44931398293447!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sbares!5e0!3m2!1ses-419!2smx!4v1694800689512!5m2!1ses-419!2smx";
      
        iframe.style.border=0;
        iframe.allowfullscreen="";
        iframe.loading="lazy"; 
        iframe.referrerpolicy="no-referrer-when-downgrade";
      mapContainer.style.display = "block";
      mapContainer.innerHTML ="";
      mapContainer.appendChild(iframe);
    } else if (respuesta === "ZonasVerdes") {
      outputElement.textContent = "Parras el lugar perfecto para conocer la naturalesa";
      var mapContainer = document.getElementById("map-container");        
      var iframe = document.createElement("iframe");
      iframe.src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d28824.011078765223!2d-102.20830446146769!3d25.438217235605176!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sZonas%20verdes!5e0!3m2!1ses-419!2smx!4v1694801063719!5m2!1ses-419!2smx" 
      
      iframe.style.border=0;
      iframe.allowfullscreen=""; 
      iframe.loading="lazy"; 
      iframe.referrerpolicy="no-referrer-when-downgrade";
      mapContainer.style.display = "block";
      mapContainer.innerHTML ="";
      mapContainer.appendChild(iframe);
    } else {
      outputElement.textContent = "Parras el mejor lugar para esta en familia";
      var mapContainer = document.getElementById("map-container");        
      var iframe = document.createElement("iframe");
      iframe.src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d38120.759393000095!2d-102.20444520929922!3d25.450189643079813!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x868f47a9a959b7fb%3A0x3a86a217ad6ba99b!2sParras%20de%20la%20Fuente%2C%20Coah.!5e0!3m2!1ses-419!2smx!4v1694801303320!5m2!1ses-419!2smx";
      iframe.style.border=0;
      iframe.allowfullscreen="";
      iframe.loading="lazy";
      iframe.referrerpolicy="no-referrer-when-downgrade";
      mapContainer.style.display = "block";
      mapContainer.innerHTML ="";
      mapContainer.appendChild(iframe);
    }
}

function setAction(accion) {
  var form = document.getElementById('form');

  if (accion === 'IniciarSecion') {
      form.action = 'Login.php';
  } else if(accion === 'Registrarse') {
      form.action = 'conexion.php';
  } 

  form.submit();
}

function VerContraseña() {
  var passwordField = document.getElementById("passwordField");

  if (passwordField.type === "password") {
      passwordField.type = "text";
  } else {
      passwordField.type = "password";
  }
}
function CambiarPassword() {
  window.location.href = "actualizarPassword.html";
}