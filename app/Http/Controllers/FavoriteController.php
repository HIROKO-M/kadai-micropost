<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{


    public function store(Request $request, $id)
    {
        \Auth::user()->favorite_add($id);
        return redirect()->back();
    }


    public function destroy($id)
    {
        \Auth::user()->favorite_delete($id);
        return redirect()->back();
    }
}
