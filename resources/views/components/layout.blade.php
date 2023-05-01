<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script>
        const BASE_PATH = '{{ url("/") }}';
    </script>
    <title>Barangay MIS</title>

    @vite('resources/css/app.css')
</head>

<body class="font-inter">
    <x-navbar />

    <div class="relative ml-16 flex flex-col h-screen overflow-auto py-7 px-10 text-project-blue bg-[#F7F7F7] gap-5">
        {{ $slot }}
    </div>

</body>

</html>
