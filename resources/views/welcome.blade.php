<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Infinity Settlers</title>
        <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="">
        <div id="app" class="">
            <board-game></board-game>
        </div>
        <!--
        <div class="flex justify-between p-12 bg-black">
            <div class="flex-1 flex text-xs">
                <div class="flex-1 m-2 pb-1 border-b-4 border-yellow-500 text-white font-bold">13221 goldcoins</div>
                <div class="flex-1 m-2 pb-1 border-b-4 border-green-700 text-white font-bold">2132 lumber</div>
                <div class="flex-1 m-2 pb-1 border-b-4 border-gray-300 text-white font-bold">312 stone</div>
                <div class="flex-1 m-2 pb-1 border-b-4 border-red-500 text-white font-bold">42000 food</div>
            </div>
            <div class="flex-1 bg-black text-white">news feed...</div>
        </div>
        -->
        <script src="/js/app.js"></script>
    </body>
</html>
