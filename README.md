# U4Digital
Proyecto de cotizacion y administracion de servicios digitales

## Requerimientos del sistema
- PHP > 7.0
- PHP Extensions: PDO, cURL, Mbstring, Tokenizer, Mcrypt, XML, GD
- Node.js > 6.0
- Composer > 1.0.0

## Instalación
1. Instalar composer como se explica [aquí](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
2. Instalar Node.js siguiendo esta guía [aquí](https://nodejs.org/en/download/package-manager/)
3. Clonar el repositorio
```
$ git clone https://github.com/u4innovation/U4Digital
```
4. Cambiar el directorio a:
```
$ cd U4Digital
```
5. Copiar `.env.example` en `.env` y modificar de acuerdo al ambiente
```
$ cp .env.example .env
```
6. Instalar dependencias de composer
```
$ composer install --prefer-dist
```
7. Generar un App Key
```
$ php artisan key:generate
```
8. Instalar otras dependencias de Node.js
```
$ npm install
$ npm run dev
```
9. Correr las migraciones de la Base de Datos, con algunos valores de muestra
```
$ php artisan migrate --seed
```


## Levantar el servidor
Para levantar el servidor de desarrollo de artisan, ejeutar:
```
$ php artisan serve --port=8080
or
$ php -S localhost:8080 -t public/
```

Ya puede ingresar al sistema mediante: [http://localhost:8080](http://localhost:8080)
