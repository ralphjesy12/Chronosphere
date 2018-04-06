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
        factory(App\User::class)->create([
            'name' => 'Administrator',
            'email' => 'webdeveloper@elevationpartners.com.ph',
            'password' => bcrypt('storysolutions2014webdev')
        ]);
    }
}
