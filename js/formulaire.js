// Fonction pour charger le fichier CSV et extraire les données
async function loadCountriesCSV() {
    try {
        const response = await fetch('datas/pays.csv');
        const data = await response.text();

        // Diviser les données CSV en lignes et colonnes
        const rows = data.split('\n');
        const countriesData = rows.map(row => row.split(','));

        // Supprimer l'en-tête du CSV
        countriesData.shift();

        // Créer un objet de mapping pour les codes de pays et les noms de pays
        const countriesMap = {};
        countriesData.forEach(country => {
            const countryCode = country[2];
            const countryName = country[4];
            countriesMap[countryCode] = countryName;
        });

        return countriesMap;
    } catch (error) {
        console.error('Une erreur s\'est produite lors du chargement du fichier CSV :', error);
        return null;
    }
}

// Fonction pour mettre à jour le style de la div avec l'image correspondante
function updateFlagImage(countryCode, flagDivId) {
    const flagDiv = document.getElementById(flagDivId); // Sélectionner la div de drapeau correspondante
    flagDiv.style.backgroundImage = `url(./images/flags/${countryCode}.jpg)`; // Mettre à jour le style avec l'image correspondante
}

// Fonction pour gérer le changement de sélection dans le menu déroulant
async function handleSelectChange(value, flagDivId) {
    // Récupérer la valeur sélectionnée dans le menu déroulant
    const selectedValue = value;

    // Charger les données CSV
    const countriesMap = await loadCountriesCSV();

    // Vérifier si les données sont chargées avec succès
    if (countriesMap) {
        // Obtenir le nom du pays à partir du code de pays sélectionné
        const selectedCountryName = countriesMap[selectedValue];
        // Appeler la fonction pour mettre à jour l'image du drapeau avec le code de pays
        updateFlagImage(selectedValue, flagDivId);
    }
}

function addInputs() {

    var container = document.getElementById("inputs-container");
    container.addEventListener('dragover', allowDrop); // Autoriser le glisser sur le conteneur
    container.addEventListener('drop', drop); // Définir la fonction à exécuter lors du dépôt

    // Créer et ajouter un nouveau bloc
    var inputRow = document.createElement("div");
    inputRow.classList.add("input-row");
    var uniqueId = generateUniqueId(); // Générer un identifiant unique
    inputRow.id = "input-row-" + uniqueId; // ID unique pour chaque ensemble

    inputRow.draggable = true; // Rendre l'élément glissable
    inputRow.addEventListener('dragstart', drag); // Attacher l'événement de drag
    inputRow.addEventListener('dragend', dragEnd); // Attacher l'événement de dragend

    var selFlag = document.createElement("div");
    selFlag.classList.add("flex");
    selFlag.id = "selFlag" + uniqueId; // ID unique pour chaque ensemble

    var flag = document.createElement("div");
    flag.classList.add("flag");
    flag.id = "flag" + uniqueId; // ID unique pour chaque ensemble

    // Créer le menu déroulant de sélection de pays
    var selectElement = document.createElement("select");
    selectElement.id = "countrySelect" + uniqueId; // ID unique pour chaque menu déroulant
    selectElement.name = "country[]";
    selectElement.className = "country-select";
    selectElement.addEventListener('change', function () {
        handleSelectChange(this.value, 'flag' + uniqueId); // Appeler la fonction avec la valeur sélectionnée et l'ID du div flag correspondant
    });

    // Charger les données CSV
    loadCountriesCSV().then(countriesMap => {
        // Ajouter des options par défaut
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Sélectionner un pays';
        selectElement.appendChild(defaultOption);

        // Ajouter les options de pays
        for (const countryCode in countriesMap) {
            const countryName = countriesMap[countryCode];
            const option = document.createElement('option');
            option.value = countryCode;
            option.textContent = countryName;
            selectElement.appendChild(option);
        }
    });

    var input1 = document.createElement("input");
    input1.id = "date_" + uniqueId;
    input1.type = "date";
    input1.name = "date[]";

    var textarea = document.createElement("textarea");
    textarea.id = "event_" + uniqueId;
    textarea.rows = "5";
    textarea.name = "event[]";
    textarea.placeholder = "Evènement";
    textarea.oninput = "updateTotalCharacters()";
    textarea.addEventListener('input', updateTotalCharacters);

    var removeBtn = document.createElement("div");
    removeBtn.classList.add("remove-btn");
    removeBtn.textContent = "+";
    removeBtn.onclick = function () {
        removeInputs(inputRow.id); // Appeler la fonction de suppression avec l'ID du bloc parent
    };

    // Ajouter le menu déroulant de sélection de pays au conteneur
    inputRow.appendChild(selFlag);
    selFlag.appendChild(input1);
    selFlag.appendChild(selectElement);
    selFlag.appendChild(flag);
    inputRow.appendChild(textarea);
    selFlag.appendChild(removeBtn);
    container.insertBefore(inputRow, document.getElementById("add-btn"));
}

function removeInputs(parentRowId) {
    var inputRow = document.getElementById(parentRowId);
    inputRow.remove(); // Supprimer le bloc parent
    updateTotalCharacters();
}

// Fonction pour générer un identifiant unique
function generateUniqueId() {
    return Math.random().toString(36).substr(2, 9);
}

// Supprimer les éléments input-row déjà présents au chargement de la page
document.addEventListener('DOMContentLoaded', function () {
    var existingRows = document.querySelectorAll('.input-row');
    existingRows.forEach(function (row) {
        row.parentElement.removeChild(row);
    });
});




function updateTotalCharacters() {
    let totalCharacters = 0;

    // Sélectionner tous les textarea
    const textAreas = document.querySelectorAll('textarea');

    // Parcourir tous les textarea et ajouter la longueur de leur contenu au total
    textAreas.forEach(textArea => {
        totalCharacters += textArea.value.length;
    });

    // Mettre à jour le nombre total de caractères affiché
    const totalCharactersElement = document.getElementById('totalCharacters');
    totalCharactersElement.textContent = totalCharacters;
    const signesElement = document.getElementById('signes');

    //* Mettre à jour le texte de l'élément h4 selon la valeur de totalCharacters
    //* et apparaitre / masquer bouton 'sauver'


    if (totalCharacters < 2) {
        signesElement.textContent = totalCharacters + " signe";
        document.getElementById('save').style.display = 'none';
    }
    if (totalCharacters > 1) {
        signesElement.textContent = totalCharacters + " signes";
        document.getElementById('save').style.display = 'block';
    }

    //* Modifier la classe de l'élément en fonction du nombre total de caractères
    //* et apparaitre / masquer bouton 'sauvegarde'
    const elementsColorInfoClass = document.querySelectorAll('.colorInfo');
    const elementsColorInfoClassOK = document.querySelectorAll('.colorInfoOk');
    if (totalCharacters >= 1400 && totalCharacters <= 1500) {
        elementsColorInfoClass.forEach(element => {
            element.classList.remove('colorInfo');
            element.classList.add('colorInfoOk');
            document.getElementById('make').style.display = 'block'; // Afficher le bouton submit
        });
        // document.getElementById('savee').style.display = 'block'; // Afficher le bouton submit

    } else {
        elementsColorInfoClassOK.forEach(element => {
            element.classList.remove('colorInfoOk');
            element.classList.add('colorInfo');
        });
        document.getElementById('make').style.display = 'none'; // Afficher le bouton submit

    }
}


