 <?php
	// Authors: Chris Peterson and Beau Mejias-Brean

 //header('Content-Type: application/json');

	class DatabaseAdaptor {
		private $DB;
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
			$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (count($array) > 0) {
				return $array;
			} else {
				return null;
			}
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

		public function getMovieYearFromID($id) {
			$stmt = $this->DB->prepare("SELECT * FROM movies WHERE id=:id LIMIT 50");
			$stmt->bindParam('id', $id);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row["year"];
		}

		public function getMatchingMovies($movie, $first, $last) {
			$first = $first . "%";
			$last = $last . "%";
			$movieID = $this->searchMovie($movie);
			$movieIDArray = array(array ("moviename", "actorlist", "year"));
			if ($movieID != null) {
				for ($i = 0; $i < count($movieID); $i++) {
					if ($movie !== "") {
						$movieIDArray[$i]["moviename"] = $this->getMovieFromID($movieID[$i]["id"]);
						$movieIDArray[$i]["year"] = $this->getMovieYearFromID($movieID[$i]["id"]);
						$movieIDArray[$i]["actorlist"] = $this->getActorsFromMovie($movieID[$i]["id"], $first, $last);
					} else {
						$actorArray = $this->searchActor($first, $last);
						if ($actorArray != null) {
							for ($j = 0; $j < count($actorArray); $j++) {
								$movieIDArray[$j]["moviename"] = $this->getMovieFromID($actorArray[$j]['movieid']);
								$movieIDArray[$j]['actorlist'] = $actorArray[$j]["actorname"];
								$movieIDArray[$j]["year"] = $this->getMovieYearFromID($actorArray[$j]['movieid']);
							}
						}
					}
				}
			}
			return array_filter($movieIDArray);
		}

		public function getActorsFromMovie($movieID, $first, $last) {
			$stmt = $this->DB->prepare("SELECT actor_id FROM roles JOIN actors ON roles.actor_id = actors.id WHERE roles.movie_id=:id AND actors.last_name LIKE :last AND actors.first_name LIKE :first");
			$stmt->bindParam('first', $first);
			$stmt->bindParam('last', $last);
			$stmt->bindParam('id', $movieID);
			$stmt->execute();
			$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$actorNameArray = array();
			for ($i = 0; $i < count($array); $i++) {
				$actorNameArray[$i] = " ". $this->getActorFromID($array[$i]["actor_id"]);
			}
			return $actorNameArray;
		}
		public function searchActor($first, $last) {
			$first = $first . "%";
			$last = $last . "%";
			$stmt = $this->DB->prepare("SELECT * FROM actors JOIN roles ON roles.actor_id = actors.id WHERE actors.last_name LIKE :last AND actors.first_name LIKE :first LIMIT 50");
			$stmt->bindParam('first', $first);
			$stmt->bindParam('last', $last);
			$stmt->execute();
			$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (count($array) > 0) {
				$dataArray = array(array("actorname", "movieid"));
				for ($i = 0; $i < count($array); $i++) {
					$dataArray[$i]["actorname"] = $this->getActorFromID($array[$i]["actor_id"]);
					$dataArray[$i]["movieid"] = $array[$i]["movie_id"];
				}
			} else {
				return null;
			}
			return $dataArray;
		}
	} // end class DatabaseAdaptor

 $DB = new DatabaseAdaptor();
// $movieArray = $DB->getMatchingMovies("", "", "");
 //var_dump($movieArray);
	?>
