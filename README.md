# panthernet-demo

Group 10:
- Vincent Kim
- Daniel Lee
- Nick Bourne

Very rough and simple demo of our final stage of the project. Some known bugs/issues:
- Add Friend (not working properly)
- Respond to Friend Request (not working properly)
- Likes/Unlikes Stored to Database (not working properly)
- Deleting posts (doesn't seem to work via XAMPP's localhost)

---

## Running locally
- Install XAMPP.
- Open XAMPP and click Start for Apache and MySQL.
- Extract the project to your XAMPP folder as follows:
  - C:\xampp\htdocs\panthernet
- Go to http://localhost/phpmyadmin/ on your browser.
  - (This may be localhost:80 depending on your configurations.)
- Create a new database, then import social.sql file to it.
- Go to http://localhost/panthernet/login.php.
