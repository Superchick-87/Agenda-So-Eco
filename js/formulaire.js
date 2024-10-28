// Fonction pour charger le fichier CSV et extraire les données des pays
async function loadCountriesCSV() {
    try {
        const response = await fetch('datas/pays.csv');
        const data = await response.text();
        const rows = data.split('\n');
        const countriesData = rows.map(row => row.split(','));
        countriesData.shift(); // Suppression de l'en-tête
        const countriesMap = {};
        countriesData.forEach(country => {
            const countryCode = country[2];
            const countryName = country[4];
            countriesMap[countryCode] = countryName;
        });
        return countriesMap;
    } catch (error) {
        console.error('Erreur lors du chargement du fichier CSV :', error);
        return null;
    }
}

async function addInputs() {
    const container = document.getElementById("inputs-container");
    const uniqueId = generateUniqueId();

    // Création de la ligne d'entrée
    const inputRow = document.createElement("div");
    inputRow.classList.add("input-row");
    inputRow.id = "input-row-" + uniqueId;
    inputRow.draggable = true;
    
    // Ajout des événements de drag-and-drop
    inputRow.addEventListener('dragstart', drag);
    inputRow.addEventListener('dragend', dragEnd);
    inputRow.addEventListener('dragover', allowDrop); // Permettre le drag sur l'élément
    inputRow.addEventListener('drop', drop); // Gestion du dépôt sur l'élément

    const selFlag = document.createElement("div");
    selFlag.classList.add("flex");
    selFlag.id = "selFlag" + uniqueId;

    // Création de l'élément date
    const dateInput = document.createElement("input");
    dateInput.id = "date_" + uniqueId;
    dateInput.type = "date";
    dateInput.name = "date[]";
    dateInput.onchange = function() {
        updateDayName(this);
    };

    // Création du select pour les pays
    const selectElement = document.createElement("select");
    selectElement.id = "country_" + uniqueId;
    selectElement.name = "country[]";
    selectElement.className = "country-select";
    selectElement.addEventListener('change', function () {
        handleSelectChange(this.value, 'flag' + uniqueId);
    });

    // Remplir le select avec les options des pays
    const countriesMap = await loadCountriesCSV();
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Sélectionner un pays';
    selectElement.appendChild(defaultOption);

    for (const countryCode in countriesMap) {
        const option = document.createElement("option");
        option.value = countryCode;
        option.textContent = countriesMap[countryCode];
        selectElement.appendChild(option);
    }

    // Drapeau du pays
    const flagDiv = document.createElement("div");
    flagDiv.classList.add("flag");
    flagDiv.id = "flag" + uniqueId;

    // Bouton de suppression
    const removeBtn = document.createElement("div");
    removeBtn.classList.add("remove-btn");
    removeBtn.textContent = "+";
    removeBtn.onclick = function () {
        removeInputs(inputRow.id);
    };

    // Bloc de la boîte contenant jour, espacement entre lettres et texte de l'événement
    const flexOpt = document.createElement("div");
    flexOpt.classList.add("flex_opt");

    // Création du conteneur bloc_jour
    const blocJour = document.createElement("div");
    blocJour.classList.add("bloc_jour");

    // Menu de sélection pour interlettrage
    const letterSpacingSelect = document.createElement("select");
    letterSpacingSelect.id = "letterSpacing_" + uniqueId;
    letterSpacingSelect.name = "letterSpacing[]";
    letterSpacingSelect.className = "agendaOpt";
    letterSpacingSelect.onchange = function () {
        updateLetterSpacing(this);
    };

    // Remplir le select avec les options d'espacement
    const letterSpacingOptions = {
        '0.25': '0.25 pt',
        '0.2': '0.20 pt',
        '0.15': '0.15 pt',
        '0.1': '0.10 pt',
        '0': '0 pt',
        '-0.1': '-0.10 pt',
        '-0.15': '-0.15 pt',
        '-0.2': '-0.20 pt',
        '-0.25': '-0.25 pt'
    };

    for (const value in letterSpacingOptions) {
        const option = document.createElement("option");
        option.value = value; // Utiliser la clé comme valeur
        option.textContent = letterSpacingOptions[value]; // Utiliser la valeur pour l'affichage
        letterSpacingSelect.appendChild(option);
    }

    // Création de l'affichage du jour
    const dayDisplay = document.createElement("h3");
    dayDisplay.id = "jour_nom" + uniqueId;
    dayDisplay.textContent = "Jour";  // Texte initial qui sera remplacé

    // Ajout du jour et du select dans `bloc_jour`
    blocJour.appendChild(letterSpacingSelect);
    blocJour.appendChild(dayDisplay);

    // Ajout de `bloc_jour` dans `flexOpt`
    flexOpt.appendChild(blocJour);

    // Zone de texte pour l'événement
    const eventTextArea = document.createElement("textarea");
    eventTextArea.id = "event_" + uniqueId;
    eventTextArea.classList.add("input-text");
    eventTextArea.name = "event[]";
    eventTextArea.rows = 5;
    eventTextArea.placeholder = "Evènement";
    eventTextArea.oninput = updateTotalCharacters;

    // Append des éléments dans la boîte
    selFlag.appendChild(dateInput);
    selFlag.appendChild(selectElement);
    selFlag.appendChild(flagDiv);
    selFlag.appendChild(removeBtn);

    flexOpt.appendChild(eventTextArea);
    inputRow.appendChild(selFlag);
    inputRow.appendChild(flexOpt);
    container.insertBefore(inputRow, document.getElementById("add-btn"));
}



// Fonction pour générer un ID unique
function generateUniqueId() {
    return Math.random().toString(36).substr(2, 9);
}

// Fonction pour mettre à jour l'image du drapeau
function updateFlagImage(countryCode, flagDivId) {
    const flagDiv = document.getElementById(flagDivId);
    flagDiv.style.backgroundImage = `url(./images/flags/${countryCode}.jpg)`;
}

// Fonction pour gérer la sélection de pays
async function handleSelectChange(value, flagDivId) {
    const countriesMap = await loadCountriesCSV();
    if (countriesMap) {
        updateFlagImage(value, flagDivId);
    }
}

// Fonction pour mettre à jour le nom du jour en français
function updateDayName(inputElement) {
    const dateValue = inputElement.value;
    if (dateValue) {
        const date = new Date(dateValue);
        const joursSemaine = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
        const jourNom = joursSemaine[date.getDay()];
        const jourElement = document.getElementById(inputElement.id.replace("date_", "jour_nom"));
        if (jourElement) {
            jourElement.textContent = jourNom;
        }
    }
}

// Fonction pour supprimer une boîte de saisie
function removeInputs(parentRowId) {
    const inputRow = document.getElementById(parentRowId);
    inputRow.remove();
    updateTotalCharacters();
}

// Fonction pour mettre à jour l'interlettrage d'une textarea
function updateLetterSpacing(selectElement) {
    const textAreaId = selectElement.id.replace("letterSpacing", "event");
    const textArea = document.getElementById(textAreaId);
    if (textArea) {
        textArea.style.letterSpacing = selectElement.value + 'pt';
    }
}

function updateTotalCharacters() {
    let totalCharacters = 0;

    // Compte le nombre total de caractères dans tous les textarea
    document.querySelectorAll('textarea').forEach(textArea => {
        totalCharacters += textArea.value.length;
    });

    // Met à jour le texte avec le nombre total de caractères
    const totalCharactersElement = document.getElementById('totalCharacters');
    totalCharactersElement.textContent = totalCharacters;

    // Affiche "signe" ou "signes" selon le nombre de caractères
    const signesElement = document.getElementById('signes');
    signesElement.textContent = totalCharacters > 1 ? totalCharacters + " signes" : totalCharacters + " signe";

    // Affiche ou masque le bouton "save" selon le nombre de caractères
    document.getElementById('save').style.display = totalCharacters > 1 ? 'block' : 'none';

    // Affiche ou masque le div "options" selon le nombre de caractères
    const optionsElement = document.getElementById('options');
    if (optionsElement) {  // Vérifiez si l'élément existe avant d'accéder à ses propriétés
        optionsElement.style.display = totalCharacters > 1 ? 'flex' : 'none';
    }

    const elementsColorInfoClass = document.querySelectorAll('.colorInfo');
    const elementsColorInfoClassOK = document.querySelectorAll('.colorInfoOk');

  
    // Gestion de l'affichage basé sur le nombre de caractères
    if (totalCharacters >= 1400 && totalCharacters <= 1500) {
        elementsColorInfoClass.forEach(element => {
            element.classList.replace('colorInfo', 'colorInfoOk');
            document.getElementById('make').style.display = 'block';
        });
    } else {
        elementsColorInfoClassOK.forEach(element => {
            element.classList.replace('colorInfoOk', 'colorInfo');
            document.getElementById('make').style.display = 'none';
        });
    }
}

