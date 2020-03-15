# Laravel

Laravel 5.7 Project with Vue2 and [Element](https://github.com/ElemeFE/element)

## Usage

```
git clone https://github.com/l30n/taller.git <project-name>
```

```
cd <project-name>
```

```
composer install
```

## If don't have .env file

```
cp .env.example .env
```

```
php artisan key:generate
```

## Basic start up

```
php artisan serve (if not using laravel valet)
```

Visit `http://localhost:8000`, It works!

## Docker start up

You can download [docker](https://www.docker.com/get-started)

Run command

```
docker-compose up -d
```

Visit `http://localhost`, It works!

## Front end build

```
yarn OR npm install
```

```
yarn watch OR npm run watch
```

```
If using laravel valet, visit http://<project-name>.test
```

Now you're ready to start coding!

## More

The articles can help you to deploy it by yourself:

- [Try Laravel 5.3 + Vue2 with Element](http://codesky.me/archives/try-laravel5-vue2-element-en.wind)
- [Laravel 5.3 + Vue2 + Element 试水](http://codesky.me/archives/try-laravel5-vue2-element-cn.wind)
- [Element Docs](https://element.eleme.io/#/en-US)
