<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;
    protected  $fillable = [
        'id',
        'name',
        'sku',
        'description',
        'price',
        'quantity',
    ];

    /**
     * Only these fields belong in the search index.
     *
     * Without this, Scout falls back to toArray() and indexes every column —
     * including id, price, quantity and timestamps — so a numeric search like
     * "3088" would match an unrelated product whose internal row id happened
     * to be 3088. Keep this list to fields a customer actually searches by.
     */
    public function toSearchableArray(): array
    {
        return [
            'name'        => (string) $this->name,
            'sku'         => (string) $this->sku,
            'description' => (string) $this->description,
        ];
    }
}
