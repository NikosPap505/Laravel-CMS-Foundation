<?php

use App\Models\Category;
use App\Models\Post;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

// Blog Index
Breadcrumbs::for('blog.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog.index'));
});

// Blog Category
Breadcrumbs::for('blog.category', function (BreadcrumbTrail $trail, Category $category) {
    $trail->parent('blog.index');
    $trail->push($category->name, route('blog.category', $category));
});

// Blog Post
Breadcrumbs::for('blog.show', function (BreadcrumbTrail $trail, Post $post) {
    $trail->parent('blog.category', $post->category);
    $trail->push($post->title, route('blog.show', $post));
});

// About
Breadcrumbs::for('about', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('About Us', route('about'));
});

// Services
Breadcrumbs::for('services', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Services', route('services'));
});

// Contact
Breadcrumbs::for('contact.create', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Contact', route('contact.create'));
});

