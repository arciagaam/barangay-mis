<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="flex items-center justify-center min-h-screen">
        <form method="POST" action="{{url('/authenticate')}}" class="flex flex-col min-w-[30%] gap-5 shadow-md py-10 px-6 rounded-md">

            <div class="flex flex-col items-center">
                <p>ICON</p>
                <p>Barangay 53-A Yakal</p>
            </div>

            <div class="flex flex-col gap-2">
                <div class="flex flex-col">
                    <label for="username">Username</label>
                    <input class="border border-gray-500 rounded-md outline-none px-2 py-1" type="text" name="username" id="username">
                </div>

                <div class="flex flex-col">
                    <label for="username">Password</label>
                    <input class="border border-gray-500 rounded-md outline-none px-2 py-1" type="password" name="username" id="username">
                </div>
            </div>

            <a class="self-end" href="{{url('/')}}">Forgot Password?</a>

            <button class="p-2 border border-black rounded-md">LOG IN</button>

        </form>
    </div>
</body>
</html>