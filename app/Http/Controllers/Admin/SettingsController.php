<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\DataFormController;
use Illuminate\Support\Facades\Validator;
use App\Models\About;
use App\Models\Language;
use App\Models\Contact;
use App\Models\About_description;

class SettingsController extends Controller
{
    use DataFormController;

    public function addContact(Request $request) {
        $contact = Contact::first();

        if ($contact) {
            $contact->copy = $request->copy ? $request->copy : null;
            $contact->privacy = $request->privacy ? $request->privacy : null;
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
                "youtube" => $request->privacy ? $request->privacy : null,
                "x" => $request->x ? $request->x : null,
            ]);
        }

        if ($contact)
            return $this->jsondata(true, null, 'Your Contact page has updated successfully', [], []);

    }

    public function getAbout() {
        $about = About::first();

        return $this->jsondata(true,null, 'get Successfuly', [], ["about" => $about]);
    }
    public function getContact() {
        $contact = Contact::first();

        return $this->jsondata(true, null, 'get Successfuly', [], ["contact" => $contact]);
    }

    public function addAbout(Request $request) {
        $languages = Language::take(7)->get();
        $keys = $languages->pluck('symbol')->all(); // get all Languages key as array

        // validate ---------------------------
        $missingDescriptions = array_diff($keys, array_keys($request->descriptions ? $request->descriptions : [])); // compare keys with names keys to know whitch is missing

        if (!empty($missingDescriptions)) {  // If is there missing keys so show msg to admin with this language
            return $this->jsondata(false, null, 'Add failed', ['Please enter About description in (' . Language::where('symbol', reset($missingDescriptions))->first()->name . ')'], []);
        }
        foreach ($request->descriptions as $key => $value) {
            if (!$value)
                return $this->jsondata(false, null, 'Add failed', ['Please enter About description in (' . Language::where('symbol', $key)->first()->name . ')'], []);
        }
        // ----------------------------------------------------------------------------------------------------------------------

        About_description::truncate();

        foreach ($request->descriptions as $lang => $desc) {
            $ABOUT = About_description::create([
                "description" => $desc,
                'language_id' => Language::where('symbol', $lang)->first()->id,
            ]);
        }


        return $this->jsonData(true, true, 'About has been Updated successfuly', [], []);
    }

    public function getAboutDesc() {
        $about = About_description::with("language")->get();
        $about_obj = [];
        foreach ($about as $item) {
            $about_obj[$item->language->symbol] = $item->description;
        }

        return response()->json($about_obj, 200);
    }
}
