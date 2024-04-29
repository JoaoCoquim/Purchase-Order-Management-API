<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PurchaseOrderItem extends Pivot
{
    // Basic model representing the pivot table between products and purchase orders
    protected $table = 'purchase_order_items';
}
