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
        container.appendChild(inputRow);
    }
}

// Sert à supprimer un bloc -> Select | Date | Textarea  
function removeInputs(id) {
    var inputRow = document.getElementById(id);
    inputRow.parentElement.removeChild(inputRow);

    // Mise à jour des identifiants des éléments restants
    updateRowIds();
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


// window.onload = updateTotalCharacters();
function updateTotalCharacters() {
    // var dd = document.getElementById('totalCharacters').textContent;
    let totalCharacters = 0;

    // Sélectionner tous les textarea
    const textAreas = document.querySelectorAll('textarea');

    // Parcourir tous les textarea et ajouter la longueur de leur contenu au total
    textAreas.forEach(textArea => {
        totalCharacters += textArea.value.length;
    });
    document.getElementById('totalCharacters').textContent = totalCharacters;

    // Afficher le nombre total de caractères

    // textarea.addEventListener("input", function () {
    //     // Met à jour le contenu du span avec le nombre de caractères dans le textarea
    //     totalCharacters = textarea.value.length;
    // });
}


// @ Ajout su select
function select() {
    var elements = document.querySelectorAll('.input-row').length;
    // URL de l'API REST fournissant la liste des pays
    const countriesAPI = 'https://restcountries.com/v3.1/all';

    // Variable pour garder une trace du nombre de menus déroulants ajoutés
    let dropdownCount = elements;

    // Variable pour garder une trace du nombre de divs ajoutées
    let divCount = elements;

    // Fonction pour récupérer la liste des pays depuis l'API REST
    async function fetchCountries() {
        try {
            const response = await fetch(countriesAPI);
            const data = await response.json();

            // Créer un nouvel élément select
            const selectElement = document.createElement('select');
            selectElement.name = "country[]";

            // Créer une option pour sélectionner
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Sélectionner un pays';
            selectElement.appendChild(defaultOption);

            // Trier les données des pays par ordre alphabétique en français
            data.sort((a, b) => {
                return a.name.common.localeCompare(b.name.common, 'fr');
            });

            // Parcourir les données pour créer les options du menu déroulant
            data.forEach(country => {
                const option = document.createElement('option');
                option.value = country.name.common;
                option.textContent = country.name.common;
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

async function insertSelectInExistingInputs() {
    // Récupérer tous les blocs input-row existants
    const existingInputRows = document.querySelectorAll('.input-row');

    // Fonction pour récupérer la liste des pays depuis l'API REST
    const countriesAPI = 'https://restcountries.com/v3.1/all';
    async function fetchCountries() {
        try {
            const response = await fetch(countriesAPI);
            const data = await response.json();

            // Créer un nouvel élément select
            const selectElement = document.createElement('select');
            selectElement.name = "country[]";

            // Créer une option pour sélectionner
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Sélectionner un pays';
            selectElement.appendChild(defaultOption);

            // Trier les données des pays par ordre alphabétique en français
            data.sort((a, b) => {
                return a.name.common.localeCompare(b.name.common, 'fr');
            });

            // Parcourir les données pour créer les options du menu déroulant
            data.forEach(country => {
                const option = document.createElement('option');
                option.value = country.name.common;
                option.textContent = country.name.common;
                selectElement.appendChild(option);
            });

            return selectElement;
        } catch (error) {
            console.error('Une erreur s\'est produite :', error);
            return null;
        }
    }

    const selectElement = await fetchCountries();

    // Pour chaque bloc input-row existant
    existingInputRows.forEach((row) => {
        // Récupérer le champ de date dans ce bloc
        const dateInput = row.querySelector('input[type="date"]');

        // S'il existe un champ de date dans le bloc
        if (dateInput) {
            // Insérer l'élément select avant le champ de date
            row.insertBefore(selectElement.cloneNode(true), dateInput);
        }
    });
}

// Appel de la fonction select() pour ajouter des menus déroulants au clic sur le bouton d'ajout
select();
// Appel de la fonction insertSelectInExistingInputs() pour ajouter des menus déroulants aux blocs existants
insertSelectInExistingInputs();
updateTotalCharacters();

// Fonction pour sélectionner automatiquement l'option correspondante dans chaque select
function selectDefaultOptions() {
    // Récupérer tous les selects existants
    const existingSelects = document.querySelectorAll('select[name="country[]"]');

    // Pour chaque select existant
    existingSelects.forEach(select => {
        // Récupérer la valeur actuelle du select
        const selectedValue = select.value;

        // Pour chaque option dans le select
        select.querySelectorAll('option').forEach(option => {
            // Si la valeur de l'option correspond à la valeur actuelle du select
            if (option.value === selectedValue) {
                // Sélectionner l'option
                option.setAttribute('selected', 'selected');
            }
        });
    });
}

// Lire le fichier CSV et mettre à jour les selects existants
fetch('./datas/2024-03-03_datas.csv') // Remplacez 'datas/2024-03-03_datas.csv' par le chemin de votre fichier CSV réel
    .then(response => response.text())
    .then(csvData => {
        const countries = extractCountriesFromCSV(csvData);
        updateExistingSelects(countries);
        selectDefaultOptions(); // Appeler la fonction pour sélectionner les options par défaut
    })
    .catch(error => console.error('Une erreur s\'est produite lors de la lecture du fichier CSV :', error));
