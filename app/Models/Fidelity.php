<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Fidelity extends Base {
    public int $id;
    public int $points;

    public int $userId;

    public function user() {
        return $this->belongsTo(User::class);
    }
}