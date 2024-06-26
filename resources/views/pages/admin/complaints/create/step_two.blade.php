<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="active" label="Step Two"/>
            <x-progress progress="inactive" label="Step Three"/>
            <x-progress progress="inactive" label="Complete"/>
        </div>
    </div>
    

    <div class="flex flex-col bg-white flex-1 rounded-md shadow-md py-5 px-5 gap-7">
        <div class="flex gap-3">
            <div class="flex gap-2 w-1/2 items-center">
                <label class="text-sm" for="search">Search</label>
                <div class="relative w-full">
                    <div class="flex items-center border w-full border-table-even focus-within:border-project-blue rounded-md overflow-hidden gap-2 px-1 bg-white transition-all duration-300 ease-in-out">
                        <input id="resident_search" class="w-full outline-none px-1 text-sm py-1" type="text" name="search" id="search">
                        <button class="w-fit h-fit aspect-square flex items-center justify-center"><i class='bx bx-search'></i></button>
                    </div>
                    <div id="search_names_container" class="absolute top-[calc(100% + 2em)] flex flex-col gap-1 bg-white rounded-md shadow-md w-full p-2 hidden">
                    </div>
                </div>
            </div>

            <button type="button" class="py-2 px-4 bg-project-yellow text-project-blue rounded-md text-sm">Search</button>

            <p class="text-project-blue/50 ml-auto text-xs italic">Fields with * are required.</p>
        </div>

        <form method="POST" action="{{url('/complaints/new/step-two')}}" class="flex flex-col h-full gap-5">
            <div class="flex flex-col gap-5">
                <p class="font-bold text-lg">Defendant Information</p>
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="first_name" class="flex gap-2 items-center">First Name  <span class="text-xs text-red-500">*</span></label>
                            @error('first_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="first_name" id="first_name" value="{{$residentData->first_name ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="middle_name">Middle Name</label>
                            @error('middle_name')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="middle_name" id="middle_name" value="{{$residentData->middle_name ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="last_name" class="flex gap-2 items-center">Last Name  <span class="text-xs text-red-500">*</span></label>
                            @error('last_name')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="last_name" id="last_name" value="{{$residentData->last_name ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="nickname">Nickname</label>
                            @error('nickname')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="nickname" id="nickname" value="{{$residentData->nickname ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="sex" class="flex gap-2 items-center">Sex  <span class="text-xs text-red-500">*</span></label>
                            @error('sex')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
            
                        @php
                            $_sex = $residentData->sex ?? null;
                        @endphp
                        <select class="form-input" name="sex" id="sex">
                            @foreach ($sex as $s)
                                <option value="{{$s->id}}" {{$s->id == $_sex ? 'selected' : ''}}>{{$s->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="birth_date" class="flex gap-2 items-center">Birth Date  <span class="text-xs text-red-500">*</span></label>
                            @error('birth_date')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="date" name="birth_date" id="birth_date" value="{{$residentData->birth_date ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="age">Age </label>
                            @error('age')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="age" id="age" value="{{$residentData->age ?? ''}}" readonly>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-5">
                <p class="font-bold text-lg">Contact Information</p>
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="house_number" class="flex gap-2 items-center">House Number  <span class="text-xs text-red-500">*</span></label>
                            @error('house_number')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="house_number" id="house_number" value="{{$residentData->house_number ?? ''}}">
                    </div>

                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="street_id" class="flex gap-2 items-center">Street<span class="text-xs text-red-500">*</span></label>
                            @error('street_id')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>

                        <select class="form-input" name="street_id" id="street_id">
                            @php
                                $_streetId = $residentData->street_id ?? null
                            @endphp
                            @foreach ($streets as $street)
                                <option value="{{$street->id}}" {{$_streetId == $street->id ? 'selected' : ''}}>{{$street->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="others">Unit/Bldg/Others</label>
                            @error('others')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="others" id="others" value="{{$residentData->others ?? ''}}">
                    </div>

                </div>
            </div>
            <div class="flex flex-row self-end mt-auto gap-3">
                @csrf
                @if ($residentData && !session()->get('new_resident.defendant'))
                    <input type="hidden" name="resident_id" id="resident_id" value="{{$residentData->resident_id}}">
                @endif
                <a href="{{url('/complaints/new/step-one')}}" class="secondary-btn">Back</a>
                <button class="primary-btn">Next</button>
            </div>
        </form>
    </div>  
</x-layout>
@vite('resources/js/searchresident.js')
@vite('resources/js/age.js')
