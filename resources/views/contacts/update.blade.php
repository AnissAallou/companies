<div class="modal-header">
    <h5 class="modal-title" id="editModalLabel">Modifier le contact</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <!-- Formulaire d'édition du contact -->
    <form action="{{ route('contacts.update', $contact->id) }}" method="POST">
        @csrf
        @method('PUT')
        <!-- Champs de formulaire pour l'édition des données -->
        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" value="{{ $contact->prenom }}" required><br><br>

        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="{{ $contact->nom }}" required><br><br>

        <label for="e_mail">Email:</label>
        <input type="email" id="e_mail" name="e_mail" value="{{ $contact->e_mail }}" required><br><br>

        <label for="organisation">Organisation:</label>
        <input type="text" id="organisation" name="organisation" value="{{ $contact->organisation->nom }}" required><br><br>

        <label for="adresse">Adresse:</label>
        <input type="text" id="adresse" name="adresse" value="{{ $contact->adresse }}" required><br><br>

        <label for="code_postal">Code Postal:</label>
        <input type="text" id="code_postal" name="code_postal" value="{{ $contact->code_postal }}" required><br><br>

        <label for="ville">Ville:</label>
        <input type="text" id="ville" name="ville" value="{{ $contact->ville }}" required><br><br>

        <label for="statut">Statut:</label>
        <select id="statut" name="statut" required>
            <option value="lead" {{ $contact->statut == 'lead' ? 'selected' : '' }}>Lead</option>
            <option value="prospect" {{ $contact->statut == 'prospect' ? 'selected' : '' }}>Prospect</option>
            <option value="client" {{ $contact->statut == 'client' ? 'selected' : '' }}>Client</option>
        </select><br><br>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
