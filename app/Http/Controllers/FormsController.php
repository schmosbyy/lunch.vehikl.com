<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormsController extends Controller
{

    public function saveURLS(Request $request, Form $form)
    {
        $validated = $request->validate([
            'formUrls.inoffice-orders' => 'required',//|url
            'formUrls.outoftown-orders' => 'required',
            'formUrls.nextweek-votes' => 'nullable',
        ]);
        dd($validated);
        $form->update($validated);

        return response()->json("Success", 201);
    }

}
