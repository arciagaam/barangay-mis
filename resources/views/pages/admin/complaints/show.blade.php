
<x-layout>
    @php
        $complainantIndex = array_keys(array_column(json_decode($recipients, true), 'complaint_role_id'), 1);
        $defendantIndex = array_keys(array_column(json_decode($recipients, true), 'complaint_role_id'), 2);
    @endphp

    <form href="{{url("/complaints/$complaint->complaint_id/edit")}}" method="POST" class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-7">
        @csrf
        <div class="flex w-full items-center justify-between ">
            <p class="font-bold text-lg">Complaint Information</p>

            @if (!$editing)
                <a href="{{url("/complaints/$complaint->complaint_id/edit")}}" class="primary-btn">Edit</a>
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
                                <option value="{{$value->id}}" {{$complaint->status_id == $value->id ? 'selected' : ''}}>{{ucfirst($value->name)}}</option>
                            @endforeach
                        </select>
                    @else
                        <input class="form-input" type="text" name="status_id" id="status_id" value="{{ucfirst($complaint->status)}}" {{$editing ? '' : 'disabled'}}>
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
                        <input class="form-input" type="datetime-local" name="date_time_incident" id="date_time_incident" value="{{$complaint->date_time_incident}}" {{$editing ? '' : 'disabled'}}>
                    </div>
    
                    <div class="form-input-container w-fit">
                        <div class="flex flex-row justify-between items-center">
                            <label for="date_time_reported" >Date and Time Reported</label>
                            @error('date_time_reported')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="datetime-local" name="date_time_reported" id="date_time_reported" value="{{$complaint->date_time_reported}}" {{$editing ? '' : 'disabled'}}>
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
                        <input class="form-input" type="text" name="incident_place" id="incident_place" value="{{$complaint->incident_place}}" {{$editing ? '' : 'disabled'}}>
                    </div>
    
                    <div class="form-input-container w-fit">
                        <div class="flex flex-row justify-between items-center">
                            <label for="incident_type" >Type of Incident</label>
                            @error('incident_type')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="incident_type" id="incident_type" value="{{$complaint->incident_type}}" {{$editing ? '' : 'disabled'}}>
                    </div>
                </div>


                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="details" >Details</label>
                        @error('details')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>


                    <textarea class="form-input resize-none" type="text" name="details" id="details" {{$editing ? '' : 'disabled'}} cols="30" rows="10">{{$complaint->details}}</textarea>
                </div>    
            </div>

            <div class="flex flex-col gap-3">
                <p class="font-bold text-lg">Complainant Information</p>
    
                @foreach ($complainantIndex as $index )
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
                <p class="font-bold text-lg">Defendant Information</p>
    
                @foreach ($defendantIndex as $index )
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
                <p class="text-lg font-bold">First Hearing</p>
                <div class="flex flex-col gap-3">

                    <div class="flex gap-3">
                        <div class="form-input-container w-fit">
                            <div class="flex flex-row justify-between items-center">
                                <label for="first_hearing_status_id" >Status</label>
                                @error('first_hearing_status_id')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                                @enderror
                            </div>
                            @php
                            $oneStatus = $hearings[0]->status_id ?? null
                            @endphp
                            @if ($editing)
                                <select class="form-input" name="first_hearing_status_id" id="first_hearing_status_id">
                                    <option value="{{null}}">Choose status</option>
                                    @foreach ($status as $value)
                                        <option value="{{$value->id}}" {{$oneStatus == $value->id ? 'selected' : ''}}>{{ucfirst($value->name)}}</option>
                                    @endforeach
                                </select>
                            @else
                                <input class="form-input" type="text" name="first_hearing_status_id" id="first_hearing_status_id" value="{{ucfirst($hearings[0]->status ?? null)}}" {{$editing ? '' : 'disabled'}}>
                            @endif
                        </div>
                        <div class="form-input-container">
                            <div class="flex flex-row justify-between items-center">
                                <label for="first_hearing_date" >Date</label>
                                @error('first_hearing_date')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                                @enderror
                            </div>
                            <input class="form-input" type="date" name="first_hearing_date" id="first_hearing_date" value="{{$hearings[0]->date ?? null}}" {{$editing ? '' : 'disabled'}}>
                        </div>
                    </div>

                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="first_hearing_details">Details</label>
                            @error('first_hearing_details')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <textarea class="form-input resize-none" name="first_hearing_details" id="first_hearing_details" cols="30" rows="10"  {{$editing ? '' : 'disabled'}}>{{$hearings[0]->details ?? null}}</textarea>
                    </div>

                </div>
            </div>

            <div class="flex flex-col gap-3">
                <p class="text-lg font-bold">Second Hearing</p>
                <div class="flex flex-col gap-3">

                    <div class="flex gap-3">
                        <div class="form-input-container w-fit">
                            <div class="flex flex-row justify-between items-center">
                                <label for="second_hearing_status_id" >Status</label>
                                @error('second_hearing_status_id')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                                @enderror
                            </div>
                            @php
                            $twoStatus = $hearings[1]->status_id ?? null
                            @endphp
                            @if ($editing)
                                <select class="form-input" name="second_hearing_status_id" id="second_hearing_status_id">
                                    <option value="{{null}}">Choose status</option>
                                    @foreach ($status as $value)
                                        <option value="{{$value->id}}" {{$twoStatus == $value->id ? 'selected' : ''}}>{{ucfirst($value->name)}}</option>
                                    @endforeach
                                </select>
                            @else
                                <input class="form-input" type="text" name="second_hearing_status_id" id="second_hearing_status_id" value="{{ucfirst($hearings[1]->status ?? null)}}" {{$editing ? '' : 'disabled'}}>
                            @endif
                        </div>
                        <div class="form-input-container">
                            <div class="flex flex-row justify-between items-center">
                                <label for="second_hearing_date" >Date</label>
                                @error('second_hearing_date')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                                @enderror
                            </div>
                            <input class="form-input" type="date" name="second_hearing_date" id="second_hearing_date" value="{{$hearings[1]->date ?? null}}" {{$editing ? '' : 'disabled'}}>
                        </div>
                    </div>                    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="second_hearing_details">Details</label>
                            @error('second_hearing_details')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <textarea class="form-input resize-none" name="second_hearing_details" id="second_hearing_details" cols="30" rows="10"  {{$editing ? '' : 'disabled'}}>{{$hearings[1]->details ?? null}}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <p class="text-lg font-bold">Third Hearing</p>
                <div class="flex flex-col gap-3">

                    <div class="flex gap-3">
                        <div class="form-input-container w-fit">
                            <div class="flex flex-row justify-between items-center">
                                <label for="third_hearing_status_id" >Status</label>
                                @error('third_hearing_status_id')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                                @enderror
                            </div>
                            @php
                                $threeStatus = $hearings[2]->status_id ?? null
                            @endphp
                            @if ($editing)
                                <select class="form-input" name="third_hearing_status_id" id="third_hearing_status_id">
                                    <option value="{{null}}">Choose status</option>
                                    @foreach ($status as $value)
                                        <option value="{{$value->id}}" {{$threeStatus == $value->id ? 'selected' : ''}}>{{ucfirst($value->name)}}</option>
                                    @endforeach
                                </select>
                            @else
                                <input class="form-input" type="text" name="third_hearing_status_id" id="third_hearing_status_id" value="{{ucfirst($hearings[2]->status ?? null)}}" {{$editing ? '' : 'disabled'}}>
                            @endif
                        </div>
                        <div class="form-input-container">
                            <div class="flex flex-row justify-between items-center">
                                <label for="third_hearing_date" >Date</label>
                                @error('third_hearing_date')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                                @enderror
                            </div>
                            <input class="form-input" type="date" name="third_hearing_date" id="third_hearing_date" value="{{$hearings[2]->date ?? null}}" {{$editing ? '' : 'disabled'}}>
                        </div>
                    </div>

                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="third_hearing_details">Details</label>
                            @error('third_hearing_details')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <textarea class="form-input resize-none" name="third_hearing_details" id="third_hearing_details" cols="30" rows="10"  {{$editing ? '' : 'disabled'}}>{{$hearings[2]->details ?? null}}</textarea>
                    </div>

                </div>
            </div>
        </div>


        @if ($editing)
            <div class="flex justify-end gap-3 items-center">
                <a class="secondary-btn" href="{{url("/complaints/$complaint->complaint_id")}}">Cancel</a>
                <button class="primary-btn">Save</button>
            </div>
        @endif
    </form>
</x-layout>