# CPBC Enquiry System

The enquiry-handling system for the Community Pro Bono Centre. Members of the
public submit a legal enquiry through the intake form; volunteers sign in,
work through the list of matters, and add notes.

There is no database. Everything lives in a Google Sheet. This code reads and
writes that sheet through a small Google Apps Script "API" a volunteer set up
in 2022. For running it on your own machine there is a CSV copy of the sheet
(`cpbc_sheet.csv`) that behaves the same way, so you do not need a Google
account to try it.

## Running it locally

With PHP installed:

```
php -S localhost:8000
```

Then open http://localhost:8000/ .

Or with Docker:

```
docker compose up -d --build
```

Then open http://localhost:8080/ .

## Signing in

One shared account, same as it has always been:

```
username: volunteer
password: clinic2019
```

The password is also in the volunteer handbook. It has not changed since the
system was built.

## Pointing it at the real sheet

Open `config.php` and set `USE_LIVE_SHEET` to `true`, then paste your Apps
Script deployment URL into `APPS_SCRIPT_URL`. The script itself is in
`apps-script/Code.gs` — deploy it as a web app against the sheet and copy the
`/exec` URL it gives you. The sheet's tab must be named `Enquiries`.

## What is in here

| File | What it does |
|------|--------------|
| `intake.php` | Public enquiry form |
| `login.php` / `logout.php` | Sign in / out |
| `matters.php` | List of matters, with search |
| `matter.php` | One matter: details and notes |
| `reset.php` | Password reset (does not send yet) |
| `reports.php` | Monthly report (not built - done by hand for now) |
| `limitation.php` | Limitation-period check |
| `sheet.php` | Reads and writes the sheet / CSV |
| `config.php` | Settings and the sheet connection |
| `apps-script/Code.gs` | The Apps Script that fronts the live sheet |

## Notes for whoever picks this up

Sorry about the state of some of this. A few things that never got finished:

- Reports page — the Centre Manager still copies the sheet out and formats the
  monthly numbers by hand.
- Password reset does not actually email anything.
- We started moving off the shared login to proper accounts per volunteer.
  It is not wired up — see `_accounts_migration_DONOTDELETE.php`. Please do
  not delete that file, someone may still finish it.
- The limitation-period check is a placeholder. It does not know the real
  rules yet.

If you have questions, the original contractor is no longer contactable and
Priya has moved on. Good luck.
