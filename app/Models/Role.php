<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Role extends Base {
    public int $id;
    public string $role;
    public string $label;
}