<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmitted;
use App\Notifications\AdminNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Send email
        Mail::to(config('mail.admin_email', config('app.admin_email', 'admin@example.com')))
            ->send(new ContactFormSubmitted($validated));

        // Send notification to admin users
        $adminUsers = User::role('admin')->get();
        foreach ($adminUsers as $admin) {
            $admin->notify(new AdminNotification(
                'contact_form',
                'New Contact Form Submission',
                "New message from {$validated['name']}: {$validated['subject']}",
                route('admin.dashboard'),
                $validated
            ));
        }

        return redirect()->route('contact.create')
            ->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}