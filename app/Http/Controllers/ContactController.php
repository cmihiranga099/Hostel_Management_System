<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contact; // Add this import for the Contact model

class ContactController extends Controller
{
    public function index()
    {
        return view('home.contact');
    }

    /**
     * Handle the contact form submission
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Save to database
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'submitted_at' => now()
            ]);

            // Optional: Send email notification
            // $contactData = [
            //     'name' => $request->name,
            //     'email' => $request->email,
            //     'subject' => $request->subject,
            //     'message' => $request->message,
            //     'submitted_at' => now()
            // ];

            // Send email to admin (optional)
            // Mail::send('emails.contact', $contactData, function($message) use ($contactData) {
            //     $message->to('admin@universityhostel.lk')
            //             ->subject('New Contact Form Submission: ' . $contactData['subject'])
            //             ->from($contactData['email'], $contactData['name']);
            // });

            return redirect()->back()->with('success', 'Thank you for your message! We have received it and will get back to you soon.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Contact form submission error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Sorry, there was an error processing your message. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show contact form with specific subject (optional)
     */
    public function show($subject = null)
    {
        return view('contact', compact('subject'));
    }
}