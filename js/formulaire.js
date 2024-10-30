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

    // Vérifier si `add-btn` existe dans `inputs-container`
    let addButton = document.getElementById("add-btn");
    if (!addButton) {
        // Créer le bouton et l'ajouter dans le conteneur
        addButton = document.createElement("div");
        addButton.classList.add("add-btn");
        addButton.id = "add-btn";
        addButton.onclick = addInputs;
        addButton.textContent = "+";
        container.appendChild(addButton);
    }

    const inputRow = document.createElement("div");
    inputRow.classList.add("input-row");
    inputRow.id = "input-row-" + uniqueId;
    inputRow.draggable = true;
    inputRow.addEventListener('dragstart', drag);
    inputRow.addEventListener('dragend', dragEnd);
    inputRow.addEventListener('dragover', allowDrop);
    inputRow.addEventListener('drop', drop);

    const selFlag = document.createElement("div");
    selFlag.classList.add("flex");
    selFlag.id = "selFlag" + uniqueId;

    // Création de l'élément date
    const dateInput = document.createElement("input");
    dateInput.id = "date_" + uniqueId;
    dateInput.type = "date";
    dateInput.name = "date[]";
    dateInput.onchange = function () {
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

    // Conteneur pour l'icône et le select (espacement entre lettres)
    const flexOptPicto = document.createElement("div");
    flexOptPicto.classList.add("flex_opt_picto");

    // Icône d'interlettrage
    const pictoOpt = document.createElement("div");
    pictoOpt.classList.add("picto_opt");
    pictoOpt.style.backgroundImage = "url('images/opti_letter_space.svg')";

    // Menu de sélection pour interlettrage
    const letterSpacingSelect = document.createElement("select");
    letterSpacingSelect.id = "letterSpacing_" + uniqueId;
    letterSpacingSelect.name = "letterSpacing[]";
    letterSpacingSelect.className = "agendaOpt selopt";
    letterSpacingSelect.onchange = function () {
        updateLetterSpacing(this);
    };

    // Remplir le select avec les options d'espacement
    for (const value in letterSpacingOptions) {
        const option = document.createElement("option");
        option.value = value;
        option.textContent = letterSpacingOptions[value];
        letterSpacingSelect.appendChild(option);
    }

    // Ajout de l'icône et du select dans `flex_opt_picto`
    flexOptPicto.appendChild(pictoOpt);
    flexOptPicto.appendChild(letterSpacingSelect);

    // Ajout de `flex_opt_picto` dans `bloc_jour`
    blocJour.appendChild(flexOptPicto);

    // Création de l'affichage du jour
    const dayDisplay = document.createElement("h3");
    dayDisplay.id = "jour_nom" + uniqueId;
    dayDisplay.textContent = "Jour";

    // Ajout du jour et du select dans `bloc_jour`
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



    // Insérer avant `add-btn`
    container.insertBefore(inputRow, addButton);

    // Centrer la nouvelle boîte à l'écran
    inputRow.scrollIntoView({ behavior: 'smooth', block: 'center' });

    const goDownButton = document.getElementById("go-down");
    goDownButton.style.display = "none";
}

// Fonction pour remonter en haut de la page
function goUp() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
// Fonction pour descendre en bas de la page
function goDown() {
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
}

// Affiche ou cache le bouton "go-up" en fonction du défilement
function updateScrollButtons() {
    // Obtenir les éléments par la classe "input-row"
    const inputRows = document.getElementsByClassName("input-row");
    console.log("Nombre d'input-row :", inputRows.length);

    const goUpButton = document.getElementById("go-up");
    const goDownButton = document.getElementById("go-down");

    // Vérifiez si la page est défilable
    const isScrollable = document.body.scrollHeight > window.innerHeight;
    const isAtTop = window.scrollY === 0;
    const isAtBottom = window.scrollY + window.innerHeight >= document.body.scrollHeight;

    if (!isScrollable || inputRows.length === 0) {
        // Si la page n'est pas défilable ou s'il n'y a pas d'éléments "input-row", masquer les boutons
        goUpButton.style.display = "none";
        goDownButton.style.display = "none";
    } else if (isAtTop) {
        // En haut de la page : afficher seulement "go-down"
        goUpButton.style.display = "none";
        goDownButton.style.display = "block";
    } else if (isAtBottom) {
        // En bas de la page : afficher seulement "go-up"
        goUpButton.style.display = "block";
        goDownButton.style.display = "none";
    } else {
        // Entre le haut et le bas : afficher les deux boutons
        goUpButton.style.display = "block";
        goDownButton.style.display = "block";
    }
}

// Attacher l'événement de scroll
window.addEventListener("scroll", updateScrollButtons);

// Utiliser MutationObserver pour surveiller les changements dans les éléments "input-row"
const observer = new MutationObserver(updateScrollButtons);

// Configurer l'observateur pour surveiller les ajouts et suppressions d'éléments dans le conteneur parent
observer.observe(document.body, {
    childList: true,
    subtree: true
});

// Appeler la fonction au chargement initial
updateScrollButtons();

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



