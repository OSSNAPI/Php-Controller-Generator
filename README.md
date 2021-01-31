# Php-Controller-Generator
 A simple and robust tool to generate PHP Controllers using postman exports, currently it is designed to generate code for [Scrawler Framework](https://github.com/scrawler-php/scrawler) but it can be tweaked a little to generate controller and stubs for any PHP Framework. This was created as part of **postman hackathon**

## How to use it
- Download the zip file from the latest release
- Copy the contents inside `generator` folder in the Root of your Scrawler Application
- Export the api collection from Postman , you will get a JSON file
- Copy this json file inside your `generator` folder and rename the json file to `postman.json`
- Open cmd inside `generator` directory and run `php generator.php` and Viola! your controller and API end points are ready

## What it does currently
- It generates all the controller with required function inside `app/Controllers/Api` using ** Postman Collection Export** json file
- It also stubs these controllers with example reponses taken from Postman's JSON file
- Thanks to [Automatic Router](https://github.com/scrawler-php/router) of Scrawler, all the api end points are automatically generated
- This creates a boilerplate and Mock server as starting point to create you actual server implementation

## Current limitations
- It does not work with folders inside collection all the api's need to be listed in collection without folders
- All the variable in api should be at end of endpoint eg `/users/:id` something like `/user/:id/posts` wont work
- This uses Scrawler's automatic routing controller pattern to generate controllers


## License

Scrawler Router is created by [Pranjal Pandey](https://www.physcocode.com) and released under
the MIT License.
