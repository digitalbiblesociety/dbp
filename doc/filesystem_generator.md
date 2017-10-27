# JSON Generator

The command `` php artisan filesystem:update `` will update the json lists in the s3 bucket.
 
 ### Arguments
 
 #### 1) Section
 You can specify a custom section to run through.
 This will cause the program only to generate jsons
 for a specific section of the API. The default option is *all*.
 
    php artisan filesystem:update bibles

 - all
 - languages
 - countries
 - alphabets
 - bibles
 
 #### 2) Driver
 This allows the program to place the API files in a number of
 different storage solutions. The default option is *local*.
 More drivers could be added on request, Dropbox ect.
 
    php artisan filesystem:update all local
 
 - local
 - ftp
 - s3
 - rackspace
 
 
 #### 3) Organization
You can specify a custom section to run through.
This will cause the program only to generate jsons
for a specific organization's filesets. This will only effect
the bibles route. The default option is *all*. 
 
    php artisan filesystem:update bibles local faith-comes-by-hearing