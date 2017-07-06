<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Incident;
use App\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(IncidentsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
    }
}

class UsersTableSeeder extends Seeder {
	public function run() {
		User::create (
			[
				'id' => 1,
				'name' => 'Test Admin',
				'email' => 'testadmin@cityofloveland.org',
				'password' => Hash::make('password'),
				'role_id' => 1,
			]
		);

		User::create (
			[
				'id' => 2,
				'name' => 'Test User',
				'email' => 'testuser@cityofloveland.org',
				'password' => Hash::make('password'),
				'role_id' => 2,
			]
		);
	}
}

class IncidentsTableSeeder extends Seeder {
	public function run() {
		Incident::create (
			[
				'id' => 1,
				'date' => '2017-01-01',
				'title' => 'Test Incident #1',
				'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eu orci nisi. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vehicula sapien id leo fringilla, efficitur scelerisque mauris pellentesque. Nunc sem enim, facilisis venenatis elit a, pharetra dignissim eros. Vestibulum iaculis risus nec leo ullamcorper, non porttitor ligula tempor. Nulla a ex eu metus laoreet porttitor et non arcu. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aenean augue risus, vulputate nec efficitur elementum, semper in dui. Aliquam erat volutpat. Mauris placerat, odio at fringilla tincidunt, risus magna tincidunt diam, a commodo diam dui dignissim libero. Nam aliquam vehicula dui, a dapibus felis iaculis dictum. Nunc tristique tincidunt dolor, eget bibendum risus dictum ac. Suspendisse semper quis nunc in varius. Morbi sed pulvinar odio. Ut congue vitae ex sed blandit. In hac habitasse platea dictumst. Mauris sit amet orci at felis commodo vulputate id vel est. Vestibulum semper eget erat vel consequat. Suspendisse dictum risus elit, et vehicula felis elementum ut. Duis molestie lorem non velit malesuada, porta fermentum metus consectetur. Sed cursus tellus at justo imperdiet, ac varius neque vehicula. Etiam feugiat ante sit amet maximus aliquet. Phasellus pulvinar maximus tincidunt. Nullam faucibus dui sem, et tincidunt urna commodo mollis. Maecenas id ultricies ex, et elementum eros. Donec ultricies, diam vitae molestie tristique, dolor risus maximus elit, nec congue quam tortor vitae leo. Vivamus a libero ac sapien consectetur dictum at congue libero. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
				'patron_description' => 'Grey hoodie, average height and build',
				'user_id' => 1,
			]
		);

		Incident::create (
			[
				'id' => 2,
				'date' => '2017-07-29',
				'title' => 'Test Incident #2',
				'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eu orci nisi. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vehicula sapien id leo fringilla, efficitur scelerisque mauris pellentesque. Nunc sem enim, facilisis venenatis elit a, pharetra dignissim eros. Vestibulum iaculis risus nec leo ullamcorper, non porttitor ligula tempor. Nulla a ex eu metus laoreet porttitor et non arcu. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aenean augue risus, vulputate nec efficitur elementum, semper in dui. Aliquam erat volutpat. Mauris placerat, odio at fringilla tincidunt, risus magna tincidunt diam, a commodo diam dui dignissim libero. Nam aliquam vehicula dui, a dapibus felis iaculis dictum. Nunc tristique tincidunt dolor, eget bibendum risus dictum ac. Suspendisse semper quis nunc in varius. Morbi sed pulvinar odio. Ut congue vitae ex sed blandit. In hac habitasse platea dictumst. Mauris sit amet orci at felis commodo vulputate id vel est. Vestibulum semper eget erat vel consequat. Suspendisse dictum risus elit, et vehicula felis elementum ut. Duis molestie lorem non velit malesuada, porta fermentum metus consectetur. Sed cursus tellus at justo imperdiet, ac varius neque vehicula. Etiam feugiat ante sit amet maximus aliquet. Phasellus pulvinar maximus tincidunt. Nullam faucibus dui sem, et tincidunt urna commodo mollis. Maecenas id ultricies ex, et elementum eros. Donec ultricies, diam vitae molestie tristique, dolor risus maximus elit, nec congue quam tortor vitae leo. Vivamus a libero ac sapien consectetur dictum at congue libero. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
				'patron_photo' => 'img_595b46526592b.jpg',
				'patron_name' => 'Bill Murray',
				'user_id' => 2,
			]
		);
	}
}

class RolesTableSeeder extends Seeder {
	public function run() {
		Role::create (
			[
				'id' => 1,
				'role' => 'Admin',
			]
		);

		Role::create (
			[
				'id' => 2,
				'role' => 'User',
			]
		);
	}
}