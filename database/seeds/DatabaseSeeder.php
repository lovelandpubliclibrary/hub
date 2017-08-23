<?php

use Illuminate\Database\Seeder;
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
    	$this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(IncidentsTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(PhotosTableSeeder::class);
        $this->call(DivisionsTableSeeder::class);

        // seed the model relationships
        $this->call(IncidentUserViewedRelationshipSeeder::class);
        $this->call(IncidentUserInvolvedRelationshipSeeder::class);
        $this->call(RoleUserRelationshipSeeder::class);
        $this->call(DivisionUserRelationshipSeeder::class);
        $this->call(DivisionUserSupervisorRelationshipSeeder::class);
    }
}

class UsersTableSeeder extends Seeder {

	public function run() {
		echo('--> Creating known user accounts...' . PHP_EOL);		// output progress

		// create the Test Admin
		User::create(
			[
				'id' => 1,
				'name' => 'Test Admin',
				'email' => 'testadmin@cityofloveland.org',
				'password' => Hash::make('password'),
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
			]
		);

		echo $this->printEmail();		// output progress

		$count = rand(20, 30);		// determine the number of users to create

		echo('--> Creating ' . $count . ' random Users... ');		// output progress

		factory(User::class, $count)->create();		// generate fake user accounts

		echo('done.' . PHP_EOL);		// output progress
	}

	// wrapper function for outputting progress
	private function printEmail() {
		return '--> ' . User::orderBy('id', 'desc')->first()->email . PHP_EOL;
	}
}

class IncidentsTableSeeder extends Seeder {

	public function run() {

		$count = rand(10, 15);		// get a random number of incidents to create

		echo('--> Creating ' . $count . ' Incidents... ');		// output progress

		factory(Incident::class, $count)->create();		// create incidents

		echo('done.' . PHP_EOL);		// output progress
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


class RolesTableSeeder extends Seeder {

	public function run() {

		// define roles
		$roles = [
			'User',
			'Supervisor',
			'Director',
			'Administrator',
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

class CommentsTableSeeder extends Seeder {

	public function run() {

		// count the number of incidents and set a limit on the number of comments to generate
		$incident_count = Incident::all()->count();
		$comment_count = rand($incident_count, 1.2 * $incident_count);

		echo('--> Creating ' . $comment_count . ' Comments...');		// output progress

		factory(Comment::class, $comment_count)->create();		// create the comments

		echo('done.' . PHP_EOL);		// output progress
	}
}


class PhotosTableSeeder extends Seeder {

	public function run() {

		$count = Incident::all()->count() * rand(1, 3);		// determine the number of photos to create

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

		echo('--> Assigning Users to random Roles... ');		// output progress

		$users = User::with('role')->get();		// get all the users and their roles

		// assign the correct roles to each user
		foreach ($users as $user) {
			// assign the appropriate role to each of the test users
			switch ($user->name) {
				case 'Test Admin':
					$role = Role::where('role', 'Administrator')->get()->first();
					$user->role()->attach($role);
					echo('---> Test Admin assigned the Administrator role.' . PHP_EOL);		// output progress
					break;
				case 'Test Director':
					$role = Role::where('role', 'Director')->get()->first();
					$user->role()->attach($role);
					echo('---> Test Director assigned the Director role.' . PHP_EOL);		// output progress
					break;
				case 'Test Supervisor':
					$role = Role::where('role', 'Supervisor')->get()->first();
					$user->role()->attach($role);
					echo('---> Test Supervisor assigned the Supervisor role.' . PHP_EOL);		// output progress
					break;
			}

			// assign the 'User' role to all users
			echo('---> Assigning all users to the User role.' . PHP_EOL);		// output progress
			$role = Role::where('role', 'User')->get()->first();
			$user->role()->attach($role);
		}

		// assign random users the Supervisor role
		/*  Note: this doesn't assign them as a supervisor to
			a division, it simply specifies the user as
			a supervisor.  Division assignment happens in the
			DivisionUserSupervisorRelationshipSeeder class */
		$division_count = Division::all()->count();
		$role = Role::where('role', '=', 'Supervisor')->get()->first();

		echo('---> Assigning random users to the Supervisor role.' . PHP_EOL);		// output progress
		
		$i = 0;
		while ($i < $division_count) {		// don't assign too many supervisors
			$user = $users->random();		// grab a random user

			// assign the User as a supervisor if they aren't already
			$user->load('role');		// reload the relationship
			if ($user->role->contains($role)) {
				continue;
			} else {
				$user->role()->attach($role);
				$i++;
			}
		}

		echo('done.' . PHP_EOL);		// output progress
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


class DivisionUserRelationshipSeeder extends Seeder {

	public function run() {

		echo('--> Assigning Users to random Divisions... ');		// output progress

		// get all the users
		$users = User::all();
		$division_count = Division::all()->count();

		// attach some divisions to each user
		foreach ($users as $user) {
			$divisions = Division::where('id', '<=', rand(1, $division_count / 4))->get();
			$user->divisions()->saveMany($divisions);
		}

		echo('done.' . PHP_EOL);		// output progress
	}
}


class DivisionUserSupervisorRelationshipSeeder extends Seeder {

	public function run() {

		echo('--> Assigning supervisors to each Division... ');		// output progress

		// retrieve all the users with a role of "Supervisor"
		$supervisors = User::whereHas('role', function ($query){
			$query->where('role', '=', 'Supervisor');
		})->get();

		$divisions = Division::all();		// retrieve all the divisions

		// assign all the supervisors to each division
		/*	Note: this would never be the case in production
			however we want to be sure each division can have as many supervisors
			as are needed, and this makes testing permissions much easier
			in development. */
		foreach ($divisions as $division) {
			$division->supervisors()->saveMany($supervisors);
		}

		echo('done.' . PHP_EOL);		// output progress
	}
}