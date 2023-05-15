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
        <form method="POST" action="{{url('/forgot-password/security-check')}}" class="flex flex-col min-w-[30%] gap-5 shadow-md py-10 px-6 rounded-md bg-white">
            @csrf
            <p class="text-lg font-bold">Forgot Password</p>
            <div class="flex flex-col gap-2">
                <div class="form-input-container">
                    <label for="security_question">Security Question</label>
                    <input class="form-input" type="text" name="security_question" id="security_question" value="{{$securityQuestion}}" disabled>           
                </div>

                <div class="form-input-container">
                    <label for="security_answer">Answer</label>
                    <input class="form-input" type="text" name="security_answer" id="security_answer" value="{{old('security_answer')}}">
                    @error('security_answer')
                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                    @if (session()->has('error'))
                        <p class="text-xs text-red-500 italic">{{session()->get('error')}}</p>
                    @endif
                </div>
            </div>
            <button class="py-2 w-full self-center bg-project-yellow font-bold rounded-md">Next</button>

        </form>
    </div>
</body>
</html>