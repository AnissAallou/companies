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
<input type="text" id="search" placeholder="Recherche..." onkeyup="filterContacts()">
<button id="addContactBtn">
    + Ajouter
</button>


<div class="table-container">
    <table border="1" id="contactsTable">
        <thead>
        <tr>
            <th>NOM DU CONTACT</th>
            <th>SOCIÉTÉ</th>
            <th>STATUT</th>
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
<div id="contactModal">
    <div id="addContactModal">
        <h2>Détail du contact</h2>
        <form id="contactForm" action="{{ route('contacts.store') }}" method="POST">
            @csrf
            <div id="contactName">
                <input type="text" id="firstName" name="prenom" placeholder="Prénom">
                <input type="text" id="lastName" name="nom" placeholder="Nom">
            </div>
            <input type="email" id="email" name="e_mail" placeholder="E-mail">
            <input type="text" id="company" name="organisation" placeholder="Entreprise">
            <input type="text" id="address" name="adresse" placeholder="Adresse">
            <div id="#contactLocation">
                <input type="text" id="postalCode" name="code_postal" placeholder="Code postal">
                <input type="text" id="city" name="ville" placeholder="Ville">
            </div>
            <select id="status" name="statut">
                <option value="lead">Lead</option>
                <option value="prospect">Prospect</option>
                <option value="client">Client</option>
            </select>
            <button id="submit" type="submit">Soumettre</button>
            <button id="close" type="button" onclick="closeModal()">Fermer</button>
        </form>

        <!-- Affichage des messages d'erreur de validation -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Affichage du message de succès -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

    </div>
</div>

<div id="deleteModal">
    <div id="deleteModalFrame">
        <h1><i class="fas fa-exclamation-triangle"></i> Supprimer le contact</h1>
        <p>Êtes-vous sûr de vouloir supprimer ce contact ?</p>
        <p>Cette opération est irréversible.</p>
        <div>
            <button id="cancelDelete" onclick="cancelDelete()">Annuler</button>
            <button id="confirmDelete" onclick="confirmDelete()">Confirmer</button>
        </div>
    </div>
</div>


@vite('resources/js/app.js')
</body>
</html>
