import './bootstrap';

let currentPage = 1;
const rowsPerPage = 10;
const originalRows = Array.from(document.querySelectorAll('#contactsTable tbody tr'));

export function filterContacts() {
    const searchInput = document.getElementById('search').value.toLowerCase();
    if (searchInput === "") {
        renderTable(originalRows);
        currentPage = 1; // Reset to the first page
        showPage(currentPage, originalRows.length);
        return;
    }

    const filteredRows = originalRows.filter(row => {
        const tdName = row.getElementsByTagName('td')[0];
        const tdCompany = row.getElementsByTagName('td')[1];
        const tdStatus = row.getElementsByTagName('td')[2];
        const nameValue = tdName.textContent.toLowerCase();
        const companyValue = tdCompany.textContent.toLowerCase();
        const statusValue = tdStatus.textContent.toLowerCase();

        return nameValue.includes(searchInput) || companyValue.includes(searchInput) || statusValue.includes(searchInput);
    });

    renderTable(filteredRows);
    currentPage = 1; // Reset to the first page after filtering
    showPage(currentPage, filteredRows.length);
}

function renderTable(rows) {
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = "";
    rows.forEach(row => {
        tableBody.appendChild(row);
    });
    applyStatusStyles();
}
function sanitizeInput(value) {
    const sanitizedValue = parseInt(value);
    if (isNaN(sanitizedValue) || sanitizedValue < 0) {
        return 0; // Si la valeur n'est pas un nombre valide ou est négative, retourne 0
    }
    return sanitizedValue;
}

function showPage(page, totalRows) {
    page = sanitizeInput(page); // Assurez-vous que la page est un nombre valide
    totalRows = sanitizeInput(totalRows); // Assurez-vous que le total des lignes est un nombre valide

    const table = document.getElementById('contactsTable');
    const tr = table.getElementsByTagName('tr');
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    page = Math.max(1, Math.min(page, totalPages)); // Assurer que la page est dans l'intervalle valide

    let firstRowDisplayed = (page - 1) * rowsPerPage + 1; // Calcul initial pour la première ligne affichée
    let lastRowPageDisplayed = Math.min(page * rowsPerPage, totalRows);
    let nbTotalRowsFromRequest = totalRows;

    // Condition pour ajuster l'affichage en cas d'aucun résultat ou erreurs de saisie
    if (totalRows === 0 || firstRowDisplayed > lastRowPageDisplayed) {
        firstRowDisplayed = 0;
        lastRowPageDisplayed = 0;
    }

    for (let i = 1; i < tr.length; i++) {
        if (i >= firstRowDisplayed && i <= lastRowPageDisplayed) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }

    document.getElementById('pageInfo').textContent = `Page ${page} sur ${totalPages}`;
    document.getElementById('paginationInfo').textContent = `Showing ${firstRowDisplayed} to ${lastRowPageDisplayed} of ${nbTotalRowsFromRequest} results`;
}


function prevPage() {
    const totalRows = document.querySelectorAll('#contactsTable tbody tr').length;
    if (currentPage > 1) {
        currentPage--;
        showPage(currentPage, totalRows);
    }
}

function nextPage() {
    const totalRows = document.querySelectorAll('#contactsTable tbody tr').length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    if (currentPage < totalPages) {
        currentPage++;
        showPage(currentPage, totalRows);
    }
}

// Functions for button actions
function viewContact(id) {
    // Implement view contact logic here
    alert(`Viewing contact ${id}`);
}

function editContact(id) {
    // Implement edit contact logic here
    alert(`Editing contact ${id}`);
}

function deleteContact(id) {
    // Implement delete contact logic here
    alert(`Deleting contact ${id}`);
}

// Helper function to capitalize the first letter
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}

// Function to apply styles to status cells
function applyStatusStyles() {
    const statusCells = document.querySelectorAll('.status');
    statusCells.forEach(cell => {
        const statusText = cell.textContent.toLowerCase();
        if (statusText === 'lead') {
            cell.classList.add('status-lead');
        } else if (statusText === 'client') {
            cell.classList.add('status-client');
        } else if (statusText === 'prospect') {
            cell.classList.add('status-prospect');
        }
    });
}

// Format all status texts and apply styles initially
originalRows.forEach(row => {
    const tdStatus = row.getElementsByTagName('td')[2];
    tdStatus.textContent = capitalizeFirstLetter(tdStatus.textContent);
});

applyStatusStyles();

// Initial display
renderTable(originalRows);
showPage(currentPage, originalRows.length);



// Assurez-vous de mettre à jour les informations lors de la première charge et après chaque action qui modifie l'affichage
showPage(1, originalRows.length);

document.getElementById('addContactBtn').addEventListener('click', function() {
    document.getElementById('contactModal').style.display = 'block';
});

function closeModal() {
    document.getElementById('contactModal').style.display = 'none';
}

function submitContactForm() {
    closeModal(); // Ferme la modale après soumission

    const form = document.getElementById('contactForm');
    const route = form.getAttribute('data-route');

    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Empêche le comportement par défaut du formulaire (rechargement de la page)

        // Récupère les données du formulaire
        const formData = new FormData(form);

        // Envoie les données au serveur avec fetch
        fetch(route, {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (response.ok) {
                    // Si l'insertion est réussie, ferme la modal
                    closeModal();
                    // Recharge la page pour afficher les nouveaux contacts
                    location.reload();
                } else {
                    // Si quelque chose ne va pas, affiche une erreur
                    alert('Une erreur s\'est produite lors de l\'insertion.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la tentative d\'insertion :', error);
                alert('Une erreur s\'est produite lors de l\'insertion.');
            });
    });
}
