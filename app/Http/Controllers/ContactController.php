<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Exibir a lista de contatos
    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    // Exibir o formulário de criação de um novo contato
    public function create()
    {
        return view('contacts.create');
    }

    // Armazenar um novo contato no banco de dados
    public function store(Request $request)
    {
       // Validação dos campos do formulário
        $validatedData = $request->validate([
            'number' => 'required',
            'name' => 'required',
            'email' => 'email',
            'number2' => 'nullable', // Pode ser nulo
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // O campo de foto é opcional e deve ser uma imagem válida
        ]);

        // Crie uma instância do modelo Contact e preencha os campos com os dados validados
        $contact = new Contact();
        $contact->number = $validatedData['number'];
        $contact->name = $validatedData['name'];
        $contact->email = $validatedData['email'];
        $contact->number2 = $validatedData['number2'];

        // Verifique se um arquivo de foto foi enviado e, se sim, armazene-o
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $contact->photo = $photoPath;
        }

            // Salve o contato no banco de dados
            $contact->save();

        return redirect()->route('index');
    }

    // Exibir um contato específico
    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    // Exibir o formulário de edição de um contato
    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    // Atualizar um contato no banco de dados
    public function update(Request $request, Contact $contact)
    {
        // Valide os dados do formulário aqui, por exemplo:
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        $contact->update($validatedData);

        return redirect()->route('contacts.index');
    }

    // Excluir um contato do banco de dados
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index');
    }
}
