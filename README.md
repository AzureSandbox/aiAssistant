## Prerequisites
1. PHP
2. Composer
3. npm
4. vue2
5. vuetify (make sure you get the version which is compatible with vue2)
6. sql database - I used phpmyadmin

## Running the application locally
Once you clone this repo, make sure you run the following commands
1. We need composer to install the php dependencies
    `composer install`
2. Locate the .env file and input your following data in there
    - APP_URL - this will be the server on which the backend runs. Verify that the port you choose is not already occupied to avoid a conflict
    - OPENAI_API_KEY - This is the api key which will be used to send the api call to OpenAI server. I was hesitent to put it here since its sensitive information. Assuming, this application runs on AWS, I would rather store this key in AWS Secrets Manager and allow the aws container to access the key from there and put it in the container's environment variables. But for POC, this place is fine.
    - DB_DATABASE - name of the database you have on your local
    - DB_USERNAME - username you use to login to db, root my default
    - DB_PASSWORD - password you use to login to db, leave it empty if you don't have any
    Now generate a new application key
        `php artisan key:generate`
3. Make sure you have a running db connection locally. Then run
    `php artisan migrate`. This migrates all the necessary tables in the database. There might be some unnecessary tables which will be created by default.
4. Installing frontend dependencies
    `npm install -g @vue/cli`
    `cd ai-assistant`
    `vue create frontend` - when it asks for the version, select vue2
    `cd frontend`
    `vue add vuetify`
5. Run the frontend - `npm run serve`, by default, this should run on localhost:8080
6. Run the backend - `php artisan serve`, by default, this should run on localhost:8000
