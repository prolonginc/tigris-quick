<?php

namespace App\QuickBooks;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Str;

/**
 * Applies QuickBooks Item state to the local products table.
 *
 * QuickBooks never hard-deletes an Item: "deleting" one marks it inactive and
 * renames it "<name> (deleted)". Inactive items are also excluded from Item
 * queries by default, so anything not returned by a full sync must be treated
 * as removed (see UpdateQuantityCommand).
 */
class ProductSync
{
    public static function isActive($item): bool
    {
        if (isset($item->Active) && $item->Active !== '') {
            if (! filter_var($item->Active, FILTER_VALIDATE_BOOLEAN)) {
                return false;
            }
        }

        return ! Str::contains((string) $item->Name, '(deleted)');
    }

    /**
     * Create or update the product for a QuickBooks item, restoring it if it
     * was previously removed and has since been reactivated.
     */
    public static function upsert($item): Product
    {
        $product = Product::withTrashed()->find($item->Id) ?? new Product(['id' => $item->Id]);

        $product->fill([
            'name' => $item->Name,
            'sku' => $item->Sku ?? null,
            'description' => $item->Description ?? '',
            'quantity' => $item->QtyOnHand ?? 0,
            'price' => $item->UnitPrice,
        ]);

        if ($product->trashed()) {
            $product->restore(); // also saves the filled attributes
        } else {
            $product->save();
        }

        return $product;
    }

    /**
     * Hide a product that is no longer sold. It is soft-deleted (Scout drops it
     * from the search index) so past orders keep their line items; any cart
     * rows for it are removed since it can no longer be ordered.
     */
    public static function deactivate(Product $product): void
    {
        Cart::where('product_id', $product->id)->delete();
        $product->delete();
    }
}
