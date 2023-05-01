<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="inactive" label="Step Two"/>
            <x-progress progress="inactive" label="Step Three"/>
            <x-progress progress="inactive" label="Complete"/>

        </div>
    </div>

    <div class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-10">

        <p class="text-2xl font-bold">Personal Information</p>

        <form class="flex flex-col gap-5 h-full" method="POST" action="{{url('/residents/new/step-one')}}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
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
                        <label for="middle_name" class="flex gap-2 items-center"> Middle Name  <span class="text-xs text-project-blue/20">optional</span></label>
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
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="nickname" class="flex gap-2 items-center">Nickname <span class="text-xs text-project-blue/20">optional</span></label>
                        @error('nickname')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror    
                    </div>
                    <input class="form-input" type="text" name="nickname" id="nickname">
                </div>
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="sex">Sex</label>
                        @error('sex')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <select class="form-input" name="sex" id="sex">
                        <option value="1">Male</option>
                        <option value="2">Female</option>
                    </select>
                </div>
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="birth_date">Birth Date</label>
                        @error('birth_date')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="date" name="birth_date" id="birth_date">
                </div>
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="age">Age</label>
                        @error('age')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="age" id="age" readonly>
                </div>
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="place_of_birth">Place of Birth</label>
                        @error('place_of_birth')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="place_of_birth" id="place_of_birth">
                </div>
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="civil_status_id">Civil Status</label>
                        @error('civil_status_id')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <select class="form-input" name="civil_status_id" id="civil_status_id">
                        @foreach ($options['civilStatus'] as $civilStatus)
                            <option value="{{$civilStatus->id}}">{{ucfirst($civilStatus->name)}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="occupation_id">Occupation</label>
                        @error('occupation_id')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <select class="form-input" name="occupation_id" id="occupation_id">
                        @foreach ($options['occupation'] as $occupation)
                            <option value="{{$occupation->id}}">{{ucfirst($occupation->name)}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="religion_id">Religion</label>
                        @error('religion_id')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <select class="form-input" name="religion_id" id="religion_id">
                        @foreach ($options['religion'] as $religion)
                            <option value="{{$religion->id}}">{{ucfirst($religion->name)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 self-end mt-auto">
                <a href="" class="py-2 px-4 bg-table-even text-project-blue/40 rounded-md">Cancel</a>
                <button type="submit" class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md">Next</button>
            </div>

        </form>
    </div>
</x-layout>

@vite('resources/js/residents.js')