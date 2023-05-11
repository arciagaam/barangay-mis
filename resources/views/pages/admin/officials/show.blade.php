
<x-layout>
    
    <form href="{{url("/officials/$official->id/edit")}}" method="POST" class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-7">
        @csrf
        <div class="flex w-full items-center justify-between">
            <p class="font-bold text-xl">Barangay Official Information</p>
            @if (!$editing)
                <a href="{{url("/officials/$official->id/edit")}}" class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md">Edit</a>
            @else
                <p class="italic text-sm">Editing</p>
            @endif
        </div>

        <div class="flex flex-col gap-5">
            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="position_id" >Position</label>
                    @error('position_id')
                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>

                @if($editing)
                    <select class="form-input w-fit" name="position_id" id="position_id">
                        @foreach ($positions as $position)
                            <option value="{{$position->id}}" {{$official->position_id == $position->id ? 'selected' : ''}}>{{$position->name}}</option>
                        @endforeach
                    </select>
                @else
                    <input class="form-input" type="text" name="position_id" id="position_id" value="{{$official->first_name}}" {{$editing ? '' : 'disabled'}}>
                @endif
            </div>

            <div class="flex gap-5">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center w-fit gap-3">
                        <label for="term_start">Term Start</label>
                        @error('term_start')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input w-fit" type="date" name="term_start" id="term_start" value="{{$official->term_start}}" {{$editing ? '' : 'disabled'}}>
                </div>
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center w-fit gap-3">
                        <label for="term_end">Term End</label>
                        @error('term_end')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input w-fit" type="date" name="term_end" id="term_end" value="{{$official->term_end}}" {{$editing ? '' : 'disabled'}}>
                </div>
            </div>

        </div>

        <div class="flex flex-col gap-5">
            <p class="font-bold text-lg">Personal Information</p>

            <div class="grid grid-cols-3 gap-3">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="first_name" >First Name</label>
                        @error('first_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="first_name" id="first_name" value="{{$official->first_name}}" disabled>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="middle_name" > Middle Name</label>
                        @error('middle_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="middle_name" id="middle_name" value="{{$official->middle_name}}" disabled>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="last_name" > Last Name</label>
                        @error('last_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="last_name" id="last_name" value="{{$official->last_name}}" disabled>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="nickname" > Nickname</label>
                        @error('nickname')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="nickname" id="nickname" value="{{$official->nickname}}" disabled>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="sex" > Sex</label>
                        @error('sex')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    
                    @if ($editing)
                        <select class="form-input" name="sex" id="sex">
                            <option value="1" {{$official->sex == 1 ? 'selected' : ''}}>Male</option>
                            <option value="2" {{$official->sex == 2 ? 'selected' : ''}}>Female</option>
                        </select>
                    @else
                        <input class="form-input" type="text" name="sex" id="sex" value="{{$official->sex == 1 ? 'Male' : 'Female'}}" disabled>
                    @endif
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="birth_date" > Birth Date</label>
                        @error('birth_date')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="date" name="birth_date" id="birth_date" value="{{$official->birth_date}}" disabled>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="age" > Age</label>
                        @error('age')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="age" id="age" value="{{$official->age}}" disabled>
                </div>
            </div>
        </div>

        @if ($editing)
            <div class="flex justify-end gap-5 items-center mt-auto">
                <a class="py-2 px-4 bg-table-even text-project-blue/50 rounded-md w-fit" href="{{url("/officials/$official->id")}}">Cancel</a>
                <button class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md w-fit">Save</button>
            </div>
        @endif
    </form>
</x-layout>