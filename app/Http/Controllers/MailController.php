<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TestMail; // mandatory to import
use Illuminate\Support\Facades\Mail; // madatory to import


class MailController extends Controller
{
    public function send(Request $request)
    {
        $data = [
            'name' => $request->input('name', 'User'),
            "info" => "Email: " . $request->input('email') . "\nSubject: " . $request->input('subject') . "\nMessage:\n" . $request->input('message'),
        ];

        try {
            Mail::to('maheshwaridhruva0@gmail.com')->send(new TestMail($data));
            return "success";
        } catch (\Exception $e) {
            return "error: " . $e->getMessage();
        }
    }
}
