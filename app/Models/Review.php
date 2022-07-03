<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Review extends Base {
    protected static bool $paranoid = true;

    public int $id;
    public ?string $content;
    public int $note;
    
    public int $userId;
    public int $productId;
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function product() {
        return $this->belongsTo(Product::class);
    }
}