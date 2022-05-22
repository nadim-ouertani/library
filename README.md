## About Library

A simple library projects using **Laravel queues**, **TDD** && **Vuejs**

## How to run the project

- **Clone the repo to your server using:**

`https://github.com/nadim-ouertani/library.git`

- **Install all the dependencies using:**

`#composer install`

- **Copy the `.env.example` file wit the name of `.env` using:**

`#cp .env.example .env`

you need to add your database in the .env file

- **Run the key generate command**

`#php artisan key:generate`

- **Migrate && Run**

`#php artisan migrate`

`#php artisan run`

- **To run the Features && Unit test please add this two lines in your `phpunit.xml`**

`<server name="DB_CONNECTION" value="sqlite"/>`

`<server name="DB_DATABASE" value=":memory:"/>`


