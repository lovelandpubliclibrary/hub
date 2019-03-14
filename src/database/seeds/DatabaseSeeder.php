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
    	// order is important
        $this->call(UsersTableSeeder::class);
    	$this->call(RolesTableSeeder::class);
        $this->call(DivisionsTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(IncidentsTableSeeder::class);
        $this->call(PatronsTableSeeder::class);
        $this->call(PhotosTableSeeder::class);
        $this->call(CommentsTableSeeder::class);


        // seed the relationships
        $this->call(IncidentUserViewedRelationshipSeeder::class);
        $this->call(IncidentUserInvolvedRelationshipSeeder::class);
        $this->call(RoleUserRelationshipSeeder::class);
        $this->call(DivisionUserRelationshipSeeder::class);
        $this->call(UserSupervisorRelationshipSeeder::class);
        // $this->call(PatronPhotoRelationshipSeeder::class);
        // $this->call(IncidentPhotoRelationshipSeeder::class);
        $this->call(PhotoRelationshipSeeder::class);
        $this->call(IncidentPatronRelationshipSeeder::class);
        $this->call(IncidentLocationRelationshipSeeder::class);
        $this->call(UserPatronRelationshipSeeder::class);
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
		$incident_count = Incident::all()->count();
		$average_count = ($patron_count + $incident_count) / 2;
		$photo_count = round($average_count / 5);		// determine the number of photos to create
		$this->command->info("--> Downloading and saving {$photo_count} photos...");		// output progress
		factory(Photo::class, $photo_count)->create();		// create photos
	}
}


class CommentsTableSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Deleting existing comments... ');
		DB::table('comments')->delete();
		

		// count the number of commentable entities
		$incident_count = Incident::all()->count();
		$patron_count = Patron::all()->count();
		$photo_count = Photo::all()->count();
		$sum = $incident_count + $patron_count + $photo_count;

		// set a limit on the number of comments that will be generated
		$comment_count = rand($sum * 3, $sum * 5);

		// output progress
		$message = '--> Creating ' . $comment_count . ' Comments... ';
		$this->command->info($message);

		// create the comments
		factory(Comment::class, $comment_count)->create();
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
					$this->command->info('--> Assigning the Test Admin to the Administrator role... ');		// output progress
					$role = Role::where('role', 'Administrator')->get()->first();
					$user->role()->attach($role);
	
					break;
				case 'Test Director':
					$this->command->info('--> Assigning the Test Director to the Director and Supervisor roles... ');		// output progress
					$roles = Role::where('role', 'Director')->orWhere('role', 'Supervisor')->get();
					foreach ($roles as $role) {
						$user->role()->attach($role);
					}
	
					break;
				case 'Test Supervisor':
					$this->command->info('--> Assigning the Test Director to the Director role... ');		// output progress
					$role = Role::where('role', 'Supervisor')->get()->first();
					$user->role()->attach($role);
	
					break;
			}

			$user->load('role');	// reload the relationship on the model

			// assign some users as supervisors
			// ensure the user isn't already a supervisor and that every user doesn't get assigned the supervisor Role
			if (!$user->isSupervisor() && $user->id % 4 === 0) {
				$user->role()->save($supervisor_role);
			}

		}

		$this->command->info('--> Assigning roles to all other users... ');		// output progress (formatted for consistency with other status messages)
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

		// attach some divisions to each user
		foreach ($users as $user) {

			for ($i = 1; $i <= rand(1, 3); $i++) {
				$division = $divisions->random();
				$user->load('divisions');

				// check to see if the user is a supervisor and if so, make them a supervisor of their division
				if ($user->divisions->contains($division)) {	// ensure the user isn't already a member of the division
					continue;
				} else {
					$user->isSupervisor() ?
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

class PhotoRelationshipSeeder extends Seeder {
	public function run() {
		$this->command->info('--> Assigning Photos to Patrons and Incidents... ');

		$photos = Photo::all()->shuffle();
		$patrons = Patron::all();
		$incidents = Incident::all();

		while ($photo = $photos->pop()) {
			if (rand(0,3)) {	// 3 of 4 photos will be assigned to a patron
				$patron = $patrons->shuffle()->first();
				$patron->photo()->attach($photo);
			} else {
				$incident = $incidents->shuffle()->first();
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


class UserPatronRelationshipSeeder extends Seeder {

	public function run() {

		$this->command->info('--> Assigning Patrons to Staff Members... ');

		$patrons = Patron::all();
		$staff = User::all();

		foreach ($patrons as $patron) {
			$staff->shuffle()->first()->patrons()->save($patron);
		}

	}
}
