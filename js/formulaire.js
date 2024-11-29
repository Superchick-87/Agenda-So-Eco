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
    try {
    

        const container = document.getElementById("inputs-container");
        const uniqueId = generateUniqueId();
        console.log("Generated uniqueId:", uniqueId);

        let addButton = document.getElementById("add-btn");
        if (!addButton) {
            addButton = document.createElement("div");
            addButton.classList.add("add-btn");
            addButton.id = "add-btn";
            addButton.onclick = addInputs;
            addButton.textContent = "+";
            container.appendChild(addButton);
        }

        // Création de la nouvelle boîte inputRow
        const inputRow = document.createElement("div");
        inputRow.classList.add("input-row");
        inputRow.id = "input-row-" + uniqueId;
        inputRow.draggable = false;  // Définir draggable sur true
        // Associer les événements de drag-and-drop
        inputRow.addEventListener('mousedown', startDrag);
        inputRow.addEventListener('dragstart', drag);
        inputRow.addEventListener('dragend', dragEnd);
        inputRow.addEventListener('dragover', allowDrop);
        inputRow.addEventListener('drop', drop);

        const selFlag = document.createElement("div");
        selFlag.classList.add("flex");
        selFlag.id = "selFlag" + uniqueId;

        const dateInput = document.createElement("input");
        dateInput.id = "date_" + uniqueId;
        dateInput.type = "date";
        dateInput.name = "date[]";
        dateInput.onchange = function () {
            updateDayName(this);
        };

        const selectElement = document.createElement("select");
        selectElement.id = "country_" + uniqueId;
        selectElement.name = "country[]";
        selectElement.className = "country-select";
        selectElement.addEventListener('change', function () {
            handleSelectChange(this.value, 'flag' + uniqueId);
        });

        const countriesMap = await loadCountriesCSV();
        console.log("Loaded countries:", countriesMap);

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

        const flagDiv = document.createElement("div");
        flagDiv.classList.add("flag");
        flagDiv.id = "flag" + uniqueId;

        // Bouton de basculement
        const toggleBtn = document.createElement("div");
        toggleBtn.classList.add("toggle-btn");
        toggleBtn.textContent = "-";  // Texte du bouton de basculement
        toggleBtn.onclick = function () {
            toggleVisibility(uniqueId); // Passer l'ID unique
        };

        const removeBtn = document.createElement("div");
        removeBtn.classList.add("remove-btn");
        removeBtn.textContent = "+";
        removeBtn.onclick = function () {
            removeInputs(inputRow.id);
        };

        const flexOpt = document.createElement("div");
        flexOpt.classList.add("flex_opt");

        const blocJour = document.createElement("div");
        blocJour.classList.add("bloc_jour");

        const flexOptPicto = document.createElement("div");
        flexOptPicto.classList.add("flex_opt_picto");

        const dragPicto = document.createElement("div");
        dragPicto.classList.add("picto_drag");

        const pictoOpt = document.createElement("div");
        pictoOpt.classList.add("picto_opt");
        pictoOpt.style.backgroundImage = "url('images/opti_letter_space.svg')";

        const letterSpacingSelect = document.createElement("select");
        letterSpacingSelect.id = "letterSpacing_" + uniqueId;
        letterSpacingSelect.name = "letterSpacing[]";
        letterSpacingSelect.className = "agendaOpt selopt";
        letterSpacingSelect.onchange = function () {
            updateLetterSpacing(this);
        };

        for (const value in letterSpacingOptions) {
            const option = document.createElement("option");
            option.value = value;
            option.textContent = letterSpacingOptions[value];
            letterSpacingSelect.appendChild(option);
        }

        flexOptPicto.appendChild(pictoOpt);

        
        
        flexOptPicto.appendChild(letterSpacingSelect);
        blocJour.appendChild(flexOptPicto);

        const dayDisplay = document.createElement("h3");
        dayDisplay.id = "jour_nom" + uniqueId;
        dayDisplay.textContent = "Jour";

        blocJour.appendChild(dragPicto);
        blocJour.appendChild(dayDisplay);
        flexOpt.appendChild(blocJour);

        const eventTextArea = document.createElement("textarea");
        eventTextArea.id = "event_" + uniqueId;
        eventTextArea.classList.add("input-text");
        eventTextArea.name = "event[]";
        eventTextArea.rows = 5;
        eventTextArea.placeholder = "Evènement";
        eventTextArea.oninput = updateTotalCharacters;

        selFlag.appendChild(dateInput);
        selFlag.appendChild(selectElement);
        selFlag.appendChild(flagDiv);
        selFlag.appendChild(toggleBtn);  // Ajoute le bouton de basculement
        selFlag.appendChild(removeBtn);

        flexOpt.appendChild(eventTextArea);
        inputRow.appendChild(selFlag);
        inputRow.appendChild(flexOpt);

        container.insertBefore(inputRow, addButton);
        inputRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        document.getElementById("go-down").style.display = "none";

        guillemets();
        console.log("Input added successfully with ID:", uniqueId);
        totalEvents();
       
        
    } catch (error) {
        console.error("Error in addInputs:", error);
    }
}


//@ Change les guillemets us en fr

function guillemets() {
    document.querySelectorAll('.input-text').forEach((textarea) => {
        textarea.addEventListener('input', function (event) {
            let content = textarea.value;
            
            // Supprimer les entités HTML &nbsp;
            content = content.replace(/&nbsp;/g, ' ');  // Remplacer &nbsp; par un espace normal

            // Remplacer les guillemets doubles par des guillemets français
            textarea.value = content.replace(/"([^"]*)"/g, '« $1 »');
        });
    });
}
    
//@ FIN - Change les guillemets us en fr

//@ Fonction pour masquer ou afficher les éléments

function toggleVisibility(uniqueId) {
    // Utiliser le bon uniqueId pour sélectionner les éléments à masquer/afficher
    const eventElement = document.getElementById("event_" + uniqueId);
    const blocJourElement = document.querySelector("#input-row-" + uniqueId + " .bloc_jour");
    const flexOptPictoElement = document.querySelector("#input-row-" + uniqueId + " .flex_opt_picto");
    const inputRow = event.target.closest('.input-row');
    
    const gragPictoElement = document.querySelector(" .picto_drag");

    // Vérifier si les éléments existent avant de changer leur affichage
    if (eventElement && blocJourElement && flexOptPictoElement) {
        const nomDuJour = document.getElementById("jour_nom" + uniqueId); // Récupérer le nom du jour
        if (eventElement.style.display === "none") {
            eventElement.style.display = "block";
            blocJourElement.style.display = "flex"; // Affiche bloc_jour
            flexOptPictoElement.style.display = "flex"; // Affiche flex_opt_picto
            blocJourElement.style.height = "134px"; // Affiche bloc_jour
            inputRow.setAttribute('draggable', 'false');
            
        } else {
            blocJourElement.style.display = "flex"; // Affiche bloc_jour
            blocJourElement.style.height = "auto"; // Affiche bloc_jour
            nomDuJour.style.display = "block"; // Gardez le nom du jour affiché
            gragPictoElement.style.display = "block";
            eventElement.style.display = "none";
            blocJourElement.style.display = "flex"; // Masque bloc_jour
            flexOptPictoElement.style.display = "none"; // Masque flex_opt_picto
            inputRow.setAttribute('draggable', 'true');
        }
    }
}

// Initialiser les boîtes déjà présentes avec la fonctionnalité de masquage
// document.querySelectorAll('.toggle-btn').forEach((btn) => {
//     // Utiliser une fonction pour capturer le bon ID au moment du clic
//     const uniqueId = btn.parentElement.id.replace('selFlag', '');
//     btn.onclick = function () {
//         toggleVisibility(uniqueId);
//     };
// });

//@ FIN - Fonction pour masquer ou afficher les éléments




// Fonction pour remonter en haut de la page
function goUp() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
// Fonction pour descendre en bas de la page
function goDown() {
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
}

// Fonction pour mettre à jour la position de #upDown et les boutons de défilement
function updateScrollButtons() {
    // Obtenir les éléments par la classe "input-row"
    const inputRows = document.getElementsByClassName("input-row");
    // console.log("Nombre d'input-row :", inputRows.length);

    const goUpButton = document.getElementById("go-up");
    const goDownButton = document.getElementById("go-down");
    const upDownButton = document.getElementById("upDown"); // Récupérer l'élément upDown

    // Récupérer les coordonnées de #inputs-container
    const container = document.getElementById('inputs-container');
    const rect = container.getBoundingClientRect();
    const xCoord = rect.x; // Coordonnée X par rapport à la fenêtre
    const containerWidth = rect.width; // Largeur de #inputs-container

    // Mettre à jour la position de #upDown
    upDownButton.style.left = (xCoord + containerWidth) + 150 + 'px'; // Ajouter la largeur à la coordonnée X

    // Vérifiez si la page est défilable
    const isScrollable = document.body.scrollHeight > window.innerHeight;
    const isAtTop = window.scrollY === 0;
    const isAtBottom = window.scrollY + window.innerHeight >= document.body.scrollHeight;

    console.log(inputRows.length);
    if (!isScrollable || inputRows.length < 3) {
        goUpButton.style.display = "none";
        goDownButton.style.display = "none";
    } else if (isAtTop) {
        goUpButton.style.display = "none";
        goDownButton.style.display = "block";
    } else if (isAtBottom) {
        goUpButton.style.display = "block";
        goDownButton.style.display = "none";
    }else {
        goUpButton.style.display = "block";
        goDownButton.style.display = "block";
    }
}

// Attacher l'événement de scroll
window.addEventListener("scroll", updateScrollButtons);
window.addEventListener("resize", updateScrollButtons); // Ajout pour le redimensionnement

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
    totalEvents();
}



// Fonction pour mettre à jour l'interlettrage d'une textarea
function updateLetterSpacing(selectElement) {
    const textAreaId = selectElement.id.replace("letterSpacing", "event");
    const textArea = document.getElementById(textAreaId);
    if (textArea) {
        textArea.style.letterSpacing = (selectElement.value) * 1.43 + 'pt';
    }
}

// Applique la mise à jour de l'interlettrage à tous les <select> ayant un id contenant 'letterSpacing'
function updateLetterSpacingForAll() {
    const letterSpacingElements = document.querySelectorAll("select[id*='letterSpacing']");
    letterSpacingElements.forEach(selectElement => {
        updateLetterSpacing(selectElement);
    });
}

function totalEvents() {
    // <p id="totalEvenements" style="display:none;">0</p>
    //      <h4 id="evenements"> évènements</h4>


    // console.log("Nombre d'input-row :", inputRows.length);
    
    // Met à jour le texte avec le nombre total de caractères
    const inputRows = document.getElementsByClassName("input-row");
    const totalEvenements = document.getElementById('totalEvenements');

    totalEvenements.innerHTML = inputRows.length;

    // Affiche "évènement" ou "évènements" selon le nombre de caractères
    const evenementsElement = document.getElementById('evenements');
    evenementsElement.textContent = inputRows.length > 1 ? totalEvenements.innerHTML + " évènements" : totalEvenements.innerHTML + " évènement";

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
            // document.getElementById('make').style.display = 'block';
        });
    } else {
        elementsColorInfoClassOK.forEach(element => {
            element.classList.replace('colorInfoOk', 'colorInfo');
            document.getElementById('make').style.display = 'none';
        });
    }
}




