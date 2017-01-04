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
- You should be able to define a frontpage/landing page *DONE*
- You should be able to separate pages and data for these in folders *DONE*
- Foldernames defines the url for a page (folder called: contact will create url: /contact) *DONE*
- You should be able to define subpages, by placing these in the folder of the parent *DONE*
- Pages should be categorized *DONE*
    - a categorization should be made by a foldername like subpages *DONE*
    - every page inside a category will be a subpage to that category in the url *DONE*
    - an API category with the page projects should get the url: /api/projects *DONE*


# Blog Posts
- You should be able to create a blog post, by folder name and date *DONE without dates*
    - a content.md file should be placed inside for content of this blog post *DONE*
    - a settings.yml file should be placed inside for categories and variables for this blog post *DONE categories are parent folders*
- You should be able to to categorize blog post *DONE*
     - a categorization should be made by an array in the settings file for the blog post *DONE*
- A general variable with a list of all blog post in descending order from the newest should be generated automatically *DONE*
- A general variable for each category, with all blog post for that category, in desc order, should be generated automatically *DONE*
- A template for all blog post should have a name of posts.blade.php *DONE*
- A template for a category should have a name of category.blade.php *DONE*

# Linked Services
- A sitemap file should be created for each project
    - to make it easier for search engines
    - to make it possible to track changes
    - to make it possible to track new posts
    - to make it possible to setup Discus for new posts
    - to make it possible to alert RSS readers about new posts

# Commands
- build - build the source file into Static HTML files *DONE*
- create:post - Scaffold out a new blog post *DONE*
- create:page - Scaffold out a new page *DONE*
    - --template place 

# Needed Tech
- CLI framework in PHP 
- Dependency Injection Container
- Markdown Parser
- Filesystem Library
- Yaml Parser
- Template Parser (Blade or other)
- Slugify string

# Optimizations
- Images should be copied from source into build /images *DONE*
- Images should be placed in images folder to be copied automatically *DONE*
- You can get a settings.yml with predefined variables based on template
- Feed back in CLI on build 
- sitemap.txt *DONE*
- should not delete everything from build folder, leave css and js *DONE*


