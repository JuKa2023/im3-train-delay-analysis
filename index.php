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
        <article class="grid grid-cols-1 md:grid-cols-3 gap-8 my-12">
            <section class="border border-gray-100 p-6 rounded-lg relative">
                <p class="text-lg font-semibold mb-2">Durchschnittliche wöchentliche Verspätungen</p>
                <p class="text-3xl font-bold">66</p>
                <p class="text-sm text-gray-400">Züge pro Woche</p>
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

                <h3 class="text-xl font-semibold mb-4">Zugverspätungen und Wetterstörungen von den Zügen ab Bern</h3>
                <div class="mb-10 mt-4">

                    <div class="bg-gray-800 p-4 rounded-lg mb-2 transform transition-transform duration-300 hover:scale-[1.02]">
                        <canvas id="monthlyChart" class="w-full"></canvas>
                    </div>
                    <p class="text-15px">Dieses Diagramm zeigt die Beziehung zwischen Zugverspätungen und Wetterstörungen im Jahr 2023.</p>
                </div>
                <h4 class="text-xl font-semibold mb-4">Wichtige Erkenntnisse</h4>
                <p class="mt-4 text-sm">März und April verzeichneten die höchste Anzahl an Zugverspätungen, was mit einer Zunahme der Wetterstörungen zusammenfiel. Wetterbedingte Störungen erreichten im Februar ihren Höhepunkt, wahrscheinlich aufgrund der Winterbedingungen. Juni zeigte die niedrigste Anzahl sowohl an Verspätungen als auch an Wetterstörungen, was auf einen verbesserten Service in den Sommermonaten hindeutet.</p>
                <p class="mt-4 text-sm">Diese monatliche Analyse zeigt, dass es eine klare Korrelation zwischen Wetterstörungen und Zugverspätungen gibt. Monate mit hohen Wetterstörungen verzeichneten auch die höchsten Verspätungsraten, was darauf hindeutet, dass Wetterereignisse einen erheblichen Einfluss auf die Pünktlichkeit der Züge haben.</p>
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