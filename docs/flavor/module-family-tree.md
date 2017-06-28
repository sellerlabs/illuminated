# Project structure: Module-Family Tree
by Eduardo Trujillo (@etcinit)

> NOTE: These are personal notes on project organization. They do not represent
the views of coworkers or other collaborators.

One pattern that has seemed to work very well for various projects and
work-related applications, is organizing classes under **modules** and
**families**. An application is first divided into a set of modules, and each
module contains a few top-level classes, plus a few families of classes.

A module is a component or part of the application that focuses one specific
task or goal.

Class families is a more abstract concept. They aggregate classes into groups
depending on a main common trait/goal shared between the classes. In many PHP
projects, this manifests in the form of `Exceptions` and `Interfaces`
namespaces under each module of an application. In some Laravel projects, some
common families are `Requests` (for FormRequests) and `Models`.

Here's a small sample of how the outline of a project following this pattern
might look like:
```
App/                        
│                           
├▶Users/                    
│ │                         
│ ├▶Controllers/            
│ │ │                       
│ │ ├▶UsersController       
│ │ │                       
│ │ └▶UsersApiController    
│ │                         
│ ├▶Exceptions/             
│ │ │                       
│ │ └▶UserNotFoundException
│ │                         
│ ├▶Interfaces/             
│ │ │                       
│ │ └▶UserRepositoryInterface
│ │                         
│ ├▶Models/                 
│ │ │                       
│ │ ├▶User                  
│ │ │                       
│ │ └▶UserProfile           
│ │                         
│ ├▶UserRepository          
│ │                         
│ ├▶UserServiceProvider     
│ │                         
│ └▶UserRouteMapper         
│                           
├▶Http/                     
│                           
└▶Posts/                    
```
# Why?

A default Laravel project will include a namespace structure that only groups
classes into families, not modules: `App/Controllers`, `App/Models`,
`App/Providers`, etc. This actually works very well for small applications.
However, as your application grows in size (15-20 controllers under
`App/Controllers`), this pattern becomes harder to maintain since its more
difficult to find which classes interact or depend on each other.

Organizing your application in modules/families essentially divides your
codebase into a bunch of smaller applications, each with its own set of
exceptions, controllers, traits, etc. This improves the time it takes to find
related components, and makes it easier to claim ownership/resposability for a
slice of the application on a team/s (Example: Team A is in charge of the
Users module, while Team B is in charge of the Posts module).

## Modules are modules, and families are families.

When following this tree structure, it is desirable to avoid creating a
module namespace that behaves more like a family. This reduces confusion for
people joining a project, since they may ask themselves why some class families
should receive special treatment and go on a root-level namespace in the
application.

Like mentioned above, this is the default layout of a Laravel project:
`App/Controllers`, `App/Models`, `App/Providers`, etc.

## Limitations

While this model seems to work well on medium-large application, larger
projects might need additional splitting. This could be possibly done through
another level of namespaces or even by splitting an application into completely
separate packages.

A large number of modules (+25-30) is a good sign that you should consider
performing some sort of split.
