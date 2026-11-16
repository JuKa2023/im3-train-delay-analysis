# Train delays × weather: findings & decisions

As of 16 Nov 2026 · Working notes for v2 of this project

## Starting point: why v1's story didn't hold up

The v1 story ([im3-train-delay-analysis](https://github.com/JuKa2023/im3-train-delay-analysis)) concluded "no significant correlation", but its data couldn't test that.

- **Forecasts, not actual delays.** A cron job polled the live departure board for Bern (transport.opendata.ch) every 2.5 minutes and skipped trains that had already left. The stored delay was the forecast just before departure, often 0.
- **No history, and collection stopped.** About 157k rows from Oct 2024 to Jan 2025, then only a trickle until April 2025.
- **Weak weather measures.** The disruption score used made-up weights (gusts 0.5, snow 0.7, rain 0.1). The "weather correlation %" was a share of delays on bad-weather days, not a correlation.
- **One point only.** Weather and trains came from Bern alone.
- **Stack:** plain PHP scripts, MariaDB, Chart.js, Tailwind via CDN.

## Data sources

opentransportdata.swiss, the official platform, replaces transport.opendata.ch. Two of its feeds fit, plus Open-Meteo for weather.

| Source | What it gives | Access | Fits? |
| --- | --- | --- | --- |
| [Ist-Daten](https://opentransportdata.swiss/en/cookbook/historic-and-statistics-cookbook/actual-data/) (daily CSV) | Every stop in Switzerland: scheduled and actual times, cancellation flag, status (measured or forecast) | No key; [archive](https://archive.opentransportdata.swiss/actual_data_archive.htm) in monthly ZIPs back to 2016 | Yes, the core |
| [GTFS-RT Service Alerts](https://opentransportdata.swiss/en/cookbook/event-cookbook/gtfs-sa/) | Live disruption messages with a cause: unknown, other, technical problem, accident, weather, maintenance | Free API key; about 2–5 requests per minute; covers roughly the next 3 hours; no archive found | Yes, the live interval job |
| [SIRI-SX](https://opentransportdata.swiss/en/cookbook/event-cookbook/siri-sx/) | Same disruption information in XML, with a reason element | API key; 2 per minute, 3,000 per day | Fallback if the alerts are thin |
| GTFS-RT Trip Updates / SIRI-ET | Live delays for the whole country | API key | No: Ist-Daten covers it the next day |
| OJP, train formation, occupancy | Journey planning, train composition, how full trains are | API key | No: off-topic |
| Open-Meteo historical API | Hourly weather for any coordinates, decades back | No key | Yes, weather |
| transport.opendata.ch (v1) | Upcoming departures with forecast delays | No key | No: forecasts, not actual times |

Caveats from the platform:

- Ist-Daten is "of only limited quality". In many cases the last forecast is used as the actual time, so the status flag matters.
- Version 2 of the format started 13 July 2025. A backfill across that date has to handle both versions.
- Going over the [rate limits](https://opentransportdata.swiss/en/limits-and-costs/) costs money, so the collector needs a hard cap in code.

## Data check results

Measured times hold up: 98–100% of train departures were measured at every station on a weekday. Ticino had gaps on the Sunday.

Two full days of Ist-Daten, trains only: Wednesday 11 Nov 2026 and Sunday 8 Nov 2026. "Measured" means the departure status was REAL rather than a forecast.

| Station | Departures, Wed | Cancelled, Wed | Measured, Wed | Measured, Sun | 3+ min late, Wed |
| --- | --- | --- | --- | --- | --- |
| Zürich HB | 1,623 | 43 | 100% | 100% | 11.2% |
| Bern | 1,125 | 43 | 99% | 100% | 2.8% |
| Visp | 287 | 23 | 98% | 100% | 10.4% |
| Zermatt | 85 | 0 | 100% | 100% | 1.2% |
| Bellinzona | 262 | 1 | 100% | 81% | 13.0% |
| Lugano | 264 | 2 | 100% | 88% | 6.9% |

- **Mountain lines are usable.** The Matterhorn Gotthard Bahn reports measured times almost completely.
- **Ticino has gap days.** The import must keep forecast-only rows marked as unmeasured.
- **Zermatt is too small alone** at 85 departures a day. Visp is the better mountain station, possibly with Zermatt merged in.
- **Stations clearly differ.** Zürich at 11% vs Bern at 3% late on the same day. One day only, nothing about weather yet.
- **Cancellations are included**, so they can be a measure of their own.
- **Alert causes: not checked yet.** The alerts feed returns "unauthorised" without a key, and the data archive has no alerts archive.

## Weather test: January and July 2026

Weather matters in short, sharp moments, not day to day. Where a train leaves from matters far more. Heavy rain hours in July had 2.4–2.9× the usual late share in Zürich and Bern, but daily correlations stayed weak everywhere.

What was tested: every train departure from Zürich HB, Bern, Visp, Bellinzona and Zermatt in January and July 2026 (about 207,000), joined with hourly [Open-Meteo](https://open-meteo.com/en/docs/historical-weather-api) weather at each station. "Late" means a measured departure 3+ minutes after schedule.

| Station | Departures per month (Jan / Jul) | Late, Jan | Late, Jul | Cancelled, Jan | Cancelled, Jul |
| --- | --- | --- | --- | --- | --- |
| Zürich HB | 54,193 / 49,303 | 6.2% | 4.4% | 1.9% | 1.6% |
| Bern | 32,297 / 32,638 | 2.9% | 3.0% | 1.4% | 1.3% |
| Visp | 8,246 / 8,300 | 8.6% | 11.3% | 1.7% | 2.0% |
| Bellinzona | 8,606 / 8,326 | 5.7% | 11.2% | 6.7% | 5.5% |
| Zermatt | 2,750 / 2,733 | 3.6% | 7.1% | 0.3% | 1.0% |

Late share in each weather condition, relative to normal (1.0 = normal; conditions with 10+ hours only; – = too few hours):

| Condition | Zürich HB | Bern | Visp | Bellinzona |
| --- | --- | --- | --- | --- |
| Heavy rain, Jul (3+ mm in 3 h) | 2.44 (16 h) | 2.88 (16 h) | 1.54 (14 h) | – |
| Hard frost, Jan (−5 °C or colder) | 1.82 (43 h) | 1.72 (87 h) | 1.08 (94 h) | – |
| Rain, Jan (1+ mm in 3 h) | 1.67 (18 h) | 1.16 (30 h) | 1.52 (61 h) | 1.17 (43 h) |
| Strong gusts, Jul (50+ km/h) | – | – | 0.76 (96 h) | 1.46 (18 h) |
| Strong gusts, Jan (50+ km/h) | 1.08 (19 h) | 0.64 (30 h) | 1.32 (41 h) | – |
| Snowing, Jan (0.5+ cm in 3 h) | 1.15 (25 h) | 0.92 (44 h) | – | – |
| Snow on ground, Jan (5+ cm) | 0.71 (32 h) | 1.13 (38 h) | 1.00 (660 h) | 0.89 (123 h) |
| Heat, Jul (30 °C or more) | 0.84 (86 h) | 0.90 (87 h) | 1.04 (129 h) | 1.00 (174 h) |

**What the numbers say**

- **The station is the biggest factor.** Bern stays at about 3% late in both months. Visp and Bellinzona reach about 11% in July, with no clear weather cause.
- **Heavy rain is the clearest weather signal.** At 3+ mm in 3 hours in July, the late share rose to 2.4× normal in Zürich and 2.9× in Bern. That's only 16 hours per station.
- **Hard frost may matter.** At −5 °C or colder, Zürich and Bern ran 1.7–1.8× normal. Much of that is one cold day, 6 Jan, the worst day of the month in Zürich (19% late), Bern and Visp, with no snow or rain.
- **Snowfall and heat show almost nothing** at the station points. January 2026 had little snow at low altitude (25–44 snowy hours).
- **Daily averages wash the effect out.** Day-level rank correlations were mostly between −0.3 and +0.3. The strongest was Visp, late share vs. daily snowfall, at +0.40.
- **Bellinzona's cancellations aren't weather.** 6.7% of departures were cancelled in January, 7.2% even in dry, calm hours. Planned works or trains from Italy are likely causes, but this data can't tell.
- **Many of the worst days aren't weather days.** Visp's worst day (30 Jul, 29% late) was dry. Bellinzona's worst cancellation day (15 Jan, 15.5%) had light rain.

**How "normal" is defined:** each station's average late share for the same hour of day and service type (long-distance or regional) in that month. This separates weather from rush-hour effects.

**Limits of this test**

- Two months only. Extreme-weather hours are few, so single incidents can swing a result.
- Open-Meteo history is reanalysis data, not station measurements. It recorded no thunderstorm hours at all, so storms couldn't be tested. MeteoSwiss station data would be worth checking.
- Weather is taken at the station. Mountain and Gotthard delays may start somewhere else along the line.
- A train that arrives late, for example from Italy, also leaves late. That counts here as a delay at that station.

## What we defined

The piece is about the data: visualisation, playing with it, and a data story. Weather and train delays interlocking is the core.

**Decided**

- **Focus:** data visualisation, an interactive playground, and a data story, not one without the others.
- **Core idea:** two data sources interlocking, with the weather–delay relationship made visible.
- **No drawn characters.** Illustration only where it supports the data, never as the main element.
- **Free to move on from the school project.** v1 is the base idea, not a constraint.
- **Keep regular-interval loading** as a base idea of the project.
- **More than Bern.** Several regions, so the piece matters to people outside Bern too.
- **Backend:** more structured than raw PHP. PocketBase or similar is fine, but don't overcomplicate it.
- **Light and dark design.** Both themes are designed, charts included; v1 was dark only.

**Proposed, not yet confirmed**

- **Four regions:** Zürich HB (big hub), Bern (the original), Visp (mountains, rather than Zermatt alone), Bellinzona (Ticino, rather than Lugano).
- **Sources:** drop transport.opendata.ch. Load Ist-Daten and Open-Meteo history nightly, backfill at least 12 months once, and poll service alerts every few minutes.
- **Interval loading gets a job no backfill can do:** collecting disruption causes, which aren't archived anywhere.
- **Stack:** a small Python + DuckDB import script for the large daily files, PocketBase for the stored hourly data, and Vue 3 + TypeScript with D3 or Observable Plot instead of Chart.js.

## Trade-offs still open

| Question | For | Against | Current lean |
| --- | --- | --- | --- |
| Story, playground or both? | Story gives a takeaway; playground shows craft and invites clicking | Story alone is static; playground alone reads as a dashboard | Both: a short guided story that hands over the controls ("martini glass") |
| Live polling as the main delay source? | Shows a real pipeline; keeps the piece live | Thin for rare weather; dies when the cron stops (v1 did, Feb 2025); measures forecasts | No: interval loading stays, but from Ist-Daten and alerts |
| Include delay causes? | The only data that needs live collection; operator says "weather" vs measured weather is a strong angle | Cause field may be mostly "unknown"; widens focus away from weather | In, if the alerts check shows real causes |
| "Check your own train" | Fastest way to make anyone play; concrete | Portfolio visitors are recruiters, not commuters; needs per-train records | A filter in the playground, not a separate feature |
| Data story if weather barely matters? | A clear null result is honest | Hard to make compelling; can read as a failed project | Regional contrast as the safety net; decide after seeing data |
| How many sources? | Each adds an angle (e.g. board forecast vs actual) | Each adds import, storage and failure work | Weather + actual times + alerts; cut the forecast comparison first |
| Core question | "Does weather make trains late?" is the original | Narrow, fails flat with a weak effect | "Does weather hit trains differently across Switzerland?" |

## Open questions and next checks

- [ ] Register a free API key at [api-manager.opentransportdata.swiss](https://api-manager.opentransportdata.swiss/) and save it in a file, so the alert causes can be sampled
- [ ] Sample alerts for about a day: how often is the cause something other than unknown or other?
- [x] Pull Ist-Daten for the four stations plus Open-Meteo history (done for Jan and Jul 2026)
- [ ] Check a full year, so rare weather (storms, heavy snow) gets enough hours
- [ ] Try MeteoSwiss station data, since Open-Meteo history recorded no thunderstorms
- [ ] Find out what happened on 6 Jan 2026 (worst day in Zürich, Bern and Visp, cold but dry)
- [ ] Find out why Bellinzona cancels 6–7% of departures, even in calm weather
- [ ] Decide the core question once the numbers are in
- [ ] Choose the signature visual once the data's shape is known (it has to work by the hour: daily averages hide the effect)
- [ ] Pick the weather points for mountain lines (station only, or also along the route)
- [ ] What is the interval loading for: showing a pipeline, keeping the piece live, or getting data nobody else has?

## Sources

- [opentransportdata.swiss](https://opentransportdata.swiss/en/)
- [Ist-Daten v2 dataset](https://data.opentransportdata.swiss/en/dataset/ist-daten-v2)
- [Ist-Daten archive](https://archive.opentransportdata.swiss/actual_data_archive.htm)
- [Limits and costs](https://opentransportdata.swiss/en/limits-and-costs/)
- [SIRI-SX event information (opendata.swiss)](https://opendata.swiss/en/dataset/ereignisinformation-ov-schweiz-siri-sx-vdv736)
