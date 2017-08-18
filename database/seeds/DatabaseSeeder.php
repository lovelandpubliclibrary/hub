<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Incident;
use App\Role;
use App\Comment;
use App\Photo;
use App\Location;

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
        $this->call(IncidentsTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(PhotosTableSeeder::class);
        $this->call(IncidentUserRelationshipSeeder::class);
        $this->call(RoleUserRelationshipSeeder::class);
    }
}

class UsersTableSeeder extends Seeder {

	public function run() {

		// output progress
		echo('--> Creating known user accounts...' . PHP_EOL);

		User::create (
			[
				'id' => 1,
				'name' => 'Test Admin',
				'email' => 'testadmin@cityofloveland.org',
				'password' => Hash::make('password'),
				'role_id' => 2,
			]
		);

		// output progress
		echo $this->getEmail();

		User::create (
			[
				'id' => 2,
				'name' => 'Test User',
				'email' => 'testuser@cityofloveland.org',
				'password' => Hash::make('password'),
				'role_id' => 1,
			]
		);

		// output progress
		echo $this->getEmail();

		User::create (
			[
				'id' => 3,
				'name' => 'Test Director',
				'email' => 'testdirector@cityofloveland.org',
				'password' => Hash::make('password'),
				'role_id' => 3,
			]
		);

		// output progress
		echo $this->getEmail();

		// determine the number of users to create
		$count = rand(20, 30);

		// output progress
		echo('--> Creating ' . $count . ' random Users... ');

		// generate fake user accounts
		factory(User::class, $count)->create();

		// output progress
		echo('done.' . PHP_EOL);
	}

	private function getEmail() {

		return '--> ' . User::orderBy('id', 'desc')->first()->email . PHP_EOL;

	}
}

class IncidentsTableSeeder extends Seeder {

	public function run() {

		// get a random number of incidents to create
		$count = rand(10, 15);

		// output progress
		echo('--> Creating ' . $count . ' Incidents... ');

		// create incidents
		factory(Incident::class, $count)->create();

		// output progress
		echo('done.' . PHP_EOL);
	}
}


class LocationsTableSeeder extends Seeder {

	public function run() {

		// output progress
		echo('--> Creating Locations...');

		// create locations
		$locations = ['Adult Services',
					  'Computer Lab',
					  '2nd Floor Hallway',
					  'Admin Area',
					  'Customer Service',
					  'Childrens',
					  'Outside',
					  'TeenSeen',
					  'Galleria',
					  'Restrooms',
					  'Other'
		];

		foreach ($locations as $location) {
			Location::create(
				[
					'location' => $location
				]
			);
		}

		// output progress
		echo('done.' . PHP_EOL);
	}
}


class RolesTableSeeder extends Seeder {

	public function run() {

		// output progress
		echo('--> Creating 3 Roles... ');

		// create roles
		Role::create(
			[
				'id' => 1,
				'role' => 'User',
			]
		);

		Role::create(
			[
				'id' => 2,
				'role' => 'Admin',
			]
		);

		Role::create(
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

		$comment_count = rand($incident_count, 1.2 * $incident_count);

		// output progress
		echo('--> Creating ' . $comment_count . ' Comments...');

		// create the comments
		factory(Comment::class, $comment_count)->create();

		// output progress
		echo('done.' . PHP_EOL);
	}
}


class PhotosTableSeeder extends Seeder {

	public function run() {
		// figure out how many photos to create
		//$count = Incident::all()->count();
		$count = 10;

		// output progress
		echo('--> Deleting existing Photos from the filesystem... ');

		// remove all the existing photos within the filesystem
		$photos = glob(public_path() . '/images/patrons/*');

		foreach ($photos as $photo) {

			if (is_file($photo)) unlink($photo);

		}

		// output progress
		echo('done.' . PHP_EOL . '--> Creating ' . $count . ' Photos... ');

		// create new photos
		factory(Photo::class, $count)->create();

		// output progress
		echo('done.' . PHP_EOL);
	}

}


class IncidentUserRelationshipSeeder extends Seeder {

	public function run() {

		// output progress
		echo('--> Establishing Incident/User relationships... ');

		$users = User::with('incidents')->get();
		$incident_count = Incident::all()->count();

		// attach a random number of incidents to each user
		foreach ($users as $user) {
				$incidents = Incident::where('id', '<=', rand(1, $incident_count))->get();
				$user->incidents()->saveMany($incidents);
		}

		// output progress
		echo('done.' . PHP_EOL);
	}
}


class RoleUserRelationshipSeeder extends Seeder {

	public function run() {

		// output progress
		echo('--> Establishing Role/User relationships... ');

		// get all the users and roles
		$users = User::with('role')->get();

		foreach ($users as $user) {
			switch ($user->name) {
				case 'Test User':
					$user->role()->save(Role::where('role', '=', 'User')->get()->first());
					break;
				case 'Test Admin':
					$user->role()->save(Role::where('role', '=', 'Admin')->get()->first());
					break;
				case 'Test Director':
					$user->role()->save(Role::where('role', '=', 'Director')->get()->first());
					break;
				default:
					$user->role()->save(Role::where('role', '=', 'User')->get()->first());
					break;
			}
			
		}

		// output progress
		echo('done.' . PHP_EOL);
	}
}