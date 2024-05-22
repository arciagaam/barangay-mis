
<x-layout>
    
    <form href="{{url("/residents/$resident->resident_id/edit")}}" method="POST" class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-7">
        @csrf
        <div class="flex w-full items-center justify-between">
            <p class="font-bold text-xl">{{$resident->first_name}} {{$resident->middle_name}} {{$resident->last_name}}</p>
            @if (!$editing)

            <div class="flex gap-3 h-fit">
                <button type="button" data-id="{{$resident->resident_id}}" data-fallback="{{url('/residents')}}" data-route="positions" data-url="{{url("residents/$resident->resident_id/delete")}}" data-type="archive" data-group="resident" class="popup_trigger py-2 px-4 bg-red-500 text-white font-bold rounded-md">Archive Resident</button>
                <a href="{{url("/residents/$resident->resident_id/edit")}}" class="primary-btn">Edit</a>
            </div>
            @else
                <p class="italic text-sm">Editing</p>
            @endif
        </div>

        <div class="flex flex-col gap-5">
            <p class="font-bold text-lg">Resident Information</p>

            <div class="grid grid-cols-3 gap-3">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="first_name" >First Name</label>
                        @error('first_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="first_name" id="first_name" value="{{$resident->first_name}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="middle_name" > Middle Name</label>
                        @error('middle_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="middle_name" id="middle_name" value="{{$resident->middle_name}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="last_name" > Last Name</label>
                        @error('last_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="last_name" id="last_name" value="{{$resident->last_name}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="nickname" > Nickname</label>
                        @error('nickname')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="nickname" id="nickname" value="{{$resident->nickname}}" {{$editing ? '' : 'disabled'}}>
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
                            @foreach ($sex as $s)
                                <option value="{{$s->id}}" {{$s->id == $resident->sex ? 'selected' : ''}}>{{$s->name}}</option>
                            @endforeach
                        </select>
                    @else
                        <input class="form-input" type="text" name="sex" id="sex" value="{{$resident->sex}}" {{$editing ? '' : 'disabled'}}>
                    @endif
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="birth_date" > Birth Date</label>
                        @error('birth_date')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="date" name="birth_date" id="birth_date" value="{{$resident->birth_date}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="age" > Age</label>
                        @error('age')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="age" id="age" value="{{$resident->age}}" {{$editing ? '' : 'disabled'}} readonly>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="place_of_birth" > Place of Birth</label>
                        @error('place_of_birth')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="place_of_birth" id="place_of_birth" value="{{$resident->place_of_birth}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="civil_status_id" > Civil Status</label>
                        @error('civil_status_id')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    

                    @if ($editing)
                        <select class="form-input" name="civil_status_id" id="civil_status_id" {{$editing ? '' : 'disabled'}}>
                            @foreach ($options['civilStatus'] as $civilStatus)
                                <option value="{{$civilStatus->id}}" {{$resident->civil_status == $civilStatus->name ? 'selected' : ''}}>{{ucfirst($civilStatus->name)}}</option>
                            @endforeach
                        </select> 
                    @else
                        <input class="form-input" type="text" name="place_of_birth" id="place_of_birth" value="{{ucfirst($resident->civil_status)}}" {{$editing ? '' : 'disabled'}}>
                    @endif
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="occupation_id" > Occupation</label>
                        @error('occupation_id')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    
                    @if ($editing)
                        <select class="form-input" name="occupation_id" id="occupation_id" {{$editing ? '' : 'disabled'}}>
                            @foreach ($options['occupation'] as $occupation)
                                <option value="{{$occupation->id}}" {{$resident->civil_status == $occupation->name ? 'selected' : ''}}>{{ucfirst($occupation->name)}}</option>
                            @endforeach
                        </select> 
                    @else
                        <input class="form-input" type="text" name="occupation_id" id="occupation_id" value="{{ucfirst($resident->occupation)}}" {{$editing ? '' : 'disabled'}}> 
                    @endif
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="religion_id" > Religion</label>
                        @error('religion_id')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    
                    @if ($editing)                    
                        <select class="form-input" name="religion_id" id="religion_id" {{$editing ? '' : 'disabled'}}>
                            @foreach ($options['religion'] as $religion)
                                <option value="{{$religion->id}}" {{$resident->civil_status == $religion->name ? 'selected' : ''}}>{{ucfirst($religion->name)}}</option>
                            @endforeach
                        </select> 
                    @else
                        <input class="form-input" type="text" name="religion_id" id="religion_id" value="{{ucfirst($resident->religion)}}" {{$editing ? '' : 'disabled'}}>
                    @endif
                </div>

                
            </div>
        </div>

        <div class="flex flex-col gap-5">
            <p class="font-bold text-lg">Contact Information</p>

            <div class="grid grid-cols-2 gap-3">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="house_number" >House Number</label>
                        @error('house_number')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="house_number" id="house_number" value="{{$resident->house_number}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="street_id" class="flex gap-2 items-center">Street<span class="text-xs text-red-500">*</span></label>
                        @error('street_id')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>

                    @if ($editing)
                        <select class="form-input" name="street_id" id="street_id" {{$editing ? '' : 'disabled'}}>
                            @php 
                                $_streetId = $resident->street_id ?? null
                            @endphp
                            @foreach ($streets as $street)
                                <option value="{{$street->id}}" {{$_streetId == $street->id ? 'selected' : ''}}>{{$street->name}}</option>
                            @endforeach
                        </select>
                    @else
                        <input class="form-input" type="text" value="{{$resident->street}}" disabled>
                    @endif


                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="others" >Unit/Bldg/Others</label>
                        @error('others')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="others" id="others" value="{{$resident->others}}" {{$editing ? '' : 'disabled'}}>
                </div>
  
            </div>
        </div>

        <div class="flex flex-col gap-5">
            <p class="font-bold text-lg">Additional Information</p>

            <div class="grid grid-cols-3 gap-3">
                @if ($resident->age >= 18)   
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="voter_status" >Voter Status</label>
                            @error('voter_status')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        
                        @if ($editing)                    
                            <select class="form-input" name="voter_status" id="voter_status" {{$editing ? '' : 'disabled'}}>
                                    <option value="0" {{$resident->voter_status == 0 ? 'selected' : ''}}>Unregistered</option>
                                    <option value="1" {{$resident->voter_status == 1 ? 'selected' : ''}}>Registered</option>
                            </select> 
                        @else
                            <input class="form-input" type="text" name="voter_status" id="voter_status" value="{{ucfirst($resident->voter_status == 1 ? 'Registered' : 'Non Registered')}}" {{$editing ? '' : 'disabled'}}>
                        @endif
                    </div>
                @endif

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="disabled" > Disabled</label>
                        @error('disabled')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    
                    @if ($editing)
                        <select class="form-input" name="disabled" id="disabled" {{$editing ? '' : 'disabled'}}>
                            <option value="0" {{$resident->disabled == 0 ? 'selected' : ''}}>Abled</option>
                            <option value="1" {{$resident->disabled == 1 ? 'selected' : ''}}>Disabled</option>
                        </select> 
                    @else
                        <input class="form-input" type="text" name="disabled" id="disabled" value="{{$resident->disabled == 1 ? 'Disabled' : 'Abled'}}" {{$editing ? '' : 'disabled'}}>
                    @endif
                </div>   
            </div>
        </div>

        @if ($editing)
            <div class="flex justify-end gap-5 items-center">
                <a class="secondary-btn" href="{{url("/residents/$resident->resident_id")}}">Cancel</a>
                <button class="primary-btn w-fit">Save</button>
            </div>
        @endif
    </form>
</x-layout>

@vite('resources/js/age.js')
@vite('resources/js/voter_status.js')
