#  AvocadoFlix

AvocadoFlix is a simple web-based movie streaming platform where users must log in before accessing and watching content. This project is designed as a student system focusing on user authentication, content access control, and basic media display.

---

##  Project Goal

The main goal of AvocadoFlix is to:

* Require users to **register and log in**
* Restrict access to movie content unless authenticated
* Display a collection of movies in a clean interface
* Simulate a streaming platform experience

---

##  Features

*  User Registration
*  User Login & Authentication
*  Session-based Access Control
*  Movie Listing Page
*  Video Player (local or embedded)
*  Unauthorized users cannot access movies

---

##  Technologies Used

* **Frontend:** HTML, CSS
* **Backend:** PHP
* **Database:** MySQL 
* **Server:** nginx

---

##  Project Structure

```
AvocadoFlix/
│── register.php       # User registration
│── login.php          # Login handler
│── logout.php         # Logout
│── home.php           # Movie dashboard (protected)
│── watch.php          # Video player page
│── config.php         # Database connection
│── assets/            # CSS, JS, images
│── videos/            # Sample movie files
```

---

##  Access Control Logic

* Users must log in first
* Sessions are used to track authentication
* Protected pages (home.php, watch.php) check session before loading

---

##  Notes

* This project is for **educational purposes only**
* Videos used should be **free or non-copyrighted**
* Focus is on system design, not content distribution

---

##  Developers

* Baya, Andrea
* Beltran, Andrey
* Lescano, Ashley
* Moredo, Edhly
* Wilcken, Audrey

---

##  Future Improvements

* Search & filter movies
* Categories (Action, Comedy, etc.)
* User profiles
* Admin panel for uploading movies
* Better UI (Netflix-style)

---
