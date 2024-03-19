var inputCount = 1; // Compteur pour les ID uniques
var inputTaskCount = 1; // Compteur pour les ID uniques
var inputHourCount = 1; // Compteur pour les ID uniques
var inputPriceCount = 1; // Compteur pour les ID uniques
function addInputs() {
   
    var container = document.getElementById("inputs-container");
    // var countrySelect = document.getElementById("countrySelect");
    inputCount++; // Incrémenter le compteur
    inputTaskCount++; // Incrémenter le compteur
    inputHourCount++; // Incrémenter le compteur
    inputPriceCount++; // Incrémenter le compteur

    var inputRow = document.createElement("div");
    inputRow.classList.add("input-row");
    inputRow.id = "input-row-" + inputCount; // ID unique pour chaque ensemble

    var input1 = document.createElement("input");
    input1.id = "date_" + inputCount;
    input1.type="date";
    input1.name = "date[]";
    
    var textarea = document.createElement("textarea");
    textarea.id = "event_" + inputCount;
    textarea.rows="5";
    textarea.name = "event[]";
    textarea.placeholder = "Evènement";
    textarea.addEventListener('input', updateTotalCharacters);
    
    var removeBtn = document.createElement("button");
    removeBtn.classList.add("remove-btn");
    removeBtn.textContent = "+";
    removeBtn.onclick = function () {
        removeInputs(inputRow.id); // Passer l'ID de l'ensemble à supprimer
    };

    inputRow.appendChild(input1);
    inputRow.appendChild(textarea);
    inputRow.appendChild(removeBtn);
    container.appendChild(inputRow);
   
}

// @ Ajout su select
function select() {
    


// URL de l'API REST fournissant la liste des pays
const countriesAPI = 'https://restcountries.com/v3.1/all';

// Variable pour garder une trace du nombre de menus déroulants ajoutés
let dropdownCount = 1;

// Variable pour garder une trace du nombre de divs ajoutées
let divCount = 1;

// Fonction pour récupérer la liste des pays depuis l'API REST
async function fetchCountries() {
    try {
        const response = await fetch(countriesAPI);
        const data = await response.json();

        // Créer un nouvel élément select
        const selectElement = document.createElement('select');
        selectElement.id = 'countrySelect_' + dropdownCount; // Incrémenter l'ID
        selectElement.name = "country[]";
        dropdownCount++; // Incrémenter le compteur

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
        // Récupérer le conteneur
        const parentContainer = document.getElementById("input-row-" + dropdownCount);
        const firstDiv = document.getElementById("date_"+ dropdownCount);
        const sel = parentContainer.appendChild(selectElement);

        // Insérer le div avant le premier enfant du conteneur
         parentContainer.insertBefore(sel, firstDiv.nextSibling);
       
    
    } catch (error) {
        console.error('Une erreur s\'est produite :', error);
    }
}
// Ajouter un écouteur d'événements au bouton
const addCountrySelectBtn = document.getElementById('add-btn');
addCountrySelectBtn.addEventListener('click', fetchCountries);
};
// @ FIN Ajout su select


// Sert à supprimer un bloc -> Select | Date | Textarea  

function removeInputs(id) {
    var inputRow = document.getElementById(id);
    inputRow.parentElement.removeChild(inputRow);
}
// FIN Sert à supprimer un bloc -> Select | Date | Textarea  


// Fonction pour mettre à jour le nombre total de caractères
function updateTotalCharacters() {
let totalCharacters = 0;

// Sélectionner tous les textarea
const textAreas = document.querySelectorAll('textarea');

// Parcourir tous les textarea et ajouter la longueur de leur contenu au total
textAreas.forEach(textArea => {
totalCharacters += textArea.value.length;
});

// Afficher le nombre total de caractères
document.getElementById('totalCharacters').textContent = totalCharacters;
}
// FIN Fonction pour mettre à jour le nombre total de caractères