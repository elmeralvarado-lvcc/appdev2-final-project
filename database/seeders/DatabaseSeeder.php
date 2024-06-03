<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Product;
use App\Models\Image;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::factory(4)
            ->hasVariants(5)
            ->has(Image::factory(3)->sequence(fn(Sequence $sequence) => ['featured' => $sequence->index === 0]))
            ->create();

        User::factory()->create([
            'name' => 'Elmer Alvarado',
            'email' => 'elmeralvarado@laverdad.edu.ph'
        ]);
    }
}
