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
use App\Patron;

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
        $this->call(PatronsTableSeeder::class);
        $this->call(PhotosTableSeeder::class);


        // seed the model relationships
        $this->call(IncidentUserViewedRelationshipSeeder::class);
        $this->call(IncidentUserInvolvedRelationshipSeeder::class);
        $this->call(RoleUserRelationshipSeeder::class);
        $this->call(DivisionUserRelationshipSeeder::class);
        $this->call(UserSupervisorRelationshipSeeder::class);
        $this->call(PatronPhotoRelationshipSeeder::class);
        $this->call(IncidentPhotoRelationshipSeeder::class);
        $this->call(IncidentPatronRelationshipSeeder::class);
        $this->call(IncidentLocationRelationshipSeeder::class);
    }
}

/**
 * Seed the models
 */
class UsersTableSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing user accounts... ');
		DB::table('users')->delete();
		


		$this->command->info('--> Creating known user accounts...');		// output progress

		$faker = Faker::create();		// instantiate the faker class

		// create the Test Admin
		User::create(
			[
				'id' => 1,
				'name' => 'Test Admin',
				'email' => 'testadmin@cityofloveland.org',
				'password' => Hash::make('password'),
				'supervisor_id' => 4,
				'created_at' => $faker->dateTime(),
			]
		);
		$this->command->info($this->printEmail());		// output progress

		// create the Test User
		User::create(
			[
				'id' => 2,
				'name' => 'Test User',
				'email' => 'testuser@cityofloveland.org',
				'password' => Hash::make('password'),
				'supervisor_id' => 4,
				'created_at' => $faker->dateTime(),
			]
		);
		$this->command->info($this->printEmail());		// output progress

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
		$this->command->info($this->printEmail());		// output progress

		// create the Test Supervisor
		User::create(
			[
				'id' => 4,
				'name' => 'Test Supervisor',
				'email' => 'testsupervisor@cityofloveland.org',
				'password' => Hash::make('password'),
				'supervisor_id' => 3,
				'created_at' => $faker->dateTime(),
			]
		);
		$this->command->info($this->printEmail());		// output progress

		$count = rand(40, 80);		// determine the number of random users to create

		$this->command->info('--> Creating ' . $count . ' random Users... ');		// output progress

		factory(User::class, $count)->create();		// generate fake user accounts
	}

	// wrapper function for outputting progress
	private function printEmail() {
		return '--> ' . User::orderBy('id', 'desc')->first()->email;
	}
}


class RolesTableSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing roles... ');
		DB::table('roles')->delete();
		

		// define roles
		$roles = [
			'User',
			'Director',
			'Administrator',
			'Supervisor',
		];

		// create roles
		foreach ($roles as $key => $role) {
			$this->command->info('--> Creating ' . $role . ' Role... ');			// output progress

			Role::create(
				[
					'id' => $key + 1,
					'role' => $role,
				]
			);
		}
	}
}


class DivisionsTableSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing divisions... ');
		DB::table('divisions')->delete();
		

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
			$this->command->info('--> Creating ' . $division . ' Division... ');	// output progress

			Division::create(
				[
					'id' => $key + 1,
					'division' => $division,
				]
			);

		}
	}
}


class LocationsTableSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing locations... ');
		DB::table('locations')->delete();
		

		$this->command->info('--> Creating Locations...');		// output progress

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
	}
}


class IncidentsTableSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing incidents... ');
		DB::table('incidents')->delete();
		

		$count = rand(50, 200);		// get a random number of incidents to create

		$this->command->info('--> Creating ' . $count . ' Incidents... ');		// output progress

		factory(Incident::class, $count)->create();		// create incidents
	}
}

class CommentsTableSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing comments... ');
		DB::table('comments')->delete();
		

		// count the number of incidents and set a limit on the number of comments to generate
		$incident_count = Incident::all()->count();
		$comment_count = rand($incident_count * 3, $incident_count * 6);

		$this->command->info('--> Creating ' . $comment_count . ' Comments... ');		// output progress

		factory(Comment::class, $comment_count)->create();		// create the comments
	}
}

class PatronsTableSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing patrons... ');
		DB::table('patrons')->delete();
		

		// count the number of incidents and set a limit on the number of patrons to generate
		$incident_count = Incident::all()->count();
		$patron_count = rand($incident_count, $incident_count * 2);

		$this->command->info('--> Creating ' . $patron_count . ' Patrons... ');		// output progress

		factory(Patron::class, $patron_count)->create();		// create the patrons
	}
}

class PhotosTableSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing photos... ');
		DB::table('photos')->delete();
		// remove all the existing photos within the filesystem
		$photos = glob(storage_path() . '/app/public/photos/*');		// http://php.net/manual/en/function.glob.php
		foreach ($photos as $photo) {
			if (is_file($photo)) unlink($photo);
		}
		
		$patron_count = Patron::all()->count();
		$photo_count = round(($patron_count * 2) / 3);		// get 2/3 of the patron_count
		$this->command->info("--> Creating {$photo_count} photos...");		// output progress
		factory(Photo::class, $photo_count)->create();		// create photos
	}
}

/**
 * Seed the relationships
 */


class IncidentUserViewedRelationshipSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing relationships... ');
		DB::table('incident_user_viewed')->delete();
		

		$this->command->info('--> Simulating Users viewing Incidents... ');		// output progress

		// retrieve all users and the number of incidents already in the database
		$users = User::with('incidentsViewed')->get();
		$incident_count = Incident::all()->count();

		// attach a random number of incidents to each user
		foreach ($users as $user) {
				$incidents = Incident::where('id', '<=', rand(1, $incident_count))->get();
				$user->incidentsViewed()->saveMany($incidents);
		}
	}
}


class IncidentUserInvolvedRelationshipSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing relationships... ');
		DB::table('incident_user_involved')->delete();
		

		$this->command->info('--> Simulating Users being involved in Incidents... ');		// output progress

		// retrieve all users and the number of incidents already in the database
		$users = User::with('incidentsInvolved')->get();
		$incident_count = Incident::all()->count();

		// attach a random number of incidents to each user
		foreach ($users as $user) {
				$incidents = Incident::where('id', '<=', rand(1, $incident_count / 2))->get();
				$user->incidentsInvolved()->saveMany($incidents);
		}
	}
}


class RoleUserRelationshipSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing relationships... ');
		DB::table('role_user')->delete();
		

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
					$this->command->info('---> Assigning the Test Admin to the Administrator role... ');		// output progress
					$role = Role::where('role', 'Administrator')->get()->first();
					$user->role()->attach($role);
	
					break;
				case 'Test Director':
					$this->command->info('---> Assigning the Test Director to the Director and Supervisor roles... ');		// output progress
					$roles = Role::where('role', 'Director')->orWhere('role', 'Supervisor')->get();
					foreach ($roles as $role) {
						$user->role()->attach($role);
					}
	
					break;
				case 'Test Supervisor':
					$this->command->info('---> Assigning the Test Director to the Director role... ');		// output progress
					$role = Role::where('role', 'Supervisor')->get()->first();
					$user->role()->attach($role);
	
					break;
			}

			$user->load('role');	// reload the relationship on the model

			// assign some users as supervisors
			// ensure the user isn't already a supervisor and that every user doesn't get assigned the supervisor Role
			if (!$user->hasRole($supervisor_role) && $user->id % 4 === 0) {
				$user->role()->save($supervisor_role);
			}

		}

		$this->command->info('---> Assigning roles to all other users... ');		// output progress (formatted for consistency with other status messages)
	}
}


class DivisionUserRelationshipSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing relationships... ');
		DB::table('division_user')->delete();
		

		$this->command->info('--> Assigning Users to random Divisions... ');		// output progress

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
	}
}

class UserSupervisorRelationshipSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Assigning Supervisors direct reports... ');		// output progress

		$users = User::with('role')->get();
		$supervisor_role = Role::where('role', 'Supervisor')->get()->first();
		$supervisors = User::whereHas('role', function ($query) {
			$query->where('role', 'Supervisor');
		})->get();


		foreach ($users as $user) {
			$skipped_users = [
				'Test Admin',
				'Test User',
				'Test Director',
				'Test Supervisor',
			];
			if (!in_array($user->name, $skipped_users)) {		// 
				while (empty($user->reportsTo) || $user->reportsTo === $user) {
					$user->reportsTo()->associate($supervisors->random());
					$user->save();
				}
			}
		}
	}
}


class PatronPhotoRelationshipSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Assigning Photos to Patrons... ');		// output progress

		$patrons = Patron::all();

		foreach ($patrons as $patron) {
			$photos = Photo::all();
			for ($i = rand(0,3); $i < 3; $i++) {	// each patron has a chance to be associated with 0-3 photos
				$photos = $photos->shuffle();
				$photo = $photos->pop();
				$patron->photo()->attach($photo);
			}
		}
	}
}


class IncidentPhotoRelationshipSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Assigning Photos to Incidents... ');		// output progress

		$incidents = Incident::all();

		foreach ($incidents as $incident) {
			$photos = Photo::all();
			for ($i = rand(0,2); $i < 2; $i++) {	// each patron has a chance to be associated with 0-2 photos
				$photos = $photos->shuffle();
				$photo = $photos->pop();
				$incident->photo()->attach($photo);
			}
		}
	}
}


class IncidentPatronRelationshipSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Involving Patrons in Incidents... ');		// output progress

		$patrons = Patron::all();

		foreach ($patrons as $patron) {
			$incidents = Incident::all();

			for ($i = rand(1,3); $i < 3; $i++) {		// each patron has a chance to be associated with 1 -3 incidents
				$incidents = $incidents->shuffle();
				$incident = $incidents->pop();
				$patron->incident()->attach($incident);
			}
		}
	}
}


class IncidentLocationRelationshipSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Assigning Locations to Incidents... ');		// output progress

		$incidents = Incident::all();

		foreach ($incidents as $incident) {
			$locations = Location::all();

			for ($i = rand(1,3); $i <= 3; $i++) {		// each incident has a chance to be assigned 1-3 locations
				$locations = $locations->shuffle();
				$location = $locations->pop();
				$incident->location()->attach($location);
				if ($incident->id % 2 === 0) break;
			}
		}
	}
}