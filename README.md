# Scraper - console app
## A szoftver futtatásához szükséges php: ^7.3 verzió
Húzzuk le a projektet az alábbi parancs segítségével
```bash
git clone https://github.com/AttilaSzendi/scraper.git
```
Lépjünk be a projekt könyvtárába
```bash
cd scraper
```
Következő lépésként húzzuk be a project függőségeit.
```bash
composer install
```
Adjuk ki a scraper:scrape artisan parancsot a futtatáshoz
```bash
php artisan scraper:scrape
```
A tesztek futtatásához az alábbi parancsot adjuk ki
```bash
php artisan test
```
