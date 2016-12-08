# Static Site Generator

### Problem
To NHS we have used Jigsaw, which with a lot of tinkering kinda did what we liked.

Christian does not really have a big problem, besides the markdown in yaml files doesn't 
get recognized correctly by his editor

My problem is that everything is yaml frontmatter in a markdown file, where a lot of
hacking with parsers gets the job done

### Idea to a solution (as of right now)

##### Both
A new form of static site generator, where everything gets handled, as much as possible,
in pure markdown files

##### Thomas
The filesystem should serve as the structure you had in the yaml files

##### Christian
Filesystem is ok, but with as few files as possible.

Sections could be a placed in single markdown files with a numbering in front of the filename


#### Problems with this idea
Array lists like a list of events cannot be handled without any form of repetition of files, if 
these needs to be handled by the file system

The variable form you get from yaml files, could be substituted by filenames but since,
this will end up in a lot of files again, there's clearly an issue here.

#### Best of both worlds?

A settings file, where variables could be defined.

If a variable only needs to hold a single value, where no markdown is needed, this can just
be defined as a plain string.

If a variable requires markdown, this could point to a markdown file.

If a variable needs to hold an array of items, a folder of markdown files could be given to this variable



# Pages
- You should be able to define a frontpage/landing page
- You should be able to separate pages and data for these in folders
- Foldernames defines the url for a page (folder called: contact will create url: /contact)
- You should be able to define subpages, by placing these in the folder of the parent
- Pages should be categorized
    - a categorization should be made by a foldername like subpages
    - every page inside a category will be a subpage to that category in the url
    - an API category with the page projects should get the url: /api/projects


# Blog Posts
- You should be able to create a blog post, by folder name and date
    - a content.md file should be placed inside for content of this blog post
    - a settings.yml file should be placed inside for categories and variables for this blog post
- You should be able to to categorize blog post
     - a categorization should be made by an array in the settings file for the blog post
- A general variable with a list of all blog post in descending order from the newest should be generated automatically
- A general variable for each category, with all blog post for that category, in desc order, should be generated automatically
- A template for all blog post should have a name of posts.blade.php
- A template for a category should have a name of category.blade.php

# Commands
- build - build the source file into Static HTML files
- create:post - Scaffold out a new blog post
- create:page - Scaffold out a new page
    - --template place 

# Needed Tech
- CLI framework in PHP
- Dependency Injection Container
- Markdown Parser
- Filesystem Library
- Yaml Parser
- Template Parser (Blade or other)

# Optimizations



