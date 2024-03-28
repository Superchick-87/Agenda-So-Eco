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
    console.log(flagDiv);
    flagDiv.style.backgroundImage = `url(./images/flags/${countryCode}.png)`; // Mettre à jour le style avec l'image correspondante
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
let inputCount = 0; // Déclaration de la variable globale

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

        var selFlag = document.createElement("div");
        selFlag.classList.add("flex");
        selFlag.id = "selFlag" + inputCount; // ID unique pour chaque ensemble

        var flag = document.createElement("div");
        flag.classList.add("flag");
        flag.id = "flag" + inputCount; // ID unique pour chaque ensemble

        // Créer le menu déroulant de sélection de pays
        var selectElement = document.createElement("select");
        selectElement.id = "countrySelect" + inputCount; // ID unique pour chaque menu déroulant
        selectElement.name = "country[]";
        selectElement.className = "country-select";
        selectElement.addEventListener('change', function () {
            handleSelectChange(this.value, 'flag' + inputCount); // Appeler la fonction avec la valeur sélectionnée et l'ID du div flag correspondant
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

        // Ajouter le menu déroulant de sélection de pays au conteneur
        selFlag.appendChild(selectElement);

        var input1 = document.createElement("input");
        input1.id = "date_" + inputCount;
        input1.type = "date";
        input1.name = "date[]";

        var textarea = document.createElement("textarea");
        textarea.id = "event_" + inputCount;
        textarea.rows = "5";
        textarea.name = "event[]";
        textarea.placeholder = "Evènement";
        textarea.oninput = "updateTotalCharacters()";
        textarea.addEventListener('input', updateTotalCharacters);

        var removeBtn = document.createElement("div");
        removeBtn.id = "remove-btn-" + inputCount; // ID unique pour chaque bouton de suppression
        removeBtn.classList.add("remove-btn");
        removeBtn.textContent = "+";
        removeBtn.onclick = function () {
            inputRow.remove(); // Supprimer le bloc parent
            updateTotalCharacters();
            updateRowIds();
            // // Après l'ajout des éléments, faire correspondre les sélecteurs déjà présents aux divs de drapeau
            // updateFlagDivAssociations();
        };
        inputRow.appendChild(selFlag);
        selFlag.appendChild(flag);
        selFlag.appendChild(input1);
        inputRow.appendChild(textarea);
        inputRow.appendChild(removeBtn);
        container.insertBefore(inputRow, document.getElementById("save"));

    }

    updateFlagDivAssociations();
}
async function handleSelectChange(value, flagDivId) {
    const selectedValue = value;
    const countriesMap = await loadCountriesCSV();
    if (countriesMap) {

        const selectedCountryName = countriesMap[selectedValue];
        updateFlagImage(selectedValue, flagDivId);
    }
}

// Sert à supprimer un bloc -> Select | Date | Textarea  
// function removeInputs(id) {
//     var inputRow = document.getElementById(id);
//     inputRow.parentElement.removeChild(inputRow);

//     // Mise à jour des identifiants des éléments restants
//     updateTotalCharacters();
//     updateRowIds();
// }

function removeInputs(id) {
    var inputRow = document.getElementById(id);
    inputRow.parentElement.removeChild(inputRow);


    // Mettre à jour les sélecteurs et les div de drapeau restants
    document.querySelectorAll('.flex').forEach(function (flexDiv, index) {
        const selectElement = flexDiv.querySelector('select');
        const flagDivId = 'flag' + (index + 1);
        const selectedValue = selectElement.value;
        handleSelectChange(selectedValue, flagDivId);
    });
    // Mise à jour des identifiants des éléments restants
    updateTotalCharacters();
    updateRowIds();
    // Après l'ajout des éléments, faire correspondre les sélecteurs déjà présents aux divs de drapeau
    updateFlagDivAssociations();

}

// Fonction pour mettre à jour les associations entre les sélecteurs et les divs de drapeau
function updateFlagDivAssociations() {
    // Sélectionner tous les sélecteurs présents
    const selectElements = document.querySelectorAll('.country-select');
    // Pour chaque sélecteur
    selectElements.forEach(function (selectElement, index) {
        // Trouver le div de drapeau associé en utilisant la classe spécifique
        const flagDiv = selectElement.parentElement.querySelector('.flag');
        // Si le div de drapeau est trouvé
        if (flagDiv) {
            // Obtenir la valeur sélectionnée dans le sélecteur
            const selectedValue = selectElement.value;
            // Appeler la fonction pour mettre à jour l'image du drapeau avec la valeur sélectionnée
            updateFlagImage(selectedValue, flagDiv);

            // Mettre à jour les IDs des sélecteurs et des divs de drapeau pour éviter le décalage de 1
            const newIndex = index + 1;
            selectElement.id = 'countrySelect' + newIndex;
            flagDiv.id = 'flag' + newIndex;
        }
    });
}

function updateRowIds() {
    var remainingRows = document.querySelectorAll('.input-row');
    remainingRows.forEach(function (row, index) {
        var newIndex = index + 1;
        row.id = 'input-row-' + newIndex; // Mise à jour de l'identifiant

        // Mise à jour de l'identifiant de l'input date
        row.querySelector('input[type="date"]').id = 'date_' + newIndex;

        // Mise à jour de l'identifiant de la textarea
        row.querySelector('textarea').id = 'event_' + newIndex;

        // Mise à jour de l'identifiant du select
        row.querySelector('select').id = 'countrySelect' + newIndex;

        // Mise à jour de l'identifiant de la div de drapeau
        row.querySelector('.flag').id = 'flag' + newIndex;

        // Mise à jour de l'identifiant de la div de sélection de drapeau
        row.querySelector('.flex').id = 'selFlag' + newIndex;

        // Mise à jour de l'identifiant du bouton de suppression
        row.querySelector('.remove-btn').id = 'remove-btn-' + newIndex;

        // Mettre à jour l'événement de suppression avec le nouvel identifiant
        row.querySelector('.remove-btn').onclick = function () {
            removeInputs('input-row-' + newIndex);

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
    const totalCharactersElement = document.getElementById('totalCharacters');
    totalCharactersElement.textContent = totalCharacters;

    // Mettre à jour le texte de l'élément h4 selon la valeur de totalCharacters
    const signesElement = document.getElementById('signes');
    if (totalCharacters > 0) {
        signesElement.textContent = totalCharacters + " signes";
    } else {
        signesElement.textContent = "0 signe";
    }

    // Modifier la classe de l'élément en fonction du nombre total de caractères
    const elementsColorInfoClass = document.querySelectorAll('.colorInfo');
    const elementsColorInfoClassOK = document.querySelectorAll('.colorInfoOk');
    // if (totalCharacters >= 1110 && totalCharacters <= 1250) {
    //     elementsColorInfoClass.forEach(element => {
    //         element.classList.remove('colorInfo');
    //         element.classList.add('colorInfoOk');
    //     });
    //     document.getElementById('make').style.display = 'block'; // Afficher le bouton submit
    // } else {
    //     elementsColorInfoClassOK.forEach(element => {
    //         element.classList.remove('colorInfoOk');
    //         element.classList.add('colorInfo');
    //     });
    //     document.getElementById('make').style.display = 'none'; // Afficher le bouton submit
    // }
}