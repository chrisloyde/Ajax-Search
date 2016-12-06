<h2>Ajax-Search</h2><br/>
**Launching MariaDB**<br/>
1. Place [imdb.sql](http://www.webstepbook.com/supplements-2ed/databases/imdb.zip) in xampp/htdocs
2. run the following code to launch mariaDB
~~~~
cd c:\xampp\htdocs
c:\xampp\mysql\bin\mysql -u root
~~~~
<br/>**Loading the database into MariaDB**<br/>
~~~~
CREATE DATABASE imdb
USE imdb
SOURCE imdb.sql
~~~~
**Todo List**<br/>
* *Create index page which will act as the search page*
* *Create a PDO object which can be used to search the database.*
* *Create a method inside the PDO object which searches either the movies or actors tables and returns all rows
which match the search query as a prefix. Should have a limit(50) on how many rows it returns as an array. *
*This is a testing method just to make sure we can search properly*
* *Add a title search, which can search the IMDB database movies table.*
* Use AJAX to accept a search for movie title names as letters are added to the query. Print the search results below.
* Add an actor search, to the index page which will behave the same way as the title search, searches last name, first name.
* Create a method in the PDO object which combines the movies and actors tables and searches both at the same time.
* Style with a table that displays title | actor | year.

