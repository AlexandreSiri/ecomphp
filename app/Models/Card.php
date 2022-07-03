<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Card extends Base {
    protected static bool $paranoid = true;

    public int $id;
    public ?bool $principal;
    
    public int $userId;
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}