# Automatische Melding Cleanup

## Overzicht
Het systeem verwijdert automatisch open meldingen die ouder zijn dan 7 dagen.

## Hoe het werkt

### Automatische verwijdering (Scheduled Task)
- **Tijdstip**: Elke dag om 03:00 's nachts
- **Criteria**: Meldingen met status "open" die ouder zijn dan 7 dagen
- **Locatie**: Geconfigureerd in `routes/console.php`

### Laravel Scheduler starten

Om de automatische cleanup te laten werken, moet de Laravel scheduler draaien. Dit doe je door deze cron entry toe te voegen (op productie):

```bash
* * * * * cd /pad/naar/project && php artisan schedule:run >> /dev/null 2>&1
```

**Op Windows/XAMPP (voor development):**
Je kan de scheduler handmatig testen met:
```bash
php artisan schedule:run
```

Of maak een Windows Task Scheduler taak die elk uur dit commando uitvoert.

### Handmatige cleanup

Je kan de cleanup ook handmatig uitvoeren:

```bash
php artisan reports:cleanup
```

Dit verwijdert direct alle open meldingen ouder dan 7 dagen.

## Command Details

**Command**: `reports:cleanup`
**Bestand**: `app/Console/Commands/DeleteOldReports.php`
**Beschrijving**: Verwijdert open meldingen die ouder zijn dan 7 dagen

### Output voorbeelden

Wanneer er oude meldingen zijn:
```
✓ 5 oude open melding(en) verwijderd
```

Wanneer er geen oude meldingen zijn:
```
Geen oude open meldingen gevonden om te verwijderen
```

## Aanpassen van de cleanup periode

Om de periode aan te passen (bijv. 14 dagen in plaats van 7), pas de volgende regel aan in `app/Console/Commands/DeleteOldReports.php`:

```php
$oneWeekAgo = Carbon::now()->subWeek();  // 7 dagen
// Wijzig naar:
$twoWeeksAgo = Carbon::now()->subWeeks(2);  // 14 dagen
```

## Logs

Elke cleanup actie wordt gelogd in de Laravel logs (`storage/logs/laravel.log`).
