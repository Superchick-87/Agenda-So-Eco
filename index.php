 <?php
    include('includes/options.php');
    $letterSpacingJson = json_encode($letterSpacing);
    ?>
 <script>
     const letterSpacingOptions = <?php echo $letterSpacingJson; ?>;
 </script>
 <!DOCTYPE html>
 <html lang="fr">
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" type="text/css" href="css/styles.css">
     <title>Agenda SOD</title>
 </head>

 <body>
     <div style="display: flex; width:90%; margin-left: 180px; justify-content: space-between;">
         <div id="txtHint"></div>
         <div id="toto" style="display:none; margin-left: 70px;"></div>
     </div>
     <div>
         <div class="colorInfo agendaDateSize logo">
             <input id="agendaSod" class="agendaDate" type="date" name="agendaSod" onchange="showHint(this.value)">
         </div>
     </div>
     <script>
         var agendaSod = document.getElementById('agendaSod');
         //@ Fonctions d'envoi du formulaire via AJAX
         function showHint(str) {
             const toto = document.getElementById('toto');
             const inputRows = document.getElementsByClassName("input-row");
             var xhttp;
             if (str == '') {
                 document.getElementById("txtHint").innerHTML = "mdmdm";
                 return;
             }
             xhttp = new XMLHttpRequest();
             xhttp.onreadystatechange = function() {
                 if (this.readyState == 4 && this.status == 200) {
                     document.getElementById("txtHint").innerHTML = this.responseText;

                     //* Appels fonctions
                     updateLetterSpacingForAll();
                     updateTotalCharacters();
                     totalEvents();
                     guillemets();
                     //* FIN - Appels fonctions

                     toto.style.display = "none";

                     $(document).ready(function() {
                         function updateFlagImage(selectElement) {
                             var selectedCountryCode = selectElement.val();
                             var flagElement = selectElement.closest(".input-row").find(".flag");
                             flagElement.css("background-image", "url(images/flags/" + selectedCountryCode + ".jpg)");
                         }
                         $("select[name='country[]']").change(function() {
                             updateFlagImage($(this));
                         });

                         $("select[name='country[]']").each(function() {
                             updateFlagImage($(this));
                         });
                     });
                 }
             };
             xhttp.open("GET", "work.php?agendaSod=" + str, true);
             xhttp.send();
         }

         function submitForm(event) {
             // Empêcher le rechargement de la page lors de la soumission du formulaire
             event.preventDefault();
             // Créer un objet FormData à partir du formulaire
             var formData = new FormData(document.getElementById('form2'));
             // Afficher toutes les données du formulaire dans la console pour le débogage
             formData.forEach(function(value, key) {
                 console.log(key + ": " + value);
             });
             // Créer un objet XMLHttpRequest pour envoyer la requête AJAX
             var xhr = new XMLHttpRequest();
             xhr.open('POST', 'done.php', true);
             // Gérer la réponse du serveur après la requête AJAX
             xhr.onreadystatechange = function() {
                 if (xhr.status === 200) {
                     document.getElementById('toto').style.display = "block";
                     // Mettre à jour le contenu de #toto avec la réponse de done.php
                     document.getElementById('toto').innerHTML = xhr.responseText;
                 }
             };
             // Envoi des données du formulaire avec la requête AJAX
             xhr.send(formData);
         }
     </script>
     <script src="js/formulaire.js"></script>

 </body>

 </html>