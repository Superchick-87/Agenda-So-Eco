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
    <title>Add Inputs Dynamically</title>
</head>

<body>
    <div id="txtHint"></div>
    <div class="colorInfo agendaDateSize">
        <input id="agendaSod" class="agendaDate" type="date" name="agendaSod" onchange="showHint(this.value)">
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
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("txtHint").innerHTML = this.responseText;
                    updateTotalCharacters();
                    

                    $(document).ready(function () {
                        function updateFlagImage(selectElement) {
                            var selectedCountryCode = selectElement.val();
                            var flagElement = selectElement.closest(".input-row").find(".flag");
                            flagElement.css("background-image", "url(images/flags/" + selectedCountryCode + ".jpg)");
                        }

                        $("select[name=\'country[]\']").change(function () {
                            updateFlagImage($(this));
                        });

                        $("select[name=\'country[]\']").each(function () {
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
        function allowDrop(event) {
            event.preventDefault(); // Prevent default behavior
        }

        function drag(event) {
            event.dataTransfer.setData("text", event.target.id); // Store the ID of the dragged element
            event.target.classList.add('dragging'); // Add the dragging class to change cursor
        }

        function drop(event) {
            event.preventDefault(); // Prevent default behavior
            var data = event.dataTransfer.getData("text"); // Get the ID of the dragged element
            var draggedElement = document.getElementById(data);
            var dropzone = event.target.closest('.input-row'); // Find the closest input row to drop onto

            if (dropzone) {
                // Insert the dragged element before the dropzone
                dropzone.parentNode.insertBefore(draggedElement, dropzone);
            } else {
                // If not dropped on an input-row, append it to the container
                document.getElementById('inputs-container').appendChild(draggedElement);
            }

            // Remove the dragging class when drop action is complete
            draggedElement.classList.remove('dragging');
        }

        function dragEnd(event) {
            event.target.classList.remove('dragging'); // Remove the dragging class when drag ends
        }
    </script>
      
</body>
</html>