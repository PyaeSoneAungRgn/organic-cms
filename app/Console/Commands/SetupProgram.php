<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupProgram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:program';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Program';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('migrate:fresh');

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
        ]);

        foreach($this->getCategories() as $category) {
            Category::create($category);
        }

        foreach($this->getProducts() as $product) {
            Product::create($product);
        }

        Customer::factory()->count(100)->create();

        foreach(range(1, 100) as $i) {
            $order = Order::create([
                'order_id' => 'OG-' . rand(100000, 999999),
                'customer_id' => rand(1, 100),
                'note' => '',
            ]);
            $randProduct = Product::inRandomOrder()->first();
            $qty = rand(1, 10);
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $randProduct->id,
                'quantity' => $qty,
                'unit_price' => $randProduct->price,
            ]);
            $order->update([
                'total_price' => $randProduct->price * $qty,
            ]);
        }
    }

    protected function getCategories()
    {
        return [
            [
                'name' => 'Fruits',
                'slug' => str()->slug('Fruits'),
                'show' => true
            ],
            [
                'name' => 'Vegetables',
                'slug' => str()->slug('Vegetables'),
                'show' => true
            ],
            [
                'name' => 'Flowers',
                'slug' => str()->slug('Flowers'),
                'show' => true
            ],
            [
                'name' => 'Herbs',
                'slug' => str()->slug('Herbs'),
                'show' => true
            ],
        ];
    }

    public function getProducts()
    {
        return [
            [
                'name' => 'Stawberry',
                'slug' => str()->slug('Stawberry'),
                'category_id' => 1,
                'quantity' => rand(10, 100),
                'price' => 1500,
            ],
            [
                'name' => 'Coconuts',
                'slug' => str()->slug('Coconuts'),
                'category_id' => 1,
                'quantity' => rand(10, 100),
                'price' => 2000,
            ],
            [
                'name' => 'Watermelon',
                'slug' => str()->slug('Watermelon'),
                'category_id' => 1,
                'quantity' => rand(10, 100),
                'price' => 3000,
            ],
            [
                'name' => 'Avocado',
                'slug' => str()->slug('Avocado'),
                'category_id' => 1,
                'quantity' => rand(10, 100),
                'price' => 2500,
            ],
            [
                'name' => 'Carrot',
                'slug' => str()->slug('Carrot'),
                'category_id' => 2,
                'quantity' => rand(10, 100),
                'price' => 800,
            ],
            [
                'name' => 'Corn',
                'slug' => str()->slug('Corn'),
                'category_id' => 2,
                'quantity' => rand(10, 100),
                'price' => 500,
            ],
            [
                'name' => 'Eggplant',
                'slug' => str()->slug('Eggplant'),
                'category_id' => 2,
                'quantity' => rand(10, 100),
                'price' => 400,
            ],
            [
                'name' => 'Chayote',
                'slug' => str()->slug('Chayote'),
                'category_id' => 2,
                'quantity' => rand(10, 100),
                'price' => 400,
            ],
            [
                'name' => 'Gandamar',
                'slug' => str()->slug('Gandamar'),
                'category_id' => 3,
                'quantity' => rand(10, 100),
                'price' => 3500,
            ],
            [
                'name' => 'Gladiolus',
                'slug' => str()->slug('Gladiolus'),
                'category_id' => 3,
                'quantity' => rand(10, 100),
                'price' => 2000,
            ],
            [
                'name' => 'Leek',
                'slug' => str()->slug('Leek'),
                'category_id' => 4,
                'quantity' => rand(10, 100),
                'price' => 6000,
            ],
            [
                'name' => 'Basil',
                'slug' => str()->slug('Basil'),
                'category_id' => 4,
                'quantity' => rand(10, 100),
                'price' => 1000,
            ],
        ];
    }
}
