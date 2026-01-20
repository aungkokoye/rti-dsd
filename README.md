## About Domain Service Desk Basic Tutorial

***compatible with Apple (Mac Silicon | Intel Silicon)***

PHP 8.2 | Yii 2.x | MySQL 8.0 | Docker Compose 3.8 | Composer 2.8

#### Repository URL
````
```git@github.com-third:aungkokoye/rti-dsd.git```

#### how to start docker
````
cd docker
docker compose up --build -d
````
#### how to stop docker
````
cd docker
docker compose down
````
#### Inside app web container
````

docker exec -it dsd_app bash
#### DB Editor Settings
````
host: 127.0.0.1
port: 3606
user: root
password: root
database: dsd
````
#### Schickling MailCatcher Documentation:
For testing email functionality, we are using the Schickling MailCatcher.
It is accessible at `http://localhost:2080`.
This allows you to view emails sent by the application without needing a real email server.
MailCatcher is configured by setting the environment variable in your `.env` file: