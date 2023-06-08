<?php

namespace App\Http\Controllers;

use App\Models\users;
use App\Models\whitelist;
use Illuminate\Support\Str;
use Termwind\Components\Dd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class whitelistController extends Controller
{
    public function create(Request $request)
    {
        $whitelist = new whitelist();
        $whitelist->name_rp = $request->name_rp;
        $whitelist->id_users = $request->id_users;
        $whitelist->naissance = $request->dateofnaissance;
        $whitelist->Profession = $request->profession;
        $whitelist->savoir = $request->savoir;
        $whitelist->description = $request->description;
        $whitelist->slug = Str::slug($request->name_rp);
        $whitelist->save();
        Log::notice("Demande de whitelist de " . $request->name_rp . " par " . $request->id_users);
        users::where('id', $request->id_users)->update(['whiteList' => 2]);
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
        Log::notice("Modification de la demande de whitelist de " . $request->name_rp . " par " . $request->id_users);
        $whitelist->save();
    }

    public function delete($id)
    {
        $whitelist = whitelist::find($id);
        $whitelist->delete();
        Log::notice("Suppression de la demande de whitelist de " . $whitelist->name_rp . " par " . $whitelist->id_users);
    }

    public function linkUser($id)
    {
        $whitelist = whitelist::where('id_users', $id)->first();
        return $whitelist;
    }

    public function view(Request $request)
    {

        $whitelist = whitelist::where('slug', $request->slug)->first();
        return $whitelist;
    }

    public function viewAll()
    {
        $whitelist = whitelist::all();
        return $whitelist;
    }

    public function count_whitelist_attente()
    {
        $users = users::where('whiteList', 2)->count();
        return $users;
    }

    public function count_whitelist_accepte()
    {
        $users = users::where('whiteList', 3)->count();
        return $users;
    }

    public function update_users_whitelist($id, $number)
    {

        $users = users::where('id', $id )->update(['whiteList' => $number]);
        return $users;
    }
}
