<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title>Forgot Password</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="flex items-center justify-center min-h-screen bg-project-blue text-project-blue">
        <div class="flex flex-col min-w-[30%] gap-5 shadow-md py-10 px-6 rounded-md bg-white">
            <div class="flex flex-col items-center gap-10">
                <div class="flex flex-col items-center">
                    <i id="complete-check" class='bx bx-lg bx-check-circle'></i>
                    <p class="text-2xl">Password Updated</p>
                </div>
            </div>
            <a href="{{url('/')}}" class="flex py-2 w-full justify-center items-center bg-project-yellow rounded-md font-bold">Login</a>
        </div>
    </div>
</body>
</html>