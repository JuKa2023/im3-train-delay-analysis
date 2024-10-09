<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Delays and Weather Disruptions</title>

    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Krub:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./src/style.css">

    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-900 text-gray-100">

<main class="container mx-auto px-4 py-8">
    <!-- Page Title (Full Width) -->
    <h1 class="text-4xl font-bold mb-8 w-full text-center">Zugverspätungen und Wetterstörungen: Eine Datengeschichte</h1>

    <!-- Main Content (Responsive Width) -->
    <div class="w-full lg:w-[55%] custom-width-75 mx-auto">
        <!-- Statistic Cards -->
        <article class="grid grid-cols-1 md:grid-cols-3 gap-4 my-12">
            <section class="border border-gray-100 p-6 rounded-lg relative">
                <p class="text-lg font-semibold mb-2">Durchschnittliche monatliche Verspätungen</p>
                <p class="text-3xl font-bold">66</p>
                <p class="text-sm text-gray-400">Züge pro Monat</p>
            </section>
            <section class="border border-gray-100 p-6 rounded-lg relative">
                <p class="text-lg font-semibold mb-2">Monat mit den meisten Verspätungen</p>
                <p class="text-3xl font-bold">April</p>
                <p class="text-sm text-gray-400">81 Verspätungen</p>
            </section>
            <section class="border border-gray-100 p-6 rounded-lg relative">
                <p class="text-lg font-semibold mb-2">Wetterkorrelation</p>
                <p class="text-3xl font-bold">73%</p>
                <p class="text-sm text-gray-400">wetterbedingte Verspätungen</p>
            </section>
        </article>

        <article>

        <!-- Monthly Line Chart Section -->
            <section class="mt-4">
                <h2 class="text-2xl font-semibold mb-4">Verständnis des Wettereinflusses auf die Pünktlichkeit der Züge</h2>
                <p class="mt-4 text-sm">Politiker, die regelmässig nach Bern pendeln, sind oft von den gleichen Faktoren betroffen wie Zugverspätungen, insbesondere wenn sie öffentliche Verkehrsmittel nutzen oder auf Strassenverkehr angewiesen sind. Wir haben untersucht, wie sich Wetterereignisse auf die Pünktlichkeit der Pendler auswirken und ob es Muster gibt, die mit den jeweiligen Fahrtrouten zusammenhängen.</p>
                <p class="mt-4 text-sm">In den letzten Monaten haben wir die durchschnittlichen Verspätungen von Politikern auf ihren Pendelrouten beobachtet. Insbesondere während starker Wetterereignisse gibt es auffällige Korrelationen zwischen Verspätungen und Wetterbedingungen.</p>
                <div class="mb-10 mt-4">
                    <div class="flex justify-center space-x-4 mb-4">
                        <button id="toggleButton"
                                class="bg-white text-gray-800 px-6 py-3 m-5 text-sm font-medium rounded-lg transition-transform transform hover:scale-[1.02] custom-border-3 border-transparent focus:outline-none"
                                onclick="toggleActiveState(this)">
                            Visp - Bern
                        </button>
                        <button id="toggleButton"
                                class="bg-white text-gray-800 px-6 py-3 m-5 text-sm font-medium rounded-lg transition-transform transform hover:scale-[1.02] custom-border-3 border-transparent focus:outline-none"
                                onclick="toggleActiveState(this)">
                            Genf - Bern
                        </button>
                        <button id="toggleButton"
                                class="bg-white text-gray-800 px-6 py-3 m-5 text-sm font-medium rounded-lg transition-transform transform hover:scale-[1.02] custom-border-3 border-transparent focus:outline-none"
                                onclick="toggleActiveState(this)">
                            Lugano - Bern
                        </button>
                        <button id="toggleButton"
                                class="bg-white text-gray-800 px-6 py-3 m-5 text-sm font-medium rounded-lg transition-transform transform hover:scale-[1.02] custom-border-3 border-transparent focus:outline-none"
                                onclick="toggleActiveState(this)">
                            Zürich - Bern
                        </button>
                    </div>

                    <div class="bg-gray-800 p-4 rounded-lg mb-2 transform transition-transform duration-300 hover:scale-[1.02]">
                        <canvas id="monthlyChart" class="w-full"></canvas>
                    </div>
                    <p class="text-15px">Dieses Diagramm zeigt die Beziehung zwischen Zugverspätungen und Wetterstörungen im Jahr 2023.</p>
                </div>
                <h4 class="text-xl font-semibold mb-4">Wichtige Erkenntnisse</h4>
                <p class="mt-4 text-sm">März und April verzeichneten die höchste Anzahl an Zugverspätungen, was mit einer Zunahme der Wetterstörungen zusammenfiel. Wetterbedingte Störungen erreichten im Februar ihren Höhepunkt, wahrscheinlich aufgrund der Winterbedingungen. Juni zeigte die niedrigste Anzahl sowohl an Verspätungen als auch an Wetterstörungen, was auf einen verbesserten Service in den Sommermonaten hindeutet.</p>
                <p class="mt-4 text-sm">Diese monatliche Analyse zeigt, dass es eine klare Korrelation zwischen Wetterstörungen und Zugverspätungen gibt. Monate mit hohen Wetterstörungen verzeichneten auch die höchsten Verspätungsraten, was darauf hindeutet, dass Wetterereignisse einen erheblichen Einfluss auf die Pünktlichkeit der Züge haben.</p>
            </section>

            <!-- April Bar Chart Section -->
            <section class="mt-4">
                <h3 class="text-2xl font-semibold mb-4">April: Ein Monat mit hohen Störungen</h3>
                <p class="mt-4 text-sm"> Der April ist besonders hervorzuheben, da in diesem Monat eine signifikant hohe Anzahl von Verspätungen auftrat, die direkt mit Wetterereignissen in Verbindung stehen. Vor allem an bestimmten Tagen gab es hohe Abweichungen von der geplanten Ankunftszeit in Bern.</p>
                <div class="mb-10 mt-4">
                    <div class="bg-gray-800 p-4 rounded-lg mb-2 transform transition-transform duration-300 hover:scale-[1.02]">
                        <canvas id="aprilChart" class="w-full h-80"></canvas>
                    </div>
                    <p class="text-15px">Dieses Balkendiagramm zeigt die täglichen Zugverspätungen und Wetterstörungen im April.</p>
                </div>
                <h4 class="text-xl font-semibold mb-4">Wichtige Erkenntnisse aus den April Daten</h4>
                <p class="mt-4 text-sm">Die höchste Anzahl von Verspätungen (95) trat am 8. April auf, was mit dem Tag der meisten Wetterstörungen (18) zusammenfiel.
                    Es gab mehrere Tage mit überdurchschnittlichen Verspätungen (mehr als 81), was darauf hindeutet, dass die hohe monatliche Gesamtzahl nicht auf ein einzelnes extremes Ereignis zurückzuführen war.
                    Wetterstörungen variierten von Tag zu Tag erheblich, von 4 bis 18 Vorfällen.
                    Die Daten zeigen eine klare Korrelation zwischen Tagen mit hohen Wetterstörungen und erhöhten Zugverspätungen.</p>
                <p class="mt-4 text-sm">Diese detaillierte Ansicht des Aprils zeigt, dass der Monat zwar insgesamt eine hohe Anzahl von Verspätungen und Wetterstörungen aufwies, diese jedoch nicht gleichmäßig verteilt waren. Stattdessen gab es bestimmte Tage mit besonders hohen Vorfällen, wahrscheinlich aufgrund schwerer Wetterereignisse. Diese Informationen können wertvoll sein für die Planung und Umsetzung gezielter Strategien zur Minderung von Verspätungen während Hochrisikozeiträumen.</p>
            </section>

            <!-- Scatter Chart Section -->
            <section class="mt-4">
                <h2 class="text-2xl font-semibold mb-4">Korrelation zwischen Wetter und Verspätungen</h2>
                <p class="mt-4 text-sm">Unsere Analyse zeigt eine starke Korrelation zwischen Wetterstörungen und Zugverspätungen. Im Durchschnitt beobachteten wir für jede wetterbedingte Störung einen Anstieg von 4-6 Zugverspätungen.</p>
                <div class="mb-10 mt-4">
                    <div class="bg-gray-800 p-4 rounded-lg mb-2 transform transition-transform duration-300 hover:scale-[1.02]">
                        <canvas id="scatterChart" class="w-full h-full"></canvas>
                    </div>
                    <p class="text-15px">Dieses Streudiagramm veranschaulicht die Korrelation zwischen Wetterstörungen und Zugverspätungen.</p>
                </div>
            </section>
        </article>

        <!-- Conclusion Section -->
        <article class="mb-10">
            <h2 class="text-2xl font-semibold mb-4">Fazit</h2>
            <p class="mt-4 text-sm">This data story highlights the significant impact of weather on train punctuality. By understanding these patterns,
                rail services can better prepare for and mitigate the effects of weather-related disruptions, ultimately improving
                the reliability of train schedules for passengers.</p>
        </article>
    </div>
</main>

<script src="./src/scripts.js"></script>

</body>

</html>