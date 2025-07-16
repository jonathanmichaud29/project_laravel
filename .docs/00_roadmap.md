# Laravel Learning Roadmap

A comprehensive guide to master Laravel from basics to advanced concepts.

## 🎯 Prerequisites

Before diving into Laravel, ensure you have:

- **PHP 8.2+** fundamentals (OOP, namespaces, traits, interfaces)
- **Composer** dependency manager
- **Basic web development** (HTML, CSS, JavaScript)
- **Database concepts** (SQL, relationships)
- **Command line** basics
- **Git** version control

---

## 📚 Phase 1: Foundation

### 1.1 Laravel Basics

- **Installation & Setup**

  - Installing Laravel via Composer
  - Laravel Sail (Docker environment)
  - [Directory structure overview](01-1_laravel-basics/structure-overview.md)
  - Configuration files (.env, config/)

- **MVC Architecture**

  - Understanding Model-View-Controller pattern
  - How Laravel implements MVC
  - [Request lifecycle overview](01-1_laravel-basics/request-lifecycle.md)

- **[Routing](01-1_laravel-basics/routing.md)**
  - Basic routes (GET, POST, PUT, DELETE)
  - Route parameters and constraints
  - Named routes
  - Route groups and middleware

**Practice Project**: Create a simple "Hello World" application with multiple routes

### 1.2 Views & Blade Templates

- **Blade Templating Engine**

  - Blade syntax and directives
  - Layouts and sections
  - Including partials
  - Loops and conditionals
  - Blade components

- **Asset Management**
  - Public folder structure
  - CSS and JavaScript inclusion
  - Vite integration basics

**Practice Project**: Build a static website with multiple pages using Blade templates

---

## 🗄️ Phase 2: Database & Models (Weeks 3-4)

### 2.1 Database Fundamentals

- **Database Configuration**

  - Database connections
  - Multiple database connections
  - Environment-specific configs

- **Migrations**

  - Creating and running migrations
  - Schema builder
  - Modifying tables
  - Rolling back migrations

- **Seeders & Factories**
  - Database seeding
  - Model factories
  - Fake data generation

### 2.2 Eloquent ORM

- **Basic Eloquent**

  - Creating models
  - CRUD operations
  - Query builder basics
  - Mass assignment protection

- **Relationships**

  - One-to-one relationships
  - One-to-many relationships
  - Many-to-many relationships
  - Polymorphic relationships

- **Advanced Queries**
  - Where clauses and conditions
  - Ordering and grouping
  - Joins and subqueries
  - Scopes (local and global)

**Practice Project**: Build a blog system with posts, categories, and tags

---

## 🎮 Phase 3: Controllers & Forms (Weeks 5-6)

### 3.1 Controllers

- **Controller Basics**

  - Creating controllers
  - Controller methods
  - Resource controllers
  - Route model binding

- **Request Handling**
  - Accessing request data
  - Request validation
  - File uploads
  - Request lifecycle

### 3.2 Forms & Validation

- **Form Handling**

  - Creating forms in Blade
  - CSRF protection
  - Form method spoofing
  - File upload forms

- **Validation**
  - Validation rules
  - Custom validation rules
  - Form requests
  - Error handling and display

**Practice Project**: Create a contact form with validation and file upload

---

## 🔐 Phase 4: Authentication & Authorization (Weeks 7-8)

### 4.1 Authentication

- **Laravel Breeze/Jetstream**

  - Installing authentication scaffolding
  - Registration and login
  - Password reset functionality
  - Email verification

- **Manual Authentication**
  - Auth facade
  - Guards and providers
  - Custom authentication logic

### 4.2 Authorization

- **Gates and Policies**

  - Defining gates
  - Creating policies
  - Authorizing actions
  - Policy methods

- **Middleware**
  - Built-in middleware
  - Creating custom middleware
  - Middleware groups
  - Route middleware

**Practice Project**: Build a multi-user blog with authentication and user-specific content

---

## 🛠️ Phase 5: Intermediate Features (Weeks 9-12)

### 5.1 Session & Caching

- **Session Management**

  - Session configuration
  - Storing and retrieving data
  - Flash messages
  - Session drivers

- **Caching**
  - Cache drivers
  - Cache operations
  - Cache tags
  - Database query caching

### 5.2 File Storage & Mail

- **File Storage**

  - Filesystem configuration
  - Storing and retrieving files
  - File uploads
  - Cloud storage (S3, etc.)

- **Mail System**
  - Mail configuration
  - Creating mailables
  - Sending emails
  - Mail queues

### 5.3 Error Handling & Logging

- **Error Handling**

  - Exception handling
  - Custom error pages
  - Debugging with dd() and dump()
  - Laravel Telescope

- **Logging**
  - Log channels
  - Writing to logs
  - Log levels
  - Custom log handlers

**Practice Project**: E-commerce site with file uploads, email notifications, and caching

---

## 🚀 Phase 6: Advanced Backend (Weeks 13-16)

### 6.1 API Development

- **RESTful APIs**

  - API routes
  - Resource controllers
  - JSON responses
  - API authentication (Sanctum)

- **API Resources**
  - Resource classes
  - Resource collections
  - Conditional attributes
  - Relationships in resources

### 6.2 Queue System

- **Queue Basics**

  - Queue configuration
  - Creating jobs
  - Dispatching jobs
  - Job batching

- **Queue Workers**
  - Running queue workers
  - Failed jobs
  - Job retry logic
  - Horizon dashboard

### 6.3 Events & Listeners

- **Event System**

  - Creating events
  - Event listeners
  - Event broadcasting
  - Model events

- **Notifications**
  - Creating notifications
  - Notification channels
  - Database notifications
  - Real-time notifications

**Practice Project**: Build a task management API with real-time notifications

---

## 🏗️ Phase 7: Advanced Architecture (Weeks 17-20)

### 7.1 Service Container & Providers

- **Dependency Injection**

  - Service container
  - Binding services
  - Resolving dependencies
  - Service providers

- **Package Development**
  - Creating packages
  - Service provider registration
  - Publishing assets
  - Package configuration

### 7.2 Advanced Eloquent

- **Model Relationships**

  - Has-one-through
  - Has-many-through
  - Polymorphic relationships
  - Many-to-many polymorphic

- **Advanced Features**
  - Accessors and mutators
  - Attribute casting
  - Model events
  - Query scopes

### 7.3 Testing

- **Unit Testing**

  - PHPUnit basics
  - Testing models
  - Mock objects
  - Test databases

- **Feature Testing**
  - HTTP tests
  - Authentication testing
  - Database testing
  - Browser testing (Dusk)

**Practice Project**: Build a complex application with comprehensive test coverage

---

## 🎯 Phase 8: Modern Laravel (Weeks 21-24)

### 8.1 Modern Frontend Integration

- **Inertia.js**

  - Setting up Inertia with React/Vue
  - Server-side rendering
  - Form handling
  - Authentication with Inertia

- **Livewire**
  - Component-based development
  - Real-time features
  - File uploads
  - Alpine.js integration

### 8.2 Performance & Optimization

- **Database Optimization**

  - Query optimization
  - Database indexing
  - Eager loading
  - Database monitoring

- **Application Performance**
  - Caching strategies
  - Queue optimization
  - Code optimization
  - Performance monitoring

### 8.3 DevOps & Deployment

- **Development Tools**

  - Laravel Sail
  - Laravel Valet
  - Debugging tools
  - Code quality tools

- **Deployment**
  - Production environment setup
  - Server configuration
  - CI/CD pipelines
  - Monitoring and logging

**Practice Project**: Deploy a production-ready application with modern frontend

---

## 🎖️ Phase 9: Expert Level (Weeks 25-28)

### 9.1 Advanced Patterns

- **Design Patterns**

  - Repository pattern
  - Service layer pattern
  - Observer pattern
  - Factory pattern

- **Clean Architecture**
  - Domain-driven design
  - SOLID principles
  - Dependency inversion
  - Hexagonal architecture

### 9.2 Scaling & Architecture

- **Microservices**

  - Service-oriented architecture
  - API gateways
  - Service communication
  - Database per service

- **High Availability**
  - Load balancing
  - Database clustering
  - Caching strategies
  - Queue scaling

### 9.3 Security & Best Practices

- **Security**

  - OWASP top 10
  - Input validation
  - SQL injection prevention
  - XSS protection

- **Best Practices**
  - Code organization
  - Documentation
  - Code reviews
  - Performance monitoring

**Practice Project**: Build and deploy a scalable, secure application

---

## 🛠️ Essential Tools & Resources

### Development Tools

- **IDE**: PhpStorm, VS Code with PHP extensions
- **Database**: MySQL, PostgreSQL, SQLite
- **Debugging**: Laravel Telescope, Xdebug
- **Testing**: PHPUnit, Pest
- **Frontend**: Vue.js, React, Alpine.js

### Learning Resources

- **Official Documentation**: Laravel.com/docs
- **Video Courses**: Laracasts, Udemy
- **Books**: "Laravel: Up & Running" by Matt Stauffer
- **Community**: Laravel News, Laravel.io
- **Practice**: Laravel Bootcamp, Laracasts challenges

### Package Ecosystem

- **Development**: Laravel Debugbar, IDE Helper
- **Authentication**: Sanctum, Passport
- **Admin**: Nova, Filament
- **Testing**: Pest, Laravel Dusk
- **Deployment**: Envoy, Vapor

---

## 📈 Learning Tips

### Study Schedule

- **Daily**: 2-3 hours of focused learning
- **Weekly**: Complete one major topic
- **Monthly**: Build a complete project
- **Practice**: Code daily, even if just 30 minutes

### Best Practices

1. **Build Projects**: Apply concepts immediately
2. **Read Documentation**: Official docs are your best friend
3. **Join Community**: Laravel Discord, Reddit, Twitter
4. **Follow Conventions**: PSR standards, Laravel conventions
5. **Test Everything**: Write tests for your code
6. **Stay Updated**: Follow Laravel releases and updates

### Common Mistakes to Avoid

- Skipping the basics
- Not following MVC patterns
- Ignoring security practices
- Not writing tests
- Over-engineering simple solutions
- Not using Laravel's built-in features

---

## 🎯 Milestone Projects

### Beginner (Weeks 1-8)

- **Personal Blog**: Posts, categories, comments
- **Task Manager**: CRUD operations, user authentication
- **Contact Management**: Forms, validation, file uploads

### Intermediate (Weeks 9-16)

- **E-commerce Store**: Products, cart, payments, emails
- **Social Media Platform**: Posts, likes, follows, real-time updates
- **Learning Management System**: Courses, lessons, quizzes

### Advanced (Weeks 17-24)

- **Multi-tenant SaaS**: Subscription billing, tenant isolation
- **Real-time Chat**: WebSockets, broadcasting, presence
- **API-first Application**: Mobile app backend, documentation

### Expert (Weeks 25-28)

- **Microservices Architecture**: Multiple services, event sourcing
- **High-performance Application**: Caching, queues, optimization
- **Open Source Package**: Contribute to Laravel ecosystem

---

## 🏆 Certification & Career

### Laravel Certifications

- **Laravel Certified Developer**: Official certification
- **Prepare**: Practice exams, documentation review
- **Topics**: All phases of this roadmap

### Career Paths

- **Backend Developer**: Focus on API development, databases
- **Full-stack Developer**: Add frontend frameworks
- **DevOps Engineer**: Deployment, scaling, monitoring
- **Technical Lead**: Architecture, team management
- **Freelancer**: Client projects, consulting

### Portfolio Building

- **GitHub**: Active contribution, clean code
- **Projects**: Diverse, well-documented applications
- **Blog**: Technical writing, tutorials
- **Open Source**: Contribute to Laravel packages

---

This roadmap provides a structured path to Laravel mastery. Remember that learning is iterative - you'll revisit concepts as you build more complex applications. Focus on understanding concepts deeply rather than rushing through topics. Good luck on your Laravel journey!
