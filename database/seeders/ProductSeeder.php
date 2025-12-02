<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;      //Agar laravel kenal tabel product

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Indomie Goreng Plus Telur',
            'price' => 10000,
            'description' => 'Indomie goreng dengan tambahan telur',
            'image' => 'https://placehold.co/400x300'
        ]);

        Product::create([
            'name' => 'Es Kopi Susu Gula Aren',
            'price' => 15000,
            'description' => 'Kopi susu kekinian, gula aren asli.',
            'image' => 'https://placehold.co/400x300'
        ]);

        Product::create([
            'name' => 'Paket Begadang',
            'price' => 20000,
            'description' => 'Kopi + Roti Bakar, cocok buat rental PS.',
            'image' => 'https://placehold.co/400x300'
        ]);
    }
}
