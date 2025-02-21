# Blog Platform

A Symfony-based blog platform that allows users to create, view, and comment on blog posts. Authenticated users can manage (create, edit, and delete) their own blog posts, including uploading images manually. Other authenticated users can view posts and add comments, but only the post owner can modify the content.

## Description

The Blog Platform is a full-featured application built with the Symfony framework. It includes:

- **User Authentication & Authorization:**  
  Secure login, logout, and password reset functionalities. Users must be authenticated to create blog posts or comments.  
- **Blog Management:**  
  Authenticated users can create new blog posts with a title, description, and an optional image upload. Users can edit or delete only their own posts.  
- **Manual Image Upload:**  
  Blog posts support image uploads without using additional bundles; images are stored in a public directory.  
- **Comment System:**  
  Any logged-in user can comment on any blog post. Comments display the author's information and timestamp.  
- **Dashboard:**  
  A personalized dashboard where users can see the list of blog posts they've created.

## Features

- **User Management:**  
  Secure authentication and access control.
  
- **Blog CRUD:**  
  Create, read, update, and delete blog posts. Only the owner can edit or delete a post.

- **Image Handling:**  
  Manual file upload with proper validation, storing images in `public/uploads/blog`.

- **Comments:**  
  A commenting feature on blog posts to allow community interaction.

## Tech Stack

- **Backend:** PHP, Symfony Framework
- **Database:** Doctrine ORM (compatible with MySQL, PostgreSQL, SQLite, etc.)
- **Frontend:** Twig, HTML, CSS, Bootstrap
- **Version Control:** Git, GitHub
- **Async Processing & Email:** Symfony Messenger & Mailer (for password reset emails, optional)

## Installation

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/aayushvyas123/blog_platform.git
   cd blog_platform

2. 
    ```bash
    composer install 

3. ** edit below lines in .env **
    ```bash
    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
    MAILER_DSN=smtp://your_username:your_password@smtp.example.com:port
4. 
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
5. 
    ```
    symfony server:start  


