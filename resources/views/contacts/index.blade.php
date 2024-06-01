<!DOCTYPE html>
<html>
<head>
    <title>Contacts</title>
    <meta charset="UTF-8">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<h1>Liste des contacts</h1>
<input type="text" id="search" placeholder="Recherche..." style="margin-bottom: 10px; width: 20%; border-radius: 4px;" onkeyup="filterContacts()">
<button id="addContactBtn" style="background-color: green; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer;">
    + Ajouter
</button>


<div class="table-container">
    <table border="1" id="contactsTable">
        <thead>
        <tr>
            <th>NOM DU CONTACT</th>
            <th>SOCIÉTÉ</th>
            <th>STATUT</th>
            <th>ACTIONS</th>
        </tr>
        </thead>
        <tbody id="tableBody">
        <?php $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($contact->prenom . ' ' . $contact->nom); ?></td>
            <td><?php echo e($contact->organisation->nom); ?></td>
            <td class="status"><?php echo e(ucfirst(strtolower($contact->organisation->statut))); ?></td>
            <td>
                <div class="action-buttons">
                    <button onclick="viewContact(<?php echo $contact->id; ?>)">
                        <i class="fas fa-eye" title="View"></i>
                    </button>
                    <button onclick="editContact(<?php echo e($contact->id); ?>)">
                        <i class="fas fa-edit" title="Edit"></i>
                    </button>
                    <button onclick="deleteContact(<?php echo e($contact->id); ?>)">
                        <i class="fas fa-trash" title="Delete"></i>
                    </button>
                </div>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<div id="paginationInfo"></div>
<div class="pagination">
    <button onclick="prevPage()"><</button>
    <span id="pageInfo"></span>
    <button onclick="nextPage()">></button>
</div>


<!-- Modal Structure -->
<!-- Modal Structure -->
<div id="contactModal" style="display: none; position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5);">
    <div style="background: white; margin: 100px auto; padding: 20px; width: 40%;">
        <h2>Détail du contact</h2>
{{--        <form id="contactForm" action="{{ route('contacts.store') }}" method="POST">--}}
        <form id="contactForm" action="{{ route('contacts.store') }}" method="POST">
            @csrf
            <div style="display: flex; justify-content: space-between;">
                <input type="text" id="firstName" name="prenom" placeholder="Prénom" style="width: 49%;">
                <input type="text" id="lastName" name="nom" placeholder="Nom" style="width: 49%;">
            </div>
            <input type="email" id="email" name="e_mail" placeholder="E-mail" style="width: 100%; margin-top: 10px;">
            <input type="text" id="company" name="organisation" placeholder="Entreprise" style="width: 100%; margin-top: 10px;">
            <input type="text" id="address" name="adresse" placeholder="Adresse" style="width: 100%; margin-top: 10px;">
            <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                <input type="text" id="postalCode" name="code_postal" placeholder="Code postal" style="width: 49%;">
                <input type="text" id="city" name="ville" placeholder="Ville" style="width: 49%;">
            </div>
            <select id="status" name="statut" style="width: 100%; margin-top: 10px;">
                <option value="lead">Lead</option>
                <option value="prospect">Prospect</option>
                <option value="client">Client</option>
            </select>
            <button type="submit" style="background-color: green; color: white; padding: 10px 20px; margin-top: 10px;">Soumettre</button>
            <button type="button" onclick="closeModal()" style="background-color: red; color: white; padding: 10px 20px; margin-top: 10px;">Fermer</button>
        </form>

        <!-- Affichage des messages d'erreur de validation -->
        @if ($errors->any())
            <div class="alert alert-danger" style="margin-top: 10px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Affichage du message de succès -->
        @if (session('success'))
            <div class="alert alert-success" style="margin-top: 10px;">
                {{ session('success') }}
            </div>
        @endif

    </div>
</div>

<div id="deleteModal" style="display: none; position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5);">
    <div style="border-radius: 8px; background: white; margin: 100px auto; padding: 20px; width: 40%;">
        <h1><i class="fas fa-exclamation-triangle" style="color: red"></i> Supprimer le contact</h1>
        <p>Êtes-vous sûr de vouloir supprimer ce contact ?</p>
        <p>Cette opération est irréversible.</p>
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            <button style="margin-right: 10px; background-color: #ced4da; color: #000; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;" onclick="cancelDelete()">Annuler</button>
            <button style="background-color: #dc3545; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;" onclick="confirmDelete()">Confirmer</button>
        </div>
    </div>
</div>
<div id="viewContactModal" style="display: none;">
    <!-- Contenu de la modale pour afficher les détails du contact -->
    <h2>Détails du contact</h2>
    <input type="text" id="firstName" disabled>
    <input type="text" id="lastName" disabled>
    <input type="email" id="email" disabled>
    <input type="text" id="company" disabled>
    <!-- Ajoutez d'autres champs si nécessaire -->
</div>




@vite('resources/js/app.js')
{{--<script src="index.js"></script>--}}

<script>

    function viewContact(id) {
        // Effectuez une requête AJAX pour récupérer les détails du contact du serveur
        fetch(`/contacts/${id}`)
            .then(response => response.json())
            .then(data => {
                // Remplissez les champs de la modale avec les données du contact
                document.getElementById('firstName').value = data.prenom;
                document.getElementById('lastName').value = data.nom;
                document.getElementById('email').value = data.e_mail;
                document.getElementById('company').value = data.organisation.nom;
                // Continuez avec d'autres champs si nécessaire

                // Affichez la modale de lecture
                document.getElementById('viewContactModal').style.display = 'block';
            })
            .catch(error => console.error('Erreur lors de la récupération des détails du contact :', error));
    }

    let currentPage = 1;
    const rowsPerPage = 10;
    const originalRows = Array.from(document.querySelectorAll('#contactsTable tbody tr'));


    function filterContacts() {
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

    // // Functions for button actions
    // function viewContact(id) {
    //     // Implement view contact logic here
    //     alert(`Viewing contact ${id}`);
    // }

    function editContact(id) {
        // Implement edit contact logic here
        alert(`Editing contact ${id}`);
    }

    function deleteContact(id) {
        // Implement delete contact logic here
        // alert(`Deleting contact ${id}`);
// Afficher la modale de confirmation de suppression
        document.getElementById('deleteModal').style.display = 'block';

        // Stocker l'ID du contact à supprimer dans une variable globale
        window.contactIdToDelete = id;
    }

    function confirmDelete() {
        // Récupérer l'ID du contact à supprimer
        const id = window.contactIdToDelete;

        // Implémenter la logique de suppression ici (envoi de la requête de suppression, etc.)

        // Une fois la suppression terminée, masquer la modale
        document.getElementById('deleteModal').style.display = 'none';
    }

    function cancelDelete() {
        // Masquer la modale de confirmation de suppression
        document.getElementById('deleteModal').style.display = 'none';

        // Réinitialiser l'ID du contact à supprimer
        window.contactIdToDelete = null;
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

</script>
</body>
</html>
