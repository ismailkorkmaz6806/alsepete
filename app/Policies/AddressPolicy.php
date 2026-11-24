<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AddressPolicy
{
    public function view(User $user, Address $address): Response
    {
        return (int) $user->id === (int) $address->user_id
            ? Response::allow()
            : Response::deny('Bu adrese erişim yetkiniz bulunmamaktadır.');
    }

    public function update(User $user, Address $address): Response
    {
        return (int) $user->id === (int) $address->user_id
            ? Response::allow()
            : Response::deny('Bu adresi yalnızca adres sahibi güncelleyebilir.');
    }

    public function delete(User $user, Address $address): Response
    {
        return (int) $user->id === (int) $address->user_id
            ? Response::allow()
            : Response::deny('Bu adresi yalnızca adres sahibi silebilir.');
    }
}
