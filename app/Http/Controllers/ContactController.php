<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    public function store(Contact $contact)
    {
        $contact = new Contact();
        $contact->name = request()->get('name');
        $contact->email = request()->get('email');
        $contact->message = request()->get('message');
        $contact->save();

        return redirect('/#contact_section')->with('success', 'Message sent successfully!');
    }

    public function index(): View
    {
        return view('contacts.index', [
            'contacts' => Contact::get(),

        ]);
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect(route('contacts.index'))->with('success', 'Submission deleted!');
    }
}
