<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('users')->insert([
      'fullName' => 'zxcv',
      'email' => 'zxcv@.zxcv.zxcv',
      'password' => bcrypt('zxcvzxcv'),
      'role_id' => 5,
      'userType' => 'admin',
      'isActivated' => 0,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s'),

    ]);
  }
}
