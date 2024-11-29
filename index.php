 <?php
    echo phpversion();
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

     <div id="txtHint"></div>
     <div>
         <!-- <h1>L'agenda de la semaine</h1> -->
         <div class="colorInfo agendaDateSize logo">
             <input id="agendaSod" class="agendaDate" type="date" name="agendaSod" onchange="showHint(this.value)">
         </div>
     </div>
     <script>
         var agendaSod = document.getElementById('agendaSod');


         function showHint(str) {
             var xhttp;
             if (str == '') {
                 document.getElementById("txtHint").innerHTML = "mdmdm";
                 return;
             }
             xhttp = new XMLHttpRequest();
             xhttp.onreadystatechange = function() {
                 if (this.readyState == 4 && this.status == 200) {
                     document.getElementById("txtHint").innerHTML = this.responseText;
                     // Mettre à jour l'interlettrage après le chargement du contenu

                     updateLetterSpacingForAll();
                     updateTotalCharacters();
                     guillemets();


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
     </script>
     <script src="js/formulaire.js"></script>

     <script>
         let initialPosition = null;

         function startDrag(event) {
             const inputRow = event.target.closest('.input-row');
             const blocJour = event.target.closest('.bloc_jour');

             // Vérifie si l'élément cliqué est bien un .input-row à l'intérieur d'une .bloc_jour
             if (inputRow && blocJour) {
                 // Activer draggable sur .input-row
                 inputRow.setAttribute('draggable', 'true');

                 // Sauvegarder la position initiale de l'élément
                 initialPosition = inputRow;
             }
         }

         function allowDrop(event) {
             event.preventDefault(); // Permet le drop en empêchant le comportement par défaut
         }

         function drag(event) {
             const inputRow = event.target.closest('.input-row');
             if (inputRow) {
                 event.dataTransfer.setData("text", inputRow.id);
                 inputRow.classList.add('dragging'); // Ajoute une classe CSS pendant le déplacement
             }
         }

         function drop(event) {
             event.preventDefault(); // Empêche le comportement par défaut
             const data = event.dataTransfer.getData("text"); // Récupère l'ID de l'élément déplacé
             const draggedElement = document.getElementById(data); // Sélectionne l'élément par son ID
             const dropzone = event.target.closest('.input-row'); // Trouve la zone de dépôt cible

             const draggedElementBloc = draggedElement.closest('.bloc_jour'); // Récupère la boîte de l'élément déplacé
             const dropzoneBloc = dropzone.closest('.bloc_jour'); // Récupère la boîte de la zone de dépôt

             // Vérifie que l'élément et la zone de dépôt sont dans la même boîte
             if (dropzone && dropzone !== draggedElement && draggedElementBloc === dropzoneBloc) {
                 // On insère l'élément avant ou après la zone de dépôt
                 const draggedRect = draggedElement.getBoundingClientRect();
                 const dropzoneRect = dropzone.getBoundingClientRect();

                 if (dropzoneRect.top < draggedRect.top) {
                     // Si la zone de dépôt est au-dessus de l'élément déplacé
                     dropzone.parentNode.insertBefore(draggedElement, dropzone);
                 } else if (dropzoneRect.top > draggedRect.top) {
                     // Si la zone de dépôt est en dessous de l'élément déplacé
                     dropzone.parentNode.insertBefore(draggedElement, dropzone.nextSibling);
                 }
             }

             // Supprime la classe de déplacement
             draggedElement.classList.remove('dragging');
         }

         function dragEnd(event) {
             const inputRow = event.target.closest('.input-row');
             if (inputRow) {
                 // Vérifie si la position a changé ou non
                 if (initialPosition === inputRow) {
                     // Si pas de changement, désactiver draggable
                     inputRow.setAttribute('draggable', 'false');
                 }
                 inputRow.classList.remove('dragging'); // Supprime la classe CSS lorsque le glisser se termine
             }
             initialPosition = null; // Réinitialiser la position initiale
         }

         // Désactiver draggable pendant l'édition du textarea
         document.querySelectorAll('.input-row').forEach(inputRow => {
             const textarea = inputRow.querySelector('textarea');
             if (textarea) {
                 // Quand le textarea est en focus, désactiver le drag sur inputRow
                 textarea.addEventListener('focus', () => {
                     inputRow.setAttribute('draggable', 'false');
                 });

                 // Quand le textarea perd le focus, réactiver le drag sur inputRow
                 textarea.addEventListener('blur', () => {
                     inputRow.setAttribute('draggable', 'true');
                 });
             }
         });

         // Ajout d'écouteurs sur .bloc_jour pour démarrer le drag-and-drop
         document.querySelectorAll('.bloc_jour').forEach(bloc => {
             bloc.addEventListener('mousedown', startDrag);
         });




         //  function allowDrop(event) {
         //      event.preventDefault(); // Prevent default behavior
         //  }

         //  function drag(event) {
         //      event.dataTransfer.setData("text", event.target.id); // Store the ID of the dragged element
         //      event.target.classList.add('dragging'); // Add the dragging class to change cursor
         //  }

         //  function drop(event) {
         //      event.preventDefault(); // Prevent default behavior
         //      var data = event.dataTransfer.getData("text"); // Get the ID of the dragged element
         //      var draggedElement = document.getElementById(data);
         //      var dropzone = event.target.closest('.input-row'); // Find the closest input row to drop onto

         //      if (dropzone) {
         //          // Insert the dragged element before the dropzone
         //          dropzone.parentNode.insertBefore(draggedElement, dropzone);
         //      } else {
         //          // If not dropped on an input-row, append it to the container
         //          document.getElementById('inputs-container').appendChild(draggedElement);
         //      }

         //      // Remove the dragging class when drop action is complete
         //      draggedElement.classList.remove('dragging');
         //  }

         //  function dragEnd(event) {
         //      event.target.classList.remove('dragging'); // Remove the dragging class when drag ends
         //  }
     </script>

 </body>

 </html>