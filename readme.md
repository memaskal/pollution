# Environmental Monitoring

This simple web app is a full functional API enabled website for environmental monitoring, assigned as a university project. 
The web app is responsible for data visualization of various atmospheric pollutants. The last, is done using Google map technologies 
on a simple demo site, the data provided to the app was taken from national pollution stations across the country. 
The data of which could be found on this link, [ypeka.gr](http://www.ypeka.gr/Default.aspx?tabid=495&language=el-GR). 
Finally the quality of those measurements can be viewed from here [airqualitynow.eu](http://www.airqualitynow.eu/about_indices_definition.php).

## App's architecture

The app was created using Laravel php framework following the MVC pattern. The app was splitted in four basic routes. 
The /admin route which is only accessible from administrative users. The /demo route which is accessible from all users 
including guests. The /api route, which is this app API endpoint, and finally home route / for the registered users.

### Admin route

From here an administrative user, can see the api usage from specific statistics on the admin dashboard. Moreover an admin can insert-delete
pollution stations and upload measurement files for each station, as they are provided on the link above.

### Demo route

This route contains the demo site demonstrating the basic functionality of the api. The demo site could be developed by any registered user, 
using his api key (given at his registration), to query the api as he wish.  

### API route

This route responds to all the api queries. Basically the functions that are supported are listed below:

1. Retrieval of all stations on the api (ids', description, latitude, longitude)
2. Absolute measurement value of a given pollutant in a specified time and date.
3. Average and standard deviation of given pollutant in a given time period.

### Home route

Since a user is registered to the api, can view his usage statistics and possibly the cost($$) of his api requests, from the home route.
Moreover this route handles register/login requests as well as password retrievals.

## API Syntax

Each api call is marked with a natural number and requires a valid api key. An example for each request type is listed below.

Stations request : http://host.com/api/1?api_token=my_token

```json
{
	"values":[
		{
			"id":"APT",
			"name":"Α.Π.Θ.",
			"latitude":"40.62835298292301",
			"longitude":"22.95875031376954"
		},
		{
			"id":"ATH",
			"name":"Αθήνας",
			"latitude":"37.98390841836567",
			"longitude":"23.72839591979982"
		},
		{
			"id":"PIR",
			"name":"Πειραιάς I",
			"latitude":"37.93281463524375",
			"longitude":"23.64539764404299"
		}
	],
	"status":"OK"
}
```

Absolute value request : http://host.com/api/2?pol_type=CO&st_code=&date=2014-07-15&hour=10&api_token=my_token

```json
{
	"values":[
		{
			"latitude":"37.98390841836567",
			"longitude":"23.72839591979982",
			"abs":0.7
		},
		{
			"latitude":"39.63985771352472",
			"longitude":"22.41501472473146",
			"abs":0.1
		}
	],
	"status":"OK"
}
```

Average value request : http://host.com/api/3?pol_type=CO&st_code=&sdate=2014-07-15&fdate=2016-09-12&api_token=my_token

```json
{
    "values":[
        {
            "latitude":"37.98390841836567",
            "longitude":"23.72839591979982",
            "avg":1.2156955120258,
            "s":0.92195531631346
        },
        {
            "latitude":"39.63985771352472",
            "longitude":"22.41501472473146",
            "avg":0.23555831265508,
            "s":0.19860212091239
        }
    ],
    "status":"OK"
}
```

Each response contains a status message. The last one can be "OK", "INV_API_KEY", "INV_REQ_NUM" or an http error code.

## Installation

For the installation of this app, you will need composer php dependency manager, as well as a working php and mysql server.
Simply run these commands on your shell, inside the project' s main directory.

```shell
composer install
php artisan migrate
```

Create your `.env` file (example is provided) and you are ready to go. If you want to have a quick demo, you can import the sql dump 
of this app provided on the files above, or you can execute the `php artisan db:seed command` to fill the database with test data.