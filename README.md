# BeanWay: Coffee Recipe Community Platform

**Course:** [IT320]  

This repository contains all project files for BeanWay, a web application for a coffee recipe-sharing community.

## 1. Project Overview

BeanWay is a dynamic web platform built with HTML, CSS, JavaScript, and a MySQL database. It serves as a community hub where coffee enthusiasts can discover new recipes, take an interactive "coffee mood" quiz, and submit their own recipes for publication.

The system supports two distinct user roles, User and Admin, each with a unique interface and set of permissions.

## 2. Core Features

### User & Guest Features
* **Authentication:** Secure login and signup pages for user registration.
* **Recipe Browsing:** Guests and Users can browse all "Approved" coffee recipes in a gallery format.
* **Recipe Viewing:** Users can click on any recipe to see a detailed view with ingredients, steps, tips, and comments.
* **Coffee Mood Quiz:** An interactive, multi-step quiz that suggests a coffee type based on the user's answers.
* **Commenting:** Logged-in users can post new comments and delete their *own* comments on recipe pages.
* **Informational Pages:** Static content pages for guests and users to learn about coffee bean types and brewing methods.

### User Dashboard Features
* **Recipe Submission:** Users can submit new recipes via a dedicated form.
* **Personal Dashboard:** A "My Recipes" page where users can track the status of their submissions.
* **Status Tracking:** Recipes are clearly marked as **Pending**, **Approved**, or **Rejected**.
* **CRUD Operations:** Users can **Edit** their existing recipes (including rejected ones for resubmission) or **Delete** them from their dashboard.

### Admin Dashboard Features
* **Admin Review Panel:** A separate dashboard for the Admin to review all "Pending" recipe submissions from users.
* **Approve/Reject:** The Admin has actions to "Accept" or "Reject" each recipe submission.
* **Feedback:** The Admin can leave feedback (though this feature is a UI mock-up in `admin.html`).
* **Secure Access:** The Admin dashboard is accessed via the main login page by selecting the "Admin" role.

## 3. System Flow

1.  **Guest:** A new visitor lands on `homepage.html`. They can browse public pages (`recipes.html`, `beans.html`) but cannot comment or submit recipes.
2.  **Signup:** The guest creates an account using `signup.html`.
3.  **Login:** The user logs in via `login.html`.
    * If they select **User**, they are directed to `userpage.html`.
    * If they select **Admin**, they are directed to `admin.html`.
4.  **User Flow:** The User explores the site, takes the `coffeeQuiz.html`, and decides to add a new recipe (`add-recipe.html`). After submission, they can see their recipe marked as "Pending" on their `profile.html` page.
5.  **Admin Flow:** The Admin logs in and goes to `admin.html`. They see the user's new recipe, review it, and click "Accept".
6.  **Resolution:** The recipe is now "Approved". The user sees this status change on their `profile.html` page, and the recipe now appears publicly in the main `recipes.html` gallery for all users to see.

## 4. Project Files

This repository includes all necessary files for the project:

### Database
* `beanway.sql`: The MySQL database dump containing all table structures and sample data.

### Core HTML Pages
* `homepage.html`: The main landing page for guests.
* `login.html`: The login page for both Users and Admins.
* `signup.html`: The user registration page.

### User-Facing Pages (Logged-In)
* `userpage.html`: The main landing page for logged-in users.
* `add-recipe.html`: Form for submitting a new recipe.
* `edit-recipe.html`: Form for editing an existing recipe.
* `profile.html`: The user's personal dashboard to manage their recipes.
* `coffeeQuiz.html`: The interactive coffee quiz.
* `recipes1.html`: The recipe gallery (logged-in version).
* `view-recipe1.html`: The detailed recipe view (logged-in version with commenting).
* `beans1.html`: Coffee beans page (logged-in version).
* `Brewing1.html`: Brewing methods page (logged-in version).

### Guest-Facing Pages (Logged-Out)
* `recipes.html`: The recipe gallery (guest version).
* `view-recipe.html`: The detailed recipe view (guest version, no comment box).
* `beans.html`: Coffee beans page (guest version).
* `Brewing.html`: Brewing methods page (guest version).

### Admin Pages
* `admin.html`: The admin dashboard for reviewing recipe submissions.
