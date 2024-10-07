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
    <div class="w-full lg:w-[55%] mx-auto">
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

        <!-- Monthly Line Chart Section -->
        <section class="mt-4">
            <h2 class="text-2xl font-semibold mb-4">Verständnis des Wettereinflusses auf die Pünktlichkeit der Züge</h2>
            <div class="mb-10 mt-4">
                <div class="bg-gray-800 p-4 rounded-lg mb-2 transform transition-transform duration-300 hover:scale-[1.02]">
                    <canvas id="monthlyChart" class="w-full"></canvas>
                </div>
                <p class="text-15px">Dieses Diagramm zeigt die Beziehung zwischen Zugverspätungen und Wetterstörungen im Jahr 2023.</p>
            </div>
        </section>

        <!-- April Bar Chart Section -->
        <section class="mt-4">
            <h3 class="text-2xl font-semibold mb-4">April: Ein Monat mit hohen Störungen</h3>
            <div class="mb-10 mt-4">
                <div class="bg-gray-800 p-4 rounded-lg mb-2 transform transition-transform duration-300 hover:scale-[1.02]">
                    <canvas id="aprilChart" class="w-full h-80"></canvas>
                </div>
                <p class="text-15px">Dieses Balkendiagramm zeigt die täglichen Zugverspätungen und Wetterstörungen im April.</p>
            </div>
        </section>

        <!-- Scatter Chart Section -->
        <section class="mt-4">
            <h2 class="text-2xl font-semibold mb-4">Korrelation zwischen Wetter und Verspätungen</h2>
            <div class="mb-10 mt-4">
                <div class="bg-gray-800 p-4 rounded-lg mb-2 transform transition-transform duration-300 hover:scale-[1.02]">
                    <canvas id="scatterChart" class="w-full h-full"></canvas>
                </div>
                <p class="text-15px">Dieses Streudiagramm veranschaulicht die Korrelation zwischen Wetterstörungen und Zugverspätungen.</p>
            </div>
        </section>

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