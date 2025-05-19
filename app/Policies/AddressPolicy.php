<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;

class AddressPolicy
{
    /**
     * Determine if the user can update the address.
     */
    public function update(User $user, Address $address): bool
    {
        return $user->id === $address->user_id;
    }

    /**
     * Determine if the user can delete the address.
     */
    public function delete(User $user, Address $address): bool
    {
        return $user->id === $address->user_id;
    }
} 