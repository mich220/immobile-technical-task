## Notes from author

#### What added?
- Php 8.2
- Symfony 6.2
- Used php-fpm alpine image
- Used symfony packages like form, validator, serializer, security, doctrine, etc. to achieve goal faster.
- Added basic docker environment.
- Added cs-fixer.
- Added php code coverage.
- Added example functional tests for Controllers.
- Added example unit tests for ResponseFactory.
- Added firewalls: admin, auth, api.
- Added authentication based on jwt (also added custom jwt encoders).
- You can create application with access to api.
- You can create admin user with access to admin panel.
- You can create dummy data.
- For api requests, you MUST set Accept header and Content-Type header.
- Added immobile-technical-task.postman_collection.json file with example requests.

#### What I could add? (if I had more time)
- Add voters and specifications related to accessing objects.
- Add ci/cd.
- Add logging elk or similar.
- Add swagger documentation.
- Add performance monitoring (blackfire or similar).
- Add more test coverage, current should be enough for this task.
- Maybe add crud service, but this task was too simple to add it.
- Add login with different providers. How could it look? For example: 
  - let client send provider code in request
  - resolve provider by code
  - get user data from provider and authenticate him

## How to start project

These are following steps to setup project:

```
cp .env.dist .env
```

then prepare docker environment:
```
docker-compose build
docker-compose up -d
```

to enter docker container:
```
docker-compose run php bash
```


inside of docker container:
```
composer install
bin/console doctrine:database:create
bin/console doctrine:schema:create
```

tests:
```
vendor/bin/phpunit
vendor/bin/phpunit --coverage-text
```

generate products with categories:
```
bin/console app:create-dummy-data
```

create admin user:
```
bin/console app:create-user {username}
```

create application user:
```
bin/console app:create-application
```

to use api, you need to login first (key and secret are values given in step above):
```
curl -X POST \ 
 -H "Content-Type: application/json" \
 -d '{"data": {"key":"XXX","secret":"XXX"}}' \
  http://localhost/api/auth
```

then you can use api:
```
curl -X POST \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6InRlc3RfYXBwIiwicm9sZXMiOlsiUk9MRV9BUFAiXSwiYXBwLWtleSI6InRlc3RfYXBwIiwiZXhwIjoxNjg3NDQzOTczfQ.EKYoVyVhbNQv6tn-jg_7sEE25xNkOj_kUpOX9fKYopY" \
 -d '{"title":"Dummy title", "description": "Dummy desc"}' \
   http://localhost/api/category
```

to retrieve on sale products:
```
curl -X POST \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6InRlc3RfYXBwIiwicm9sZXMiOlsiUk9MRV9BUFAiXSwiYXBwLWtleSI6InRlc3RfYXBwIiwiZXhwIjoxNjg3NDQzOTczfQ.EKYoVyVhbNQv6tn-jg_7sEE25xNkOj_kUpOX9fKYopY" \
  http://localhost/api/category?onSale=true
```

to get response in xml format (just add Accept header and set it to: application/xml):
```
curl -X POST \
 -H "Content-Type: application/json" \
 -H "Accept: application/xml" \
 -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6InRlc3RfYXBwIiwicm9sZXMiOlsiUk9MRV9BUFAiXSwiYXBwLWtleSI6InRlc3RfYXBwIiwiZXhwIjoxNjg3NDQzOTczfQ.EKYoVyVhbNQv6tn-jg_7sEE25xNkOj_kUpOX9fKYopY" \
  http://localhost/api/category?onSale=true
```
