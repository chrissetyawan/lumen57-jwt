# lumen 5.7 with JWT


## USAGE

```
$ git clone https://github.com/chrissetyawan/lumen57-jwt.git
$ composer install
$ cp .env.example .env
$ vim .env
        DB_*
            configuration your database
	    JWT_SECRET
            php artisan jwt:secret
	    APP_KEY
            key:generate is abandoned in lumen, so do it yourself
            md5(uniqid())，str_random(32) etc.，maybe use jwt:secret and copy it

$ php artisan migrate
$ php artisan db:seed

```

## REST API DESIGN


```
    demo： user, checklist, item
    get    /api/checklists              	 checklist index
    post   /api/checklists              	 create a checklist
    get    /api/checklists/5            	 checklist detail
    put    /api/checklists/5            	 replace a checklist
    patch  /api/checklists/5            	 update part of a checklist
    delete /api/checklists/5            	 delete a checklist
    get    /api/checklists/5/items     		 item list of a checklist
    post   /api/checklists/5/items         add a item
    get    /api/checklists/5/items/8       item detail
    put    /api/checklists/5/items/8       replace a item
    patch  /api/checklists/5/items/8       update part of a item
    delete /api/checklists/5/items/8       delete a item
    get    /api/users/4/checklists         checklist of a user
    get    /api/user/checklists            checklist of current user
```

