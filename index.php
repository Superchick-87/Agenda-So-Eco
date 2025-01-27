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
                     compareDate();
                     //* Appels fonctions
                     updateLetterSpacingForAll();
                     updateTotalCharacters();
                     totalEvents();
                     guillemets();
                     duplicate();
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
     <script>

        function compareDate() {
            const dateOrigine = document.getElementById('agendaSod');
            const agendaSodd = document.getElementById('agendaSodd');

            // Initialisation : mettre la valeur de `dateOrigine` dans `agendaSodd`
            agendaSodd.value = dateOrigine.value;

            // Ajout d'un écouteur pour détecter les changements dans `dateOrigine`
            dateOrigine.addEventListener('change', (event) => {
                // Met à jour la valeur de `agendaSodd` avec celle de `dateOrigine`
                agendaSodd.value = event.target.value;
            });

            console.log('Valeur initiale de agendaSodd:', agendaSodd.value);
        }

        function duplicate() {
            document.getElementById('dupli').addEventListener('click', function () {
                const partDupli = document.getElementById('partDupli');
                const overlay = document.getElementById('overlay');
                const agendaSodd = document.getElementById('agendaSodd');

                // Afficher l'overlay
                overlay.style.display = 'block';
                
                // Nettoyer le conteneur
                partDupli.innerHTML = '';

                // Créer un formulaire
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'duplicate.php';
                // form.action = 'done.php';

                // Créer un élément input de type date
                const dateInput = document.createElement('input');
                dateInput.type = 'date';
                dateInput.name = 'datec'; // Nom du champ pour la soumission

                // Récupération de la date d'origine
                const dateOrigine = document.createElement('input');
                dateOrigine.id = 'date';
                dateOrigine.value = agendaSodd.value;
                dateOrigine.type = 'date';
                dateOrigine.name = 'agendaSodd'; // Nom du champ pour la soumission
               
                // console.log(dateOrigine.value); 
                // dateInput.type = 'date';
                // dateInput.name = 'datec'; // Nom du champ pour la soumission

                // Créer un bouton OK
                const okButton = document.createElement('button');
                okButton.type = 'submit'; // Bouton de soumission
                okButton.textContent = 'OK';

                // Ajouter un événement avant soumission
                form.addEventListener('submit', function (event) {
                    if (!dateInput.value) { // Vérifie si le champ de date est vide
                        event.preventDefault(); // Empêche la soumission du formulaire
                        
                        alert('Veuillez sélectionner une date avant de soumettre.');
                    }
                });

                // Ajouter les éléments au formulaire
                form.appendChild(dateOrigine);
                form.appendChild(dateInput);
                form.appendChild(okButton);

                // Ajouter le formulaire au conteneur
                partDupli.appendChild(form);

                // Ajouter un événement pour fermer l'overlay en cliquant à l'extérieur du formulaire
               overlay.addEventListener('click', function (event) {
                   if (event.target === overlay) {
                       overlay.style.display = 'none'; 
                   }
               });
            });  
        }
    </script>
 </body>

 </html>