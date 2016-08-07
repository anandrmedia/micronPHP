# micronPHP
A simple lightweight framework for building apps and APIs in PHP

Checkout the wiki for detailed instructions.

## Folder Structure
/
- app -> controllers , views
- cache
- includes -> db.php
- public -> assets, images

## Instructions

Each web page of your app should have a controller and a view. Did I miss model? Yeah! Lets keep this framework simple.
Write your controllers inside app/controllers folder, and write the view inside app/views folder.

When you visit, http://yourmicronsiteurl/pagename , the controller named pagename.php and view named pagename.php will be executed.

Also, you can create virtual routes, that can point to a controller or view.

Routes are defined in includes/routes.php file.

## Helper Functions

### Link Builder
**route('pagename', ["parameter" => "value"])**

Example
``` <a href="<?php echo route('admin/products',array('a' => 'add'))?>" class="btn btn-primary">Add Product</a> ```

### Redirect
**redirectRoute('pagename', ["parameter" => "value"])**

Example
``` redirectRoute('admin/divisions',array('successMessage' => 'Division Added')); ```

### Generating Asset Links
**echo assets('path to css file inside public/assets folder')**

Example
``` <link rel="stylesheet" href="<?php echo assets('pretty/css/prettyPhoto.css')?>" type="text/css" media="screen" charset="utf-8" /> ```

### Including a view inside another view
**loadView('viewName',[array of data to be passed])**

Example
``` loadView('header',["title" => 'Sample Title']) ```


### Form Required Fields Validation
**validateRequired($userInput, $requiredFields)**

Example
```
$requiredFields = ['title','subject']; 
validateRequired($_POST,$requriedFields)
```

### Save Form to Database
**magicInsert('tableName',$_POST)**
Unset any unwanted parameters using unset() function before using magicInsert() 
Example
```
magicInsert('users',$_POST);
```
