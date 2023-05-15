<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="inactive" label="Complete"/>

        </div>
    </div>

    <form method="POST" action="{{url('/maintenance/users/new/step-one')}}" class="flex flex-col bg-white flex-1 rounded-md shadow-md py-5 px-5 gap-7">
        @csrf

        <div class="flex flex-wrap gap-3">
            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="first_name">First Name</label>
                    @error('first_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
                <input class="form-input" type="text" name="first_name" id="first_name">
            </div>
    
            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="middle_name">Middle Name</label>
                    @error('middle_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
                <input class="form-input" type="text" name="middle_name" id="middle_name">
            </div>
    
            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="last_name">Last Name</label>
                    @error('last_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
                <input class="form-input" type="text" name="last_name" id="last_name">
            </div>
            
        </div>

        <div class="form-input-container">
            <div class="flex flex-row justify-between items-center">
                <label for="username">Username</label>
                @error('username')
                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                @enderror
            </div>
            <input class="form-input w-fit" type="text" name="username" id="username">
        </div>

        <div class="form-input-container">
            <div class="flex flex-row justify-between items-center">
                <label for="password">Password</label>
                @error('password')
                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                @enderror
            </div>
            <input class="form-input w-fit" type="password" name="password" id="password">
        </div>

        <div class="form-input-container">
            <div class="flex flex-row justify-between items-center">
                <label for="role_id">Role</label>
                @error('role_id')
                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                @enderror
            </div>
            <select class="form-input w-fit" name="role_id" id="role_id">
                @foreach ($roles as $role)
                    <option value="{{$role->id}}">{{ucfirst($role->name)}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-input-container">
            <div class="flex flex-row justify-between items-center">
                <label for="security_question_id">Choose a Security Question</label>
                @error('security_question_id')
                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                @enderror
            </div>
            <select class="form-input w-fit" name="security_question_id" id="security_question_id">
                @foreach ($questions as $question)
                    <option value="{{$question->id}}">{{ucfirst($question->name)}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-input-container">
            <div class="flex flex-row justify-between items-center">
                <label for="security_question_answer">Answer</label>
                @error('security_question_answer')
                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                @enderror
            </div>
            <input class="form-input w-fit" type="text" name="security_question_answer" id="security_question_answer">
        </div>

        <div class="flex flex-row self-end mt-auto">
            <button class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md">Next</button>
        </div>
    </form>
</x-layout>