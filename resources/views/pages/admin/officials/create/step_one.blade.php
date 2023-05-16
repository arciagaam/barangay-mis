<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="inactive" label="Step Two"/>
            <x-progress progress="inactive" label="Complete"/>
        </div>
    </div>

    <div class="flex flex-col bg-white flex-1 rounded-md shadow-md py-5 px-5 gap-7">
        @csrf
        <div class="flex gap-3">
            <div class="flex gap-2 w-1/2 items-center">
                <label class="text-sm" for="search">Search</label>
                <div class="relative w-full">
                    <div class="flex items-center border w-full border-table-even focus-within:border-project-blue rounded-md overflow-hidden gap-2 px-1 bg-white transition-all duration-300 ease-in-out">
                        <input id="resident_search" class="w-full outline-none px-1 text-sm py-1" type="text" name="search" id="search" value="{{ $residentData ? "$residentData->first_name $residentData->middle_name $residentData->last_name" :  null }}">
                        <button class="w-fit h-fit aspect-square flex items-center justify-center"><i class='bx bx-search'></i></button>
                    </div>
                    <div id="search_names_container" class="absolute top-[calc(100% + 2em)] flex flex-col gap-1 bg-white rounded-md shadow-md w-full p-2 hidden">
                    </div>
                </div>
            </div>

            <button type="button" class="py-2 px-4 bg-project-yellow text-project-blue rounded-md text-sm">Search</button>
        </div>

        <form method="POST" action="{{url('/officials/new/step-one')}}" class="flex flex-col h-full">
            @csrf
            <div class="flex flex-col gap-5">
                <p class="font-bold text-lg">Resident Information</p>
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="first_name">First Name</label>
                        </div>
                        <input class="form-input" type="text" name="first_name" id="first_name" value="{{$residentData->first_name ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="middle_name">Middle Name</label>
                        </div>
                        <input class="form-input" type="text" name="middle_name" id="middle_name" value="{{$residentData->middle_name ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="last_name">Last Name</label>
                        </div>
                        <input class="form-input" type="text" name="last_name" id="last_name" value="{{$residentData->last_name ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="nickname">Nickname</label>
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
                            <option value="1" {{$_sex == 1 ? 'selected' : ''}}>Male</option>
                            <option value="2" {{$_sex == 2 ? 'selected' : ''}}>Female</option>
                        </select>
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="birth_date">Birth Date</label>
                        </div>
                        <input class="form-input" type="text" name="birth_date" id="birth_date" value="{{$residentData->birth_date ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="age">Age</label>
                        </div>
                        <input class="form-input" type="text" name="age" id="age" value="{{$residentData->age ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="place_of_birth">Place of Birth</label>
                        </div>
                        <input class="form-input" type="text" name="place_of_birth" id="place_of_birth" value="{{$residentData->place_of_birth ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="occupation">Occupation</label>
                        </div>
                        <input class="form-input" type="text" name="occupation" id="occupation" value="{{$residentData->occupation ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="religion_id" class="flex gap-2 items-center">Religion  <span class="text-xs text-red-500">*</span></label>
                            @error('religion_id')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
            
                        @php
                            $_religion = $residentData->religion ?? null;
                        @endphp
            
                        <select class="form-input" name="religion_id" id="religion_id">
                            @foreach ($religions as $religion)
                                <option value="{{$religion->id}}" {{$_religion == $religion->id ? 'selected' : ''}}>{{ucfirst($religion->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-5">
                <p class="font-bold text-lg">Contact Information</p>
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="house_number">House Number</label>
                        </div>
                        <input class="form-input" type="text" name="house_number" id="house_number" value="{{$residentData->house_number ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="purok">Purok</label>
                        </div>
                        <input class="form-input" type="text" name="purok" id="purok" value="{{$residentData->purok ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="block">Block</label>
                        </div>
                        <input class="form-input" type="text" name="block" id="block" value="{{$residentData->block ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="lot">Lot</label>
                        </div>
                        <input class="form-input" type="text" name="lot" id="lot" value="{{$residentData->lot ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="others">Street/Unit/Bldg/Others</label>
                        </div>
                        <input class="form-input" type="text" name="others" id="others" value="{{$residentData->others ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="subdivision">Subdivision</label>
                        </div>
                        <input class="form-input" type="text" name="subdivision" id="subdivision" value="{{$residentData->subdivision ?? ''}}">
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-5">
                <p class="font-bold text-lg">Additional Information</p>
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="voter_status">Voter Status</label>
                        </div>
                        <input class="form-input" type="text" name="voter_status" id="voter_status" value="{{$residentData->voter_status ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="precinct_number">Precinct Number</label>
                        </div>
                        <input class="form-input" type="text" name="precinct_number" id="precinct_number" value="{{$residentData->precinct_number ?? ''}}">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="disabled">Disabled</label>
                        </div>
                        <input class="form-input" type="text" name="disabled" id="disabled" value="{{$residentData->disabled ?? ''}}">
                    </div>
                </div>
            </div>
            <div class="flex flex-row self-end mt-auto ml-auto gap-3">
                @if ($residentData && !session()->get('new_resident'))
                    <input type="hidden" name="resident_id" id="resident_id" value="{{$residentData->resident_id}}">
                @endif
                <button class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md">Next</button>
            </div>
        </form>
        
    </div>  
</x-layout>
@vite('resources/js/searchresident.js')
