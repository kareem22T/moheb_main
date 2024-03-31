<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;
use App\Models\About;
use App\Models\Contact;

class SettingsController extends Controller
{
    use DataFormController;

    public function addContact(Request $request) {
        $contact = Contact::first();

        if ($contact) {
            $contact->phone = $request->phone ? $request->phone : null;
            $contact->email = $request->email ? $request->email : null;
            $contact->facebook = $request->facebook ? $request->facebook : null;
            $contact->instagram = $request->instagram ? $request->instagram : null;
            $contact->youtube = $request->youtube ? $request->youtube : null;
            $contact->x = $request->x ? $request->x : null;
            $contact->save();
        } else {
            $contact = Contact::create([
                "phone" => $request->phone ? $request->phone : null,
                "email" => $request->email ? $request->email : null,
                "facebook" => $request->facebook ? $request->facebook : null,
                "instagram" => $request->instagram ? $request->instagram : null,
                "youtube" => $request->youtube ? $request->youtube : null,
                "x" => $request->x ? $request->x : null,
            ]);
        }

        if ($contact)
            return $this->jsondata(true, null, 'Your Contact page has updated successfully', [], []);

    }

    public function getAbout() {
        $about = About::first();

        return $this->jsondata(true, 'get Successfuly', [], ["about" => $about]);
    }
    public function getContact() {
        $contact = Contact::first();

        return $this->jsondata(true, null, 'get Successfuly', [], ["contact" => $contact]);
    }
}
