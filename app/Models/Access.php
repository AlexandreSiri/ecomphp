<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Access extends Base {
    public int $id;
    public string $token;
    
    public int $userId;
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}