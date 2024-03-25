function addInputs() {
    let elements = document.querySelectorAll('.input-row').length;

    var inputCount = elements; // Compteur pour les ID uniques
    var container = document.getElementById("inputs-container");

    inputCount++; // Incrémenter le compteur

    // Vérifier si un élément avec cet ID existe déjà
    if (!document.getElementById("input-row-" + inputCount)) {
        var inputRow = document.createElement("div");
        inputRow.classList.add("input-row");
        inputRow.id = "input-row-" + inputCount; // ID unique pour chaque ensemble

        var input1 = document.createElement("input");
        input1.id = "date_" + inputCount;
        input1.type = "date";
        input1.name = "date[]";

        var textarea = document.createElement("textarea");
        textarea.id = "event_" + inputCount;
        textarea.rows = "5";
        textarea.name = "event[]";
        textarea.placeholder = "Evènement";
        textarea.addEventListener('input', updateTotalCharacters);

        var removeBtn = document.createElement("div");
        removeBtn.id = "remove-btn-" + inputCount; // ID unique pour chaque bouton de suppression
        removeBtn.classList.add("remove-btn");
        removeBtn.textContent = "+";
        removeBtn.onclick = function () {
            inputRow.remove(); // Supprimer le bloc parent
        };

        inputRow.appendChild(input1);
        inputRow.appendChild(textarea);
        inputRow.appendChild(removeBtn);
        // container.appendChild(inputRow);
        
        // Insérer la nouvelle rangée juste avant l'élément de soumission
        container.insertBefore(inputRow, document.getElementById("valid"));
    }
}

// Sert à supprimer un bloc -> Select | Date | Textarea  
function removeInputs(id) {
    var inputRow = document.getElementById(id);
    inputRow.parentElement.removeChild(inputRow);

    // Mise à jour des identifiants des éléments restants
    updateRowIds();
    updateTotalCharacters();
}

// Fonction pour mettre à jour les identifiants des éléments restants
function updateRowIds() {
    var remainingRows = document.querySelectorAll('.input-row');
    remainingRows.forEach(function (row, index) {
        row.id = 'input-row-' + (index + 1); // Mise à jour de l'identifiant
        row.querySelector('input[type="date"]').id = 'date_' + (index + 1); // Mise à jour de l'identifiant de l'input date
        row.querySelector('textarea').id = 'event_' + (index + 1); // Mise à jour de l'identifiant de la textarea
        row.querySelector('.remove-btn').id = 'remove-btn-' + (index + 1); // Mise à jour de l'identifiant du bouton de suppression
        row.querySelector('.remove-btn').onclick = function () {
            removeInputs('input-row-' + (index + 1));
        };
    });
}

// Supprimer les éléments input-row déjà présents au chargement de la page
document.addEventListener('DOMContentLoaded', function () {
    var existingRows = document.querySelectorAll('.input-row');
    existingRows.forEach(function (row) {
        row.parentElement.removeChild(row);
    });
});
// FIN Sert à supprimer un bloc -> Select | Date | Textarea  


function updateTotalCharacters() {
    let totalCharacters = 0;

    // Sélectionner tous les textarea
    const textAreas = document.querySelectorAll('textarea');

    // Parcourir tous les textarea et ajouter la longueur de leur contenu au total
    textAreas.forEach(textArea => {
        totalCharacters += textArea.value.length;
    });

    // Mettre à jour le nombre total de caractères affiché
    document.getElementById('totalCharacters').textContent = totalCharacters;
}




// @ Ajout su select
function select() {
    var elements = document.querySelectorAll('.input-row').length;
    // Chemin vers le fichier pays.csv
    const countriesCSV = 'datas/pays.csv';

    // Variable pour garder une trace du nombre de menus déroulants ajoutés
    let dropdownCount = elements;

    // Fonction pour récupérer la liste des pays depuis le fichier CSV
    async function fetchCountries() {
        try {
            const response = await fetch(countriesCSV);
            const data = await response.text();
    
            // Diviser les données CSV en lignes et colonnes
            const rows = data.split('\n');
            const countries = rows.map(row => row.split(',')[4]); // La 5ème colonne contient les noms des pays
    
            // Trier les pays par ordre alphabétique
            countries.sort((a, b) => a.localeCompare(b));
    
            // Créer un nouvel élément select
            const selectElement = document.createElement('select');
            selectElement.name = 'country[]';
    
            // Créer une option pour sélectionner
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Sélectionner un pays';
            selectElement.appendChild(defaultOption);
    
            // Parcourir les données triées pour créer les options du menu déroulant
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country;
                option.textContent = country;
                selectElement.appendChild(option);
            });
    
            return selectElement;
        } catch (error) {
            console.error('Une erreur s\'est produite :', error);
            return null;
        }
    }
    

    // Ajouter un écouteur d'événements au bouton d'ajout
    const addCountrySelectBtn = document.getElementById('add-btn');
    addCountrySelectBtn.addEventListener('click', async function () {
        const selectElement = await fetchCountries();
        if (selectElement) {
            let elements = document.querySelectorAll('.input-row').length;
            let dropdownCount = elements;

            // Récupérer le conteneur
            const parentContainer = document.getElementById("input-row-" + dropdownCount);
            const firstDateInput = parentContainer.querySelector('input[type="date"]');
            if (firstDateInput) {
                parentContainer.insertBefore(selectElement, firstDateInput);
            }
        }
    });

}

select();
updateTotalCharacters();