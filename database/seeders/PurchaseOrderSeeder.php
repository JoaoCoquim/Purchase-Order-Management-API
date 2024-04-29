<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        foreach ($customers as $customer) {
            //Create multiple purchase orders for each customer
            $purchaseOrders = PurchaseOrder::factory(2)->create([
                'customer_id' => $customer->id,
            ]);

            //Selecting 3 random products to use in each order (to simulate a random order)
            $selectedProducts = $products->random(3);

            foreach ($purchaseOrders as $purchaseOrder) {
                foreach ($selectedProducts as $product) {
                    //Create purchase order items to associate selected products with the current purchase order
                    PurchaseOrderItem::query()->create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'product_id' => $product->id,
                    ]);
                }
            }
        }
    }
}
