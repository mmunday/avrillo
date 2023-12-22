# System Requirements

To run this application, you will require:
- [PHP ^8.1]([https://php.net])
- [Composer]([https://getcomposer.org/])
- [Docker]([https://www.docker.com/products/docker-desktop/])

Installation steps for these tools will vary depending on whether you are running 
on Windows, Linux or Mac.

# Installation

You will need to clone this repository and then run a `composer install` which will
download the required dependencies. This may take a few minutes.

# Running the application

The application was bootstrapped using Laravel Sail. From the application root directory,
run `./vendor/bin/sail up`.

This will pull, build & start the Docker containers that run the application.

# Using the API

The application exposes two API endpoints:

- `GET /api/quotes` which will return five random quotes. These quotes are cached and may not change between requests.
- `POST /api/quotes/refresh` which will also return five random quotes. This endpoint wipes any existing cache, refreshing the quotes returned.

The application runs on `localhost` using port `80` by default. An example full URL would be `http://localhost/api/quotes`.

Both endpoints return data as a JSON array. For example: 

```json
[
    "I channel Will Ferrell when I'm at the daddy daughter dances",
    "I am Warhol. I am the No. 1 most impactful artist of our generation. I am Shakespeare in the flesh.",
    "So many of us need so much less than we have especially when so many of us are in need",
    "Have you ever thought you were in love with someone but then realized you were just staring in a mirror for 20 minutes?",
    "So many of us need so much less than we have especially when so many of us are in need"
]
```

# Authentication

Basic authentication is provided by way of an API token that should be included in request header
when using either endpoint. The `X-TOKEN` key should be used for the header. By default, the token
expects a value of `my-not-so-secret-token`, although this can be overridden in the `.env` file.

As an example, the following cURL command could be used to make requests with the token:

```bash
curl -H "X-TOKEN: my-not-so-secret-token" http://localhost/api/quotes
```

Tools such as [Postman](https://www.postman.com/) could also be used to make requests.
