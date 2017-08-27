<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;
use App\Incident;
use App\Role;
use App\Comment;
use App\Photo;
use App\Location;
use App\Division;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
    	// seed the models
        $this->call(UsersTableSeeder::class);
    	$this->call(RolesTableSeeder::class);
        $this->call(DivisionsTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(IncidentsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(PhotosTableSeeder::class);


        // seed the model relationships
        $this->call(IncidentUserViewedRelationshipSeeder::class);
        $this->call(IncidentUserInvolvedRelationshipSeeder::class);
        $this->call(RoleUserRelationshipSeeder::class);
        $this->call(DivisionUserRelationshipSeeder::class);
    }
}

class UsersTableSeeder extends Seeder {

	public function run() {
		echo('--> Creating known user accounts...' . PHP_EOL);		// output progress

		$faker = Faker::create();		// instantiate the faker class

		// create the Test Admin
		User::create(
			[
				'id' => 1,
				'name' => 'Test Admin',
				'email' => 'testadmin@cityofloveland.org',
				'password' => Hash::make('password'),
				'created_at' => $faker->dateTime(),
			]
		);
		echo $this->printEmail();		// output progress

		// create the Test User
		User::create(
			[
				'id' => 2,
				'name' => 'Test User',
				'email' => 'testuser@cityofloveland.org',
				'password' => Hash::make('password'),
				'created_at' => $faker->dateTime(),
			]
		);
		echo $this->printEmail();		// output progress

		// create the Test Director
		User::create(
			[
				'id' => 3,
				'name' => 'Test Director',
				'email' => 'testdirector@cityofloveland.org',
				'password' => Hash::make('password'),
				'created_at' => $faker->dateTime(),
			]
		);
		echo $this->printEmail();		// output progress

		// create the Test Supervisor
		User::create(
			[
				'id' => 4,
				'name' => 'Test Supervisor',
				'email' => 'testsupervisor@cityofloveland.org',
				'password' => Hash::make('password'),
				'created_at' => $faker->dateTime(),
			]
		);
		echo $this->printEmail();		// output progress

		$count = rand(40, 80);		// determine the number of random users to create

		echo('--> Creating ' . $count . ' random Users... ');		// output progress

		factory(User::class, $count)->create();		// generate fake user accounts

		echo('done.' . PHP_EOL);		// output progress
	}

	// wrapper function for outputting progress
	private function printEmail() {
		return '--> ' . User::orderBy('id', 'desc')->first()->email . PHP_EOL;
	}
}


class RolesTableSeeder extends Seeder {

	public function run() {

		// define roles
		$roles = [
			'User',
			'Director',
			'Administrator',
			'Supervisor',
		];

		// create roles
		foreach ($roles as $key => $role) {
			echo('--> Creating ' . $role . ' Role... ');			// output progress

			Role::create(
				[
					'id' => $key + 1,
					'role' => $role,
				]
			);

			echo('done.' . PHP_EOL);			// output progress
		}
	}
}


class DivisionsTableSeeder extends Seeder {

	public function run() {

		// define divisions
		$divisions = [
			'Admin',
			'Adult Services',
			'Children',
			'Customer Service',
			'LTI',
			'Material Handlers',
			'Subs',
			'Teen',
			'Tech Services',
		];

		// create divisions
		foreach ($divisions as $key => $division) {
			echo('--> Creating ' . $division . ' Division... ');	// output progress

			Division::create(
				[
					'id' => $key + 1,
					'division' => $division,
				]
			);

			echo('done.' . PHP_EOL);		// output progress
		}
	}
}


class LocationsTableSeeder extends Seeder {

	public function run() {

		echo('--> Creating Locations...');		// output progress

		// define locations
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

		// create locations
		foreach ($locations as $location) {
			Location::create(
				[
					'location' => $location
				]
			);
		}

		echo('done.' . PHP_EOL);		// output progress
	}
}


class IncidentsTableSeeder extends Seeder {

	public function run() {

		$count = rand(50, 200);		// get a random number of incidents to create

		echo('--> Creating ' . $count . ' Incidents... ');		// output progress

		factory(Incident::class, $count)->create();		// create incidents

		echo('done.' . PHP_EOL);		// output progress
	}
}

class CommentsTableSeeder extends Seeder {

	public function run() {

		// count the number of incidents and set a limit on the number of comments to generate
		$incident_count = Incident::all()->count();
		$comment_count = rand($incident_count * 3, $incident_count * 6);

		echo('--> Creating ' . $comment_count . ' Comments... ');		// output progress

		factory(Comment::class, $comment_count)->create();		// create the comments

		echo('done.' . PHP_EOL);		// output progress
	}
}


class PhotosTableSeeder extends Seeder {

	public function run() {

		$count = round(Incident::all()->count() / rand(2, 3));		// determine the number of photos to create

		echo('--> Deleting existing Photos from the filesystem... ');		// output progress

		// remove all the existing photos within the filesystem
		$photos = glob(public_path() . '/images/patrons/*');		// http://php.net/manual/en/function.glob.php
		foreach ($photos as $photo) {
			if (is_file($photo)) unlink($photo);
		}

		echo('done.' . PHP_EOL . '--> Creating ' . $count . ' Photos... ');		// output progress

		factory(Photo::class, $count)->create();		// create new photos

		echo('done.' . PHP_EOL);		// output progress
	}
}


class IncidentUserViewedRelationshipSeeder extends Seeder {

	public function run() {

		echo('--> Simulating Users viewing Incidents... ');		// output progress

		// retrieve all users and the number of incidents already in the database
		$users = User::with('incidentsViewed')->get();
		$incident_count = Incident::all()->count();

		// attach a random number of incidents to each user
		foreach ($users as $user) {
				$incidents = Incident::where('id', '<=', rand(1, $incident_count))->get();
				$user->incidentsViewed()->saveMany($incidents);
		}

		echo('done.' . PHP_EOL);		// output progress
	}
}


class IncidentUserInvolvedRelationshipSeeder extends Seeder {

	public function run() {

		echo('--> Simulating Users being involved in Incidents... ');		// output progress

		// retrieve all users and the number of incidents already in the database
		$users = User::with('incidentsInvolved')->get();
		$incident_count = Incident::all()->count();

		// attach a random number of incidents to each user
		foreach ($users as $user) {
				$incidents = Incident::where('id', '<=', rand(1, $incident_count / 2))->get();
				$user->incidentsInvolved()->saveMany($incidents);
		}

		echo('done.' . PHP_EOL);		// output progress
	}
}


class RoleUserRelationshipSeeder extends Seeder {

	public function run() {

		// retrieve the necessary models
		$users = User::with('role')->get();		// get all the users and their roles
		$supervisor_role = Role::where('role', 'Supervisor')->get()->first();		// retrieve the Supervisor role

		// setup supervisor assignment constraints
		$supervisor_count = round(User::all()->count() / 4);		// determine the number of Users to make Supervisors

		// assign the correct roles to each user
		foreach ($users as $user) {

			// assign the 'User' role to all users
			$user_role = Role::where('role', 'User')->get()->first();
			$user->role()->attach($user_role);

			$user->load('role');	// reload the relationship on the model

			// assign the appropriate role to each of the test users
			switch ($user->name) {
				case 'Test Admin':
					echo('---> Assigning the Test Admin to the Administrator role... ');		// output progress
					$role = Role::where('role', 'Administrator')->get()->first();
					$user->role()->attach($role);
					echo('done.' . PHP_EOL);		// output progress
					break;
				case 'Test Director':
					echo('---> Assigning the Test Director to the Director role... ');		// output progress
					$role = Role::where('role', 'Director')->get()->first();
					$user->role()->attach($role);
					echo('done.' . PHP_EOL);		// output progress
					break;
				case 'Test Supervisor':
					echo('---> Assigning the Test Director to the Director role... ');		// output progress
					$role = Role::where('role', 'Supervisor')->get()->first();
					$user->role()->attach($role);
					echo('done.' . PHP_EOL);		// output progress
					break;
			}

			$user->load('role');	// reload the relationship on the model

			// assign some users as supervisors
			echo('---> Assigning the Supervisor role to ' . $user->name . '... ');		// output progress

			// ensure the user isn't already a supervisor and that every user doesn't get assigned the supervisor Role
			if (!$user->hasRole($supervisor_role) && $user->id % 4 === 0) {
				$user->role()->save($supervisor_role);
			}

			echo('done.' . PHP_EOL);		// output progress
		}

		echo('---> Assigning the User role to all users... done.' . PHP_EOL);		// output progress (formatted for consistency with other status messages)
	}
}


class DivisionUserRelationshipSeeder extends Seeder {

	public function run() {

		echo('--> Assigning Users to random Divisions... ');		// output progress

		// retrieve the required models
		$users = User::all();
		$divisions = Division::all();
		$supervisor_role = Role::where('role', 'Supervisor')->get()->first();

		// attach some divisions to each user
		foreach ($users as $user) {

			for ($i = 1; $i <= rand(1, 3); $i++) {
				$division = $divisions->random();
				$user->load('divisions');

				// check to see if the user is a supervisor and if so, make them a supervisor of their division
				if ($user->divisions->contains($division)) {	// ensure the user isn't already a member of the division
					continue;
				} else {
					$user->hasRole($supervisor_role) ?
					$user->divisions()->save($division, ['supervisor' => true]) :
					$user->divisions()->save($division);
				}
			}
		}

		echo('done.' . PHP_EOL);		// output progress
	}
}