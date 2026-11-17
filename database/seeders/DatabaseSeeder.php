<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Equipment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create(['name' => 'admin', 'email' => 'admin@example.com', 'password' => 'password']);
        $items = [
            ['Carbon Fiber Paddle', 'Paddles', 129.99, 'USAPA-ready carbon fiber paddle with a honeycomb core.', 'available'],
            ['Fiberglass Paddle', 'Paddles', 79.50, 'Durable fiberglass face with a comfortable ergonomic handle.', 'unavailable'],
            ['Indoor Pickleball Pack', 'Balls', 18.00, 'Pack of 12 indoor balls with a precision-drilled pattern.', 'available'],
            ['Outdoor Pickleball Pack', 'Balls', 22.00, 'Pack of 12 outdoor balls built for wind and hard courts.', 'available'],
            ['Portable Pickleball Net', 'Nets', 189.00, 'Portable regulation-height net with a sturdy carry case.', 'available'],
            ['Replacement Net', 'Nets', 95.00, 'Replacement mesh net compatible with standard portable frames.', 'unavailable'],
            ['Paddle Grip Tape', 'Accessories', 8.99, 'Sweat-absorbing replacement grip tape for any paddle.', 'available'],
            ['Pickleball Equipment Bag', 'Accessories', 54.75, 'Multi-compartment bag for paddles, balls, and accessories.', 'unavailable'],
        ];
        foreach ($items as [$name, $category, $price, $description, $status]) $user->equipment()->create(['equipment_name' => $name, 'category' => $category, 'unit_price' => $price, 'description' => $description, 'availability_status' => $status]);
    }
}
