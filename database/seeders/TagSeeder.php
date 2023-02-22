<?php

namespace Database\Seeders;

use Database\Factories\TagFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // run the factory
        TagFactory::new()->count(10)->create();
    }
}
