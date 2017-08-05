<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Incident;
use App\Role;
use App\Comment;
use App\Photo;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RoleUserRelationshipSeeder::class);
        $this->call(IncidentsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(PhotosTableSeeder::class);
    }
}

class UsersTableSeeder extends Seeder {
	public function run() {
		// generate known user accounts for testing
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

		User::create (
			[
				'id' => 3,
				'name' => 'Test Director',
				'email' => 'testdirector@cityofloveland.org',
				'password' => Hash::make('password'),
				'role_id' => 3,
			]
		);

		// determine the number of users to create
		$count = rand(20, 200);

		// output progress
		echo('Creating ' . $count + User::all()->count() . ' Users... ');

		// generate fake user accounts
		factory(User::class, rand(20, 200))->create();

		// output progress
		echo('done.' . PHP_EOL);
	}
}

class IncidentsTableSeeder extends Seeder {
	public function run() {
		// get a random number of incidents to create
		$count = rand(10, 100);

		// output progress
		echo('Creating ' . $count . ' Incidents... ');

		// create incidents
		factory(Incident::class, $count)->create();

		// output progress
		echo('done.' . PHP_EOL);
	}
}

class RolesTableSeeder extends Seeder {
	public function run() {
		// output progress
		echo('Creating 3 Roles... ');

		// create roles
		Role::create (
			[
				'id' => 1,
				'role' => 'User',
			]
		);

		Role::create (
			[
				'id' => 2,
				'role' => 'Admin',
			]
		);

		Role::create (
			[
				'id' => 3,
				'role' => 'Director',
			]
		);

		// output progress
		echo('done.' . PHP_EOL);
	}
}

class CommentsTableSeeder extends Seeder {
	public function run() {
		// count the number of incidents so that we can generate an appropriate range of comments
		$incident_count = Incident::all()->count();
		$comment_count = rand($incident_count, 3 * $incident_count);

		// output progress
		echo('Creating ' . $comment_count . ' Comments...');

		// create the comments
		factory(Comment::class, $comment_count)->create();

		// output progress
		echo('done.' . PHP_EOL);
	}
}

class RoleUserRelationshipSeeder extends Seeder {
	public function run() {
		// get all the users
		$users = User::all();
		
		// assign a role to each user
		// this could be improved by adding a variable number of roles to
		// each user but at this time that isn't necessary
		foreach ($users as $user) {
			$user->role()->save(Role::find(rand(1, Role::all()->count())));
		}
	}
}


class PhotosTableSeeder extends Seeder {
	public function run() {
		// figure out how many photos to create
		$count = Incident::all()->count();

		// output progress
		echo('Deleting any existing Photos from the filesystem... ');

		// remove all the existing photos within the filesystem
		$photos = glob(public_path() . '/images/patrons/*');
		foreach ($photos as $photo) {
			if (is_file($photo)) unlink($photo);
		}

		// output progress
		echo('done.' . PHP_EOL . 'Creating ' . $count . ' Photos... ');

		// create new photos
		factory(Photo::class, $count)->create();

		// output progress
		echo('done.' . PHP_EOL);
	}
}