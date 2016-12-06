 <?php
	// Authors: Chris Peterson and Beau Mejias-Brean

	class DatabaseAdaptor {
		// The instance variable used in every one of the functions in class DatbaseAdaptor
		private $DB;
		// Make a connection to an existing data based named 'quotes' that has
		// table quote. In this assignment you will also need a new table named 'users'
		public function __construct() {
			$db = 'mysql:dbname=imdb;charset=utf8;host=127.0.0.1';
			$user = 'root';
			$password = '';
			
			try {
				$this->DB = new PDO ( $db, $user, $password );
				$this->DB->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			} catch ( PDOException $e ) {
				echo ('Error establishing Connection');
				exit ();
			}
		}

		/*available tables:
		 * actors
		 * directors
		 * directors_genres
		 * movies
		 * movies_directors
		 * movies_genres
		 * roles
		*/
		public function searchMovie($movie) {
			$movie = $movie . "%";
			$stmt = $this->DB->prepare("SELECT * FROM movies WHERE name LIKE :movie LIMIT 50");
			$stmt->bindParam('movie', $movie);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		public function searchActor($first, $last) {
			$first = $first . "%";
			$last = $last . "%";
			$stmt = $this->DB->prepare("SELECT * FROM actors WHERE last_name LIKE :last AND first_name LIKE :first LIMIT 50");
			$stmt->bindParam('first', $first);
			$stmt->bindParam('last', $last);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}


		
	} // end class DatabaseAdaptor

 $DB = new DatabaseAdaptor();
 //$movieArray = $DB->searchActor("", "t");
 //var_dump($movieArray);
	?>
