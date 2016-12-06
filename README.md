<h2>Ajax-Search</h2><br/>
--Launching MariaDB--<br/>
1. Place [imdb.sql](http://www.webstepbook.com/supplements-2ed/databases/imdb.zip) in xampp/htdocs
2. run the following code to launch mariaDB
~~~~
cd c:\xampp\htdocs
c:\xampp\mysql\bin\mysql -u root
~~~~
<br/>--Loading the database into MariaDB--<br/>
~~~~
CREATE DATABASE imdb
USE imdb
SOURCE imdb.sql
~~~~
