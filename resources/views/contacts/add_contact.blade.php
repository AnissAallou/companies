<!-- Ajoutez ce code dans add_contact.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Contact</title>
</head>
<body>
<h1>Add Contact</h1>
<form id="addContactForm">
    @csrf
    <label for="firstName">First Name:</label>
    <input type="text" id="firstName" name="prenom" required><br><br>

    <label for="lastName">Last Name:</label>
    <input type="text" id="lastName" name="nom" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="e_mail" required><br><br>

    <label for="company">Company:</label>
    <input type="text" id="company" name="organisation" required><br><br>

    <label for="address">Address:</label>
    <input type="text" id="address" name="adresse" required><br><br>

    <label for="postalCode">Postal Code:</label>
    <input type="text" id="postalCode" name="code_postal" required><br><br>

    <label for="city">City:</label>
    <input type="text" id="city" name="ville" required><br><br>

    <label for="status">Status:</label>
    <select id="status" name="statut" required>
        <option value="lead">Lead</option>
        <option value="prospect">Prospect</option>
        <option value="client">Client</option>
    </select><br><br>

    <button type="submit">Submit</button>
</form>

<script>
    document.getElementById('addContactForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche le formulaire de se soumettre normalement

        // Récupérer les données du formulaire
        const formData = new FormData(this);

        // Envoyer les données au serveur avec AJAX
        fetch('/contacts/store', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                // Afficher un message en fonction de la réponse
                if (data.success) {
                    alert('Contact ajouté avec succès!');
                    // Rediriger ou effectuer une autre action si nécessaire
                } else {
                    alert('Une erreur s\'est produite. Veuillez réessayer.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'ajout du contact:', error);
                alert('Une erreur s\'est produite. Veuillez réessayer.');
            });
    });
</script>
</body>
</html>
