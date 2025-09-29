<?php
namespace Database\Seeders;
use App\Models\User; // tambahkan
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;  //tambahkan hash

class UserSeeder extends Seeder
{
 	public function run(): void
 	{
		$users = [
			[
				'username'=>'admin',
				'name'=>'Administrator',
				'email'=>'admin@gmail.com',
				'level'=>'admin',
				'password'=>Hash::make('123456')
			],
			[
				'username'=>'user1',
				'name'=>'Akun User1',
				'email'=>'user1@gmail.com',
				'level'=>'user',
				'password'=>Hash::make('123456')
			],
			[
				'username'=>'user2',
				'name'=>'Akun User2',
				'email'=>'user2@gmail.com',
				'level'=>'user',
				'password'=>Hash::make('123456')
			],
		];

		foreach ($users as $key => $value) {
			User::create($value);
		}
	}
}
