<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Address extends Base {
    protected static bool $paranoid = true;

    public int $id;
    public string $name;
    public string $street;
    public string $postal;
    public string $city;
    public string $country;
    public ?bool $principal;
    
    public ?int $userId;
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}