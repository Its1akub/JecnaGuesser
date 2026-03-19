<img src="assets/images/jg_banner_logo_dark.svg" alt="Logo"/>
# Ječná Guesser

> **Uhádni, kde ve škole jsi** — webová hra inspirovaná GeoGuessr, zasazená do budovy SPŠE Ječná.

---

## O projektu

**Ječná Guesser** je školní projekt vytvořený studenty SPŠE Ječná (Praha). Hráčům je zobrazena fotografie pořízená někde v budově školy a jejich úkolem je označit přesné místo na půdorysu patra. Body se udělují na základě přesnosti odhadu, správného výběru patra a času.

Hra podporuje tři obtížnosti, globální žebříček a sledování návštěvnosti pomocí UTM parametrů.

---

## Funkce

- Hádání polohy z fotografie na interaktivním půdorysu
- Navigace mezi patry (patra 0–4)
- Tři úrovně obtížnosti: Snadná, Střední, Těžká
- Globální žebříček s filtrováním podle obtížnosti
- Živá časomíra v průběhu herního sezení
- Sledování UTM kampaní pro analytiku
- Plně responzivní design přizpůsobený mobilům

---

## Dokumentace

Podrobná dokumentace je ve složce [`docs/`](docs/):

---

## Rychlý start

### Database Setup

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


### Start

```bash
git clone https://github.com/your-org/jecna-guesser.git
cd jecna-guesser
composer install
# nastavte .env.local s DATABASE_URL
php bin/console doctrine:migrations:migrate
php bin/console importmap:install
symfony server:start
```

---

## Licence

Projekt je licencován pod **MIT licencí**.

```
Copyright (c) 2026 Samuel Majer, Neil Malhotra, Jakub Novák, Jakub Špernoga, Adam Švec
```
