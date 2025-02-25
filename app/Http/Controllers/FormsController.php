<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormsController extends Controller
{

    public function saveURLS(Request $request)
    {
        $validated = $request->validate([
            'formUrls.inoffice' => ['required'],//|url
            'formUrls.outoftown' => ['required'],
            'formUrls.nextweek' => ['nullable'],
        ]);
        // Store each URL properly
        Form::updateOrCreate(
            ['name' => 'inoffice'], // Search criteria
            ['form_url' => $validated['formUrls']['inoffice']] // Update values
        );

        Form::updateOrCreate(
            ['name' => 'outoftown'],
            ['form_url' => $validated['formUrls']['outoftown']]
        );

        Form::updateOrCreate(
            ['name' => 'nextweek'],
            ['form_url' => $validated['formUrls']['nextweek']]
        );
        return back();
    }

}
