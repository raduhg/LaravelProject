<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Mail\ContactFormReply;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Store a new contact form submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        Contact::create($validated);

        return redirect('/#contact_section')->with('success', 'Message sent successfully!');
    }

    /**
     * Display a listing of the contact messages for the admin.
     */
    public function index(): View
    {
        return view('contacts.index', [
            'contacts' => Contact::latest()->get(),
        ]);
    }

    /**
     * Send a reply to a contact form submission.
     */
    public function sendReply(Request $request, Contact $contact): RedirectResponse
    {
        $validated = $request->validate([
            'reply_message' => 'required|string',
        ]);

        Mail::to($contact->email)->send(new ContactFormReply($contact, $validated['reply_message']));

        return redirect()->route('contacts.index')->with('success', 'Reply sent successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect(route('contacts.index'))->with('success', 'Submission deleted!');
    }
}