<?php

namespace App\Http\Controllers;

use App\Models\whitelist;
use Illuminate\Http\Request;

class whitelistController extends Controller
{
    public function create(Request $request)
    {
        $whitelist = new whitelist();
        $whitelist->name_rp = $request->name_rp;
        $whitelist->id_users = $request->id_users;
        $whitelist->naissance = $request->naissance;
        $whitelist->Profession = $request->Profession;
        $whitelist->savoir = $request->savoir;
        $whitelist->description = $request->description;
        $whitelist->save();
    }

    public function update(Request $request, $id)
    {
        $whitelist = whitelist::find($id);
        $whitelist->name_rp = $request->name_rp;
        $whitelist->id_users = $request->id_users;
        $whitelist->naissance = $request->naissance;
        $whitelist->Profession = $request->Profession;
        $whitelist->savoir = $request->savoir;
        $whitelist->description = $request->description;
        $whitelist->save();
    }

    public function delete($id)
    {
        $whitelist = whitelist::find($id);
        $whitelist->delete();
    }

    public function linkUser($id)
    {
        $whitelist = whitelist::where('id', $id)->first();
        $name = trim($whitelist->name_rp);
        $id_demande = $whitelist->id;
        $url_whitelist = "http://127.0.0.1:8000/whitelist/$name/$id_demande";
        return $url_whitelist;
    }

}
