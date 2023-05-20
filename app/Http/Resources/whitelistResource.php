<?php

namespace App\Http\Resources;

use App\Http\Controllers\whitelistController;
use App\Models\Admin;
use App\Models\whitelist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class whitelistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_rp' => $this->name_rp,
            'id_users' => $this->id_users,
            'naissance' => $this->naissance,
            'Profession' => $this->Profession,
            'savoir' => $this->savoir,
            'description' => $this->description,
            //** si lutisateur est admin afficher en dessous creat_at */
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y H:m') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:m') : null,
            'time' => $this->created_at->diffForHumans(),
            'timestanp' => $this->created_at->timestamp,
        ];
    }

    public function all(whitelist $whitelist)
    {
        return [
            'id' => $this->id,
            'name_rp' => $this->name_rp,
            'id_users' => $this->id_users,
            'naissance' => $this->naissance,
            'Profession' => $this->Profession,
            'savoir' => $this->savoir,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
