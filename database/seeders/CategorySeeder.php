<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       
       Category::create([
          'name' => 'Clothes'
       ]);
       Category::create([
            'name' => 'Shoes'
        ]);
        Category::create([
            'name' => 'Accessories'
        ]);

      
    }
}
