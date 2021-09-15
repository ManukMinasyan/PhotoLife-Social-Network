<h1 align="center">PhotoLife.</h3>

<p align="center">
  <a href="https://photolife.minasyan.info/">
    <img src="https://i.imgur.com/QBa9HAJ.jpg" alt="PhotoLife logo" />
  </a>
</p>

<h3 align="center">The Ultimate Photo/Video Sharing Platform.</h3>
<p align="center">An advanced open source social networking platform to run your project with minimal time.</p>

<p align="center">
  <a href="https://laravel.com/">
    <img src="https://laravel.com/img/logomark.min.svg" height="50" alt="Laravel - The PHP Framework For Web Artisans" />
  </a>
     <a href="https://vuejs.org/">
    <img src="https://vuejs.org/images/logo.png" height="50" alt="Vue.js - The ProgressiveJavaScript Framework" />
  </a>
    <a href="https://buefy.org/">
    <img src="https://buefy.org/static/img/buefy.1d65c18.png" height="50" alt="Buefy - Lightweight UI components for Vue.js based on Bulma" />
  </a>
    <a href="https://socket.io/">
    <img src="https://socket.io/images/logo.svg" height="50" alt="SOCKET.IO - FEATURING THE FASTEST AND MOST RELIABLE REAL-TIME ENGINE" />
  </a>
</p>

## Demo
Demo: https://photolife.minasyan.info/ <br/>
<strong>Login:</strong> john_doe <br/>
<strong>Password:</strong> password
<br /><br />
Admin Demo: https://photolife.minasyan.info/dashboard <br/>
<strong>Login:</strong> admin@site.com <br/>
<strong>Password:</strong> password
<br /> <br />
<strong>Telescope:</strong> https://photolife.minasyan.info/telescope <br />

## Installation

### Server Requirements
The Laravel framework has a few system requirements. All of these requirements are satisfied by the Laravel Homestead virtual machine, so it's highly recommended that you use Homestead as your local Laravel development environment.

However, if you are not using Homestead, you will need to make sure your server meets the following requirements:

- PHP >= 7.2.0
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- FFmpeg

### Step #1
Copy files to your working directory:

### Step #2
#### Configuration
Next make sure to create a new database and add your database credentials to your .env file, you will also want to add your application URL in the APP_URL variable:
```
APP_URL=http://localhost
DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

### Step #3
Install PhotoLife with [composer](https://getcomposer.org/doc/00-intro.md):
Go to the project directory and run the command below:
Installation by one command:
```
$ composer app-install
```

## Useful Commands
#### Create the superadmin account
```
php artisan photolife:superadmin
```

#### Running Demo Data Seeder
If you want to insert sample data into your application, run the command below:
```
php artisan db:seed --class=DemoDataSeeder
```

### Supervisor configuration for socket server
```
#/etc/supervisor/conf.d/photolife_minasyan.conf

[program:photolife.minasyan.info-echo-server]
directory=/var/www/html/photolife.minasyan.info
command=laravel-echo-server start
autostart=true
autorestart=true
user=root
redirect_stderr=true
stdout_logfile=/var/www/html/photolife.minasyan.info/storage/logs/echo_server.log
```

# To Do
### --- Dashboard ---
- [ ] Home Page | Add Google Analytics Charts

- [ ] Admins(Users) | Add methods to add,edit,delete dashboard users.
- [ ] Admins(Users) | Add role and permission functionality

- [ ] Members | Edit Functionality
- [ ] Members | Block Functionality
- [ ] Members | List Filter By` Privacy, Has Avatar, Has Phone Number, Has WebSite, Has Bio, Registration Date(Calendar, date range)

- [ ] Posts | Show in table likes and bookmarks count
- [ ] Posts | Slider, to show post images and videos հարմար ձևով
- [ ] Posts | List Filter By` Author, Hashtag, Created At(Calendar, date range)

- [ ] Reports | Send notification to user when post deleted.
- [ ] Reports | List Filter By` Reporter, Report Type, Created At(Calendar, date range)

### --- Web App ---
- [ ] Profile | Add Stories Functionality.
- [ ] Profile | Keep Stories On Collection, Functionality.
- [ ] Profile | Add recommended users functionality.

- [ ] Post | Add people tagging system (tag peoples on photo).
- [ ] Post | Add location picking functionality.
- [ ] Post | Photos Editing Tool(Resizce, Cropy, Effects)
- [ ] Post | Add Posts to Archive

- [ ] Post Comments | Add hashtag and mention members functionality(with notification to users)
- [ ] Post Comments | Real time update comments with socket.

- [ ] Messenger | Add Group Chat Functionality

- [ ] Settings | Add Notifications(What type of notifications do you want recieve)(Post Likeing, Following, @mention, New Message)
- [ ] Settings | Add Delete Account Functionality
