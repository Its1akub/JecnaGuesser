<img src="assets/images/jg_banner_logo_dark.svg" alt="Logo"/>

# JEČNÁ GUESSER
Guess where you are in SPŠE Ječná.

## Database Setup

1. V souboru php.ini ktera ve sloužce u php.exe souboru musí byt povoleno `extension=pdo_mysql`

2. Musíš mít mysql stahnuté a vytvorenou databazi `jecna_guesser`


3. Jestli to spouštíš lokálně a ne na serveru tak si vytvoř soubor `.env.local` a do něho dej tohle:

```
APP_ENV=dev 
APP_SECRET=3e703ab691f61a73b560ee494a56bbff
DATABASE_URL="mysql://root:heslo@127.0.0.1:3306/jecna_guesser"
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
MAILER_DSN=null://null
```

Heslo přepiš na své

Jestli na serveru tak to dej do `.env`

4. Zadej tyto přikazy do konzole

```
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
