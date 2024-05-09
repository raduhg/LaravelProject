<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Mail;

// class EmailController extends Controller
// {
//     public function sendResponse(Request $request)
//     {
//         $subject = $request->input('subject');
//         $content = $request->input('content');
//         $mail_to = $request->input('mail_to');
//         $email_data = [
//             'subject' => $subject,
//             'content' => $content,
//             'mail_to' => $mail_to,
//         ];
//         return view('emails.response-email', $email_data);
//         //Mail::to('recipient@example.com')->send(new ResponseEmail($email_data));  
//     }
// } 
