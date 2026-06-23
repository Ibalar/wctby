<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DeliveryMethod;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use MoonShine\Laravel\Models\MoonshineUser;
use MoonShine\Laravel\Models\MoonshineUserRole;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->seedMoonshineAdmin();
        $this->seedDeliveryMethods();
        $this->seedPaymentMethods();
        $this->seedCategoriesAndProducts();
        $this->seedTestUsersAndOrders();
    }

    private function seedMoonshineAdmin(): void
    {
        if (MoonshineUser::where('moonshine_user_role_id', MoonshineUserRole::DEFAULT_ROLE_ID)->exists()) {
            return;
        }

        $role = MoonshineUserRole::firstOrCreate(
            ['id' => MoonshineUserRole::DEFAULT_ROLE_ID],
            ['name' => 'Admin']
        );

        MoonshineUser::create([
            'email' => 'admin@moonshine.local',
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'moonshine_user_role_id' => $role->id,
        ]);
    }

    private function seedDeliveryMethods(): void
    {
        $methods = [
            ['name' => 'Самовывоз', 'code' => 'pickup', 'price' => 0, 'sort_order' => 1],
            ['name' => 'Курьер по Минску', 'code' => 'courier_minsk', 'price' => 10, 'sort_order' => 2],
            ['name' => 'Европочта', 'code' => 'europost', 'price' => 7, 'sort_order' => 3],
            ['name' => 'Белпочта', 'code' => 'belpost', 'price' => 5, 'sort_order' => 4],
        ];

        foreach ($methods as $method) {
            DeliveryMethod::firstOrCreate(
                ['code' => $method['code']],
                $method
            );
        }
    }

    private function seedPaymentMethods(): void
    {
        $methods = [
            ['name' => 'Наличные', 'code' => 'cash', 'sort_order' => 1],
            ['name' => 'Банковская карта', 'code' => 'card', 'sort_order' => 2],
            ['name' => 'ЕРИП', 'code' => 'erip', 'sort_order' => 3],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(
                ['code' => $method['code']],
                $method
            );
        }
    }

    private function seedCategoriesAndProducts(): void
    {
        if (Category::count() > 0) {
            return;
        }

        $categories = [
            'Смартфоны' => ['Apple', 'Samsung', 'Xiaomi'],
            'Ноутбуки' => ['Игровые', 'Офисные', 'Ультрабуки'],
            'Аудио' => ['Наушники', 'Колонки', 'Микрофоны'],
            'Аксессуары' => ['Чехлы', 'Зарядные устройства', 'Кабели'],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::factory()->create(['name' => $parentName]);

            foreach ($children as $childName) {
                $child = Category::factory()->childOf($parent)->create(['name' => $childName]);

                Product::factory()
                    ->count(rand(3, 6))
                    ->forCategory($child)
                    ->create()
                    ->each(function (Product $product) {
                        if (rand(0, 1)) {
                            Sku::factory()
                                ->count(rand(1, 3))
                                ->forProduct($product)
                                ->create();
                        }
                    });
            }
        }
    }

    private function seedTestUsersAndOrders(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $existingTestUsers = User::where('email', 'like', '%@example.com')
            ->where('email', '!=', 'admin@example.com')
            ->count();

        if ($existingTestUsers >= 5) {
            return;
        }

        $usersToCreate = 5 - $existingTestUsers;
        $users = User::factory($usersToCreate)->create();

        $allUsers = $users->push($admin);

        foreach ($allUsers as $user) {
            $orderCount = rand(0, 3);

            for ($i = 0; $i < $orderCount; $i++) {
                $order = Order::factory()
                    ->forUser($user)
                    ->create(['status' => fake()->randomElement(['new', 'processing', 'completed'])]);

                $products = Product::inRandomOrder()->limit(rand(1, 4))->get();

                foreach ($products as $product) {
                    $price = $product->base_price;
                    $quantity = rand(1, 3);

                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'item_type' => Product::class,
                        'item_id' => $product->id,
                        'name' => $product->name,
                        'price' => $price,
                        'quantity' => $quantity,
                        'line_total' => $price * $quantity,
                    ]);
                }
            }
        }
    }
}
