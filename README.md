# im3-train-delay-analysis

## Kurzbeschreibung des Projekts
Dieses Projekt untersucht die Korrelation zwischen Wetterbedingungen und Zugverspätungen auf bestimmten Strecken in der Schweiz. Die Route dreier Ständeräte – Lisa Mazzone (Genf-Bern), Ruedi Noser (Zürich-Bern) und Marianne Maret (Visp-Bern) – dient als Grundlage. Mithilfe von Wetterdaten (Meteo API) und Zugverspätungsinformationen (SBB API) analysieren wir, ob und wie stark das Wetter (z. B. Regen, Schnee) die Verspätungen beeinflusst. Nutzer können die Route eines Politikers auswählen, aktuelle sowie prognostizierte Zugverspätungen anzeigen lassen und eine Bilanz über häufige Verspätungen ziehen.

## Autoren
- [@JuKa2023](https://github.com/JuKa2023)
- [@SophiaIseli](https://github.com/SophiaIseli)

## Features
- Live Wetterdaten: Nutzung einer Meteo API, um aktuelle Wetterinformationen zu erhalten (Regen, Schnee, Temperatur, etc.).
- Zugverspätungs-Analyse: Mithilfe der SBB API werden die aktuellen Verspätungen der Züge auf den ausgewählten Strecken abgerufen.
- Interaktive Visualisierung: Nutzer können zwischen drei verschiedenen Strecken wählen (Genf-Bern, Zürich-Bern, Visp-Bern) und sich die entsprechenden Wetter- und Verspätungsdaten anzeigen lassen.
- Prognosen: Basierend auf historischen Daten und aktuellen Wetterbedingungen werden Vorhersagen über mögliche Zugverspätungen gemacht.
- Bilanz und Fazit: Am Ende werden Trends aufgezeigt, wann und unter welchen Bedingungen Verspätungen besonders häufig auftreten.

## Verwendete Technologien und API
- Backhend: PHP
- Datenbank: Maria DB
- Frontend: HTML, Tailwind, JavaScript,
- Charts: Chart.Js
- API

## Installation und Einrichtung



## Datenbakstruktur

- Das Projekt verwendet eine simple Datenbankstruktur mit zwei Tabellen, eine Tabelle für das Sammeln des Wetters und eine für das Sammeln jeglicher Züge, die in Bern am Anschlagbrett angezeigt werden. Diese Datenbanken sollen in Regelmässigen abständen befüllt werden durch die API, hierfür werden die Wetter Daten ale 5 Minuten aus der API herausgezogen und alle 10 Minuten aus der Zug API.

## Learnings
- APIs integrieren und verknüpfen: Wir haben gelernt, wie man APIs untersucht und welche Tücken sie mitbringen können, wie man verschiedene APIs (Wetter- und Zugverspätungsdaten) nahtlos in eine Webanwendung integriert und die Daten sinnvoll kombiniert, wann es Sinn macht Daten vorab in eine Datenbank zu speichern.
- Datenanalyse und Visualisierung: Einblicke in die Korrelation von Wetterbedingungen und Verspätungen auf den Strecken wurden durch statistische Auswertungen und die Visualisierung der Ergebnisse gewonnen.
- Benutzerzentrierte Gestaltung: Wir haben den Fokus darauf gelegt, die Anwendung für den Benutzer einfach und interaktiv zu gestalten.

## Schwierigkeiten
- Datenzusammenführung: Es war eine Herausforderung, die unterschiedlichen Datenquellen (Wetter und Zugverspätungen) synchron zu verarbeiten, da sie in verschiedenen Formaten und mit unterschiedlichen Aktualisierungszyklen vorliegen.
- Prognose-Genauigkeit: Das Erstellen genauer Vorhersagen basierend auf den Wetterdaten und der Historie der Zugverspätungen erwies sich als schwieriger als erwartet.
- Technische Komplexität: Die Echtzeitverarbeitung von Wetter- und Zugdaten stellte besondere Anforderungen an die Architektur und die Performance der Anwendung.

## Benutzte Ressourcen
Zur Vorbereitung auf den Kurs schauten wir verschiedene Tutorials auf [YouTube](https://www.youtube.com/). Auf beiden Seiten haben wir Tutorials spezifisch zur Einbindung von APIs sowie zur schlauen Entwicklung und Integration von Animationen gefunden. Dies ermöglichte uns, sofort in das Projekt einzusteigen, und diente uns ebenfalls als Quelle bei Unklarheiten.

Während der Entwicklung stiessen wir auf technische Herausforderungen. In solchen Fällen griffen wir auf die Notizen aus dem Unterricht zurück. Wenn uns das Kursmaterial nicht weiterhalf, griffen wir auf [ChatGPT](https://chat.openai.com/c/0c86d02e-cf73-4878-8671-4585188888fa) und [W3Schools](https://www.w3schools.com/php/default.asp) zurück, um Lösungen für Codeprobleme zu finden und uns bei Unklarheiten in der Programmierung zu unterstützen. Diese Vorgehensweise trug wesentlich zur Effizienz und Qualität des Entwicklungsprozesses bei. Natürlich gab es auch immer wieder Punkte, wo wir lieber auf menschliche Hilfe zurückgreifen wollten. In diesen Fällen erhielten wir Unterstützung durch unsere Dozenten oder Freunde, die in der Programmierwelt eingebettet sind.

Für Design-Inspirationen haben wir häufig die Seite CodePen [CodePen](https://codepen.io/) genutzt. Diese Plattform erfordert jedoch eine gewisse Menge an Fachjargon und Vorwissen, um die gewünschten Ergebnisse zu finden. Es ist wichtig, klar anzugeben, mit welchen Technologien man arbeitet und ob man bestimmte Frameworks verwendet oder nicht, um relevante und nützliche Beispiele zu finden.

## Erweiterungsmöglichkeiten
- Mehr Strecken und Personen: Weitere Bahnhöfe könnten hinzugefügt werden, um ein breiteres Bild zu erhalten.
- Erweiterte Vorhersage-Modelle: Der Einsatz von maschinellem Lernen könnte die Vorhersagegenauigkeit für Zugverspätungen basierend auf Wetterdaten erheblich verbessern.
- Integrierte Push-Benachrichtigungen: Nutzer könnten Push-Benachrichtigungen erhalten, wenn auf ihrer ausgewählten Strecke Verspätungen zu erwarten sind.
- Erweiterung auf internationale Daten: Die Integration von internationalen Wetter- und Zugdaten könnte die Analyse auf andere Länder und Bahnnetze ausweiten.


## Kontakt
Falls Sie Fragen oder Anregungen haben, können Sie uns gerne über GitHub kontaktieren:
- Repository (API, CSS and Responsive Design): [@JuKa2023](https://github.com/JuKa2023)
- Design: [@SophiaIseli](https://github.com/SophiaIseli)
