# test4bidroom
Test task for Bidroom.
This Application have one page route '/contact' fith simple form. 


Please clone the test project. 
Run command "composer install". 
Create file .env.(cp .env/example .env).
Change information about database in file .env(like as  DATABASE_URL=mysql://{user}:{password}@127.0.0.1:3306/{name_new_db}).
Run "php bin/console doctrine:migrations:migrate" for creatr table(s) in DB.
Run "php bin/console server:start 0.0.0.0:8000" for start application on Your local server.
Open route in brouser: http://0.0.0.0:8000/contact

# Changes: add validation, add sending email, add API Client(for getAll and create one), etc.

# Some photos from QA testing: Find message by Id 
<img src="TESTPOST1.png" alt="Test">

# Create new message POST request <img src="TESTPOST2.png" alt="Test">
