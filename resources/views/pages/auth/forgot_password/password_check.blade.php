<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="flex items-center justify-center min-h-screen bg-project-blue text-project-blue">
        <form method="POST" action="{{url('/forgot-password/change-password')}}" class="flex flex-col min-w-[30%] gap-5 shadow-md py-10 px-6 rounded-md bg-white">
            @csrf
            <p class="text-lg font-bold">Forgot Password</p>
            <div class="flex flex-col gap-2">
                <div class="form-input-container">
                    <label for="password">Enter new password</label>
                    <input class="form-input" type="password" name="password" id="password">

                    @error('password')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>

                <div class="form-input-container">
                    <label for="confirm_password">Confirm Password</label>
                    <input class="form-input" type="password" name="confirm_password" id="confirm_password">

                    @error('confirm_password')
                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
            </div>
            <button class="py-2 w-full self-center bg-project-yellow font-bold rounded-md">Change Password</button>

        </form>
    </div>
</body>
</html>