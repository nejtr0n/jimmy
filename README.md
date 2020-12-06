# CRUD

Simple crud example

### Install
* Clone repository
    
  ```git clone git@github.com:nejtr0n/jimmy.git && cd jimmy```
* Configure environment  
    
  ```cp src/.env.example src/.env```
* Build and run docker environment
  
  ```docker-compose up -d```
* Install composer dependencies
  
  ```make install```
* Bootstrap database
  
  ```make migrate```

API endpoint is accessible through
```
http://localhost/api/v1/articles
```

Swagger documentation address is
```
http://localhost:8080/#/
```

*Here you can test api*

You could run tests
```
make test
```