<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StatesSeeder::class);
        $this->call(SymptomsSeeder::class);
        $this->call(ProductsSeeder::class);
        $this->call(AccountsSeeder::class);
        $this->call(QuestionsSeeder::class);
        $this->call(PhotosSeeder::class);
    }
}
