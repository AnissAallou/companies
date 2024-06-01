<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    //

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $contacts = Contact::with('organisation')->get();
        return view('contacts.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'e_mail' => 'required|email|max:255',
            'organisation_id' => 'required|integer', // Assurez-vous que le champ "organisation" dans le formulaire envoie l'ID de l'organisation
            'adresse' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'ville' => 'required|string|max:255',
            'statut' => 'required|string|max:255',
        ]);

        // Création d'un nouveau contact avec les données validées
        $contact = new Contact();

        $contact->prenom = $validatedData['prenom'];
        $contact->nom = $validatedData['nom'];
        $contact->e_mail = $validatedData['e_mail'];
        $contact->organisation_id = $validatedData['organisation_id']; // Utilisez la colonne "organisation_id" pour définir la relation avec l'organisation
        $contact->adresse = $validatedData['adresse'];
        $contact->code_postal = $validatedData['code_postal'];
        $contact->ville = $validatedData['ville'];
        $contact->statut = $validatedData['statut'];

        // Sauvegarde du contact dans la base de données
        $contact->save();

        // Redirection avec un message de succès
        return redirect()->route('contacts.index')->with('success', 'Contact ajouté avec succès!');
    }

}
