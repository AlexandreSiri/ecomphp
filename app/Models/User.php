<?php

namespace App\Models;

use App\Helpers\Models\Base;
use DateTime;

class User extends Base {
    protected static bool $paranoid = true;
    // protected static string $table = "users";
    
    public int $id;
    public string $username;
    public string $email;
    public string $password;
    public string $firstname;
    public string $lastname;
    public ?DateTime $birthAt = null;
    public int $roleId;

    public function addresses() {
        return $this->hasMany(Address::class);
    }
    public function resets() {
        return $this->hasMany(Reset::class);
    }
    public function accesses() {
        return $this->hasMany(Access::class);
    }
    public function cards() {
        return $this->hasMany(Card::class);
    }
    public function role() {
        return $this->belongsTo(Role::class);
    }
    public function fidelity() {
        return $this->hasOne(Fidelity::class);
    }
    public function reviews() {
        return $this->hasMany(Review::class);
    }
    public function orders() {
        return $this->hasMany(Order::class);
    }

    // public function posts() {
    //     return $this->hasMany(Post::class, [
    //         "authorId" => $this->id
    //     ]);
    // }
    // public function roles() {
    //     return $this->hasManyThrough(Role::class, "user_roles", [
    //         "userId" => $this->id
    //     ], [
    //         "roleId" => "id"
    //     ]);
    // }
    // public function addRole(Role $role) {
    //     return $this->addThrough($role, "user_roles", [
    //         "userId" => $this->id
    //     ], [
    //         "roleId" => $role->id
    //     ]);
    // }
    // public function removeRole(Role $role) {
    //     return $this->removeTrough($role, "user_roles", [
    //         "userId" => $this->id
    //     ], [
    //         "roleId" => $role->id
    //     ]);
    // }
}