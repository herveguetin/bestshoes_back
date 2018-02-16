<?php
/**
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author Hervé Guétin <herve.guetin@gmail.com> <@herveguetin>
 * @copyright Copyright (c) 2018 Agence Soon (http://www.agence-soon.fr)
 */

namespace App\Http\Controllers;

class FindShoesController extends Controller
{
    public function send(\Illuminate\Http\Request $request)
    {
        $mail = new \App\Mail\ShoesList();
        $mail->args($request->all());
        \Illuminate\Support\Facades\Mail::to('miranda@runmway.com')->send($mail);
    }
}
