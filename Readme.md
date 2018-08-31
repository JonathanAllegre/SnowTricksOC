
# SnowTrickOC -- How to Install  
  
The 6th project of the OpenClassRoom training: PHP / Symfony application developer  
  
## Getting Started  
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.  
  
### Prerequisites  
 - php 7.2.1  
 - composer: https://getcomposer.org  
  
### Installing  
  
 #### Clone Or Download the project
 - Open your command shell prompt and enter:
 
	> git clone https://github.com/JonathanAllegre/SnowTricksOC.git
	
 - Move in your folder application :
 
	> cd SnowTricksOC
	
 - Open folder with your favorite editor
 
 #### Configuration
 - Replace the file ".env.dist"  with ".env"
 - Replace value of DATABASE_URL by your own configuration.
 - Replace value of MAILER_URL by your own configuration.
 - Run:
	 > composer install

 #### Database Configuration
 - Run:
	 > php bin/console doctrine:database:create
	 > php bin/console doctrine:schema:create

 #### CKEditor Installation
 - Run:
	 > php bin/console ckeditor:install
	 > php bin/console assets:install public
	 
 #### DataFixtures
 - Run:
	> php bin/console doctrine:fixture:load
	
- Enter "Y"

 #### Run php Server
- Run:
	> php -S 127.0.0.1:8000 -t public

	**And now the app works !**

## Little thing to know

The application give you a generic username / password:
 To connect in member space enter:
	- username: admin
	- password: admin
	
Enjoy !
