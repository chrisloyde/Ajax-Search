 <?php
	// Authors: Chris Peterson and Beau Mejias-Brean

 //header('Content-Type: application/json');

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
		 * actors | id , first_name , last_name , gender, film_count
		 * directors
		 * directors_genres
		 * movies | id, name, year, rank
		 * movies_directors
		 * movies_genres
		 * roles | actor_id , movie_id, role
		*/
		public function searchMovie($movie) {
			$movie = $movie . "%";
			$stmt = $this->DB->prepare("SELECT id FROM movies WHERE name LIKE :movie LIMIT 50");
			$stmt->bindParam('movie', $movie);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		public function searchActor($first, $last) {
			$first = $first . "%";
			$last = $last . "%";
			$stmt = $this->DB->prepare("SELECT id FROM actors WHERE last_name LIKE :last AND first_name LIKE :first LIMIT 50");
			$stmt->bindParam('first', $first);
			$stmt->bindParam('last', $last);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		public function searchRoles($movie_id, $actor_id) {
			$stmt = $this->DB->prepare("SELECT * FROM roles WHERE actor_id = :actor AND movie_id = :movie LIMIT 50");
			$stmt->bindParam('actor', $actor_id);
			$stmt->bindParam('movie', $movie_id);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getActorFromID($id) {
			$stmt = $this->DB->prepare("SELECT * FROM actors WHERE id=:id LIMIT 50");
			$stmt->bindParam('id', $id);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row["first_name"]." ".$row["last_name"];
		}

		public function getMovieFromID($id) {
			$stmt = $this->DB->prepare("SELECT * FROM movies WHERE id=:id LIMIT 50");
			$stmt->bindParam('id', $id);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row["name"];
		}

		public function getMatchingMovies($movie) {
			$movieID = $this->searchMovie($movie);
			$movieIDArray = array(array ("moviename", "actorlist"));
			for ($i = 0; $i < count($movieID); $i++) {
				$movieIDArray[$i]["moviename"] = $this->getMovieFromID($movieID[$i]["id"]);
				$movieIDArray[$i]["actorlist"] =  $this->getActorsFromMovie($movieID[$i]["id"]);
			}
			return array_filter($movieIDArray);
		}

		public function getActorsFromMovie($movieID) {
			//$movieID = $this->searchMovie($movie)[0]["id"];
			$stmt = $this->DB->prepare("SELECT actor_id FROM roles WHERE movie_id=:id");
			$stmt->bindParam('id', $movieID);
			$stmt->execute();
			$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$actorNameArray = array();
			for ($i = 0; $i < count($array); $i++) {
				$actorNameArray[$i] = " ". $this->getActorFromID($array[$i]["actor_id"]);
			}
			return $actorNameArray;
		}




	} // end class DatabaseAdaptor

 $DB = new DatabaseAdaptor();
 //$movieArray = $DB->getMatchingMovies("red");
 //var_dump($movieArray);
	?>
