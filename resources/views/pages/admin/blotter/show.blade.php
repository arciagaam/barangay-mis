
<x-layout>
    @php
        $reporterIndex = array_keys(array_column(json_decode($recipients, true), 'blotter_role_id'), 1);
        $victimIndex = array_keys(array_column(json_decode($recipients, true), 'blotter_role_id'), 2);
        $suspectIndex = array_keys(array_column(json_decode($recipients, true), 'blotter_role_id'), 3);
    @endphp

    <form href="{{url("/blotters/$blotter->blotter_id/edit")}}" method="POST" class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-7">
        @csrf
        <div class="flex w-full items-center justify-between ">
            <p class="font-bold text-lg">Blotter Information</p>

            @if (!$editing)
                <a href="{{url("/blotters/$blotter->blotter_id/edit")}}" class="primary-btn">Edit</a>
            @else
                <p class="italic text-sm">Editing</p>
            @endif
        </div>

        <div class="flex flex-col gap-7">

            <div class="flex flex-col gap-3">
                
                <div class="form-input-container w-fit">
                    <div class="flex flex-row justify-between items-center">
                        <label for="status_id" >Status</label>
                        @error('status_id')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    @if ($editing)
                        <select class="form-input" name="status_id" id="status_id">
                            @foreach ($status as $value)
                                <option value="{{$value->id}}" {{$blotter->status_id == $value->id ? 'selected' : ''}}>{{ucfirst($value->name)}}</option>
                            @endforeach
                        </select>
                    @else
                        <input class="form-input" type="text" name="status_id" id="status_id" value="{{ucfirst($blotter->status)}}" {{$editing ? '' : 'disabled'}}>
                    @endif
                </div>

                <div class="flex flex-row gap-3">
                    <div class="form-input-container w-fit">
                        <div class="flex flex-row justify-between items-center">
                            <label for="date_time_incident">Date and Time of Incident</label>
                            @error('date_time_incident')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="datetime-local" name="date_time_incident" id="date_time_incident" value="{{$blotter->date_time_incident}}" {{$editing ? '' : 'disabled'}}>
                    </div>
    
                    <div class="form-input-container w-fit">
                        <div class="flex flex-row justify-between items-center">
                            <label for="date_time_reported" >Date and Time Reported</label>
                            @error('date_time_reported')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="datetime-local" name="date_time_reported" id="date_time_reported" value="{{$blotter->date_time_reported}}" {{$editing ? '' : 'disabled'}}>
                    </div>
                </div>

                <div class="flex flex-row gap-3">
                    <div class="form-input-container w-fit">
                        <div class="flex flex-row justify-between items-center">
                            <label for="incident_place" >Place of Incident</label>
                            @error('incident_place')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="incident_place" id="incident_place" value="{{$blotter->incident_place}}" {{$editing ? '' : 'disabled'}}>
                    </div>
    
                    <div class="form-input-container w-fit">
                        <div class="flex flex-row justify-between items-center">
                            <label for="incident_type" >Type of Incident</label>
                            @error('incident_type')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="incident_type" id="incident_type" value="{{$blotter->incident_type}}" {{$editing ? '' : 'disabled'}}>
                    </div>
                </div>


                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="description" >Description</label>
                        @error('description')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>


                    <textarea class="form-input resize-none" type="text" name="description" id="description" {{$editing ? '' : 'disabled'}} cols="30" rows="10">{{$blotter->description}}</textarea>
                </div>    
            </div>

            <div class="flex flex-col gap-3">
                <p class="font-bold text-lg">Reporter Information</p>
    
                @foreach ($reporterIndex as $index )
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="first_name" >First name</label>
                            @error('first_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="first_name" id="first_name" value="{{$recipients[$index]->first_name}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="middle_name" > Middle Name</label>
                            @error('middle_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="middle_name" id="middle_name" value="{{$recipients[$index]->middle_name}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="last_name" > Last Name</label>
                            @error('last_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="last_name" id="last_name" value="{{$recipients[$index]->last_name}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="nickname" > Nickname</label>
                            @error('nickname')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="nickname" id="nickname" value="{{$recipients[$index]->nickname}}" disabled>
                    </div>   
                </div>
                @endforeach
    
            </div>
    
            <div class="flex flex-col gap-3">
                <p class="font-bold text-lg">Victim Information</p>
    
                @foreach ($victimIndex as $index )
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="first_name" >First name</label>
                            @error('first_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="first_name" id="first_name" value="{{$recipients[$index]->first_name}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="middle_name" > Middle Name</label>
                            @error('middle_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="middle_name" id="middle_name" value="{{$recipients[$index]->middle_name}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="last_name" > Last Name</label>
                            @error('last_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="last_name" id="last_name" value="{{$recipients[$index]->last_name}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="nickname" > Nickname</label>
                            @error('nickname')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="nickname" id="nickname" value="{{$recipients[$index]->nickname}}" disabled>
                    </div>   
                </div>
                @endforeach
    
            </div>
    
            <div class="flex flex-col gap-3">
                <p class="font-bold text-lg">Suspect Information</p>
    
                @foreach ($suspectIndex as $index )
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="first_name" >First name</label>
                            @error('first_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="first_name" id="first_name" value="{{$recipients[$index]->first_name}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="middle_name" > Middle Name</label>
                            @error('middle_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="middle_name" id="middle_name" value="{{$recipients[$index]->middle_name}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="last_name" > Last Name</label>
                            @error('last_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="last_name" id="last_name" value="{{$recipients[$index]->last_name}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="nickname" > Nickname</label>
                            @error('nickname')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="nickname" id="nickname" value="{{$recipients[$index]->nickname}}" disabled>
                    </div>   
                </div>
                @endforeach
    
            </div>
        </div>


        @if ($editing)
            <div class="flex justify-end gap-3 items-center">
                <a class="secondary-btn" href="{{url("/blotters/$blotter->blotter_id")}}">Cancel</a>
                <button class="primary-btn">Save</button>
            </div>
        @endif
    </form>
</x-layout>