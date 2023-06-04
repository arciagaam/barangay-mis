<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="active" label="Step Two"/>
            <x-progress progress="active" label="Step Three"/>
            <x-progress progress="inactive" label="Complete"/>
        </div>
    </div>
    

    <form method="POST" action="{{url('/complaints/new/step-three')}}" class="flex flex-col bg-white flex-1 rounded-md shadow-md py-5 px-5 gap-7">
        @csrf
        <div class="flex flex-col gap-5">
            <div class="flex">
                <p class="font-bold text-lg">Complaint Details</p>
                <p class="text-project-blue/50 ml-auto text-xs italic">Fields with * are required.</p>
            </div>

            <div class="flex flex-col gap-5">

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="date_time_incident">Date and Time of Incident <span class="text-xs text-red-500">*</span></label>
                        @error('date_time_incident')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="datetime-local" name="date_time_incident" id="date_time_incident" value="{{$residentData->date_time_incident ?? ''}}">
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="incident_place">Place of Incident <span class="text-xs text-red-500">*</span></label>
                        @error('incident_place')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="incident_place" id="incident_place" value="{{$residentData->incident_place ?? ''}}">
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="incident_type">Type of Incident <span class="text-xs text-red-500">*</span></label>
                        @error('incident_type')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="incident_type" id="incident_type" value="{{$residentData->incident_type ?? ''}}">
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="details">Details</label>
                        @error('details')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <textarea name="details" class="form-input resize-none" id="details" cols="30" rows="10"></textarea>
                </div>

            </div>

            <div class="flex flex-col gap-5">

                <div class="flex flex-col gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="first_hearing">First Hearing</label>
                            @error('first_hearing')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="date" name="first_hearing" id="first_hearing" min={{date('Y-m-d')}}>
                    </div>

                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="first_hearing_details">Details</label>
                            @error('first_hearing_details')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <textarea class="form-input resize-none" name="first_hearing_details" id="first_hearing_details" cols="30" rows="10"></textarea>
                    </div>
                </div>


                <div class="flex flex-col gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="second_hearing">Second Hearing</label>
                            @error('second_hearing')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="date" name="second_hearing" id="second_hearing" min={{date('Y-m-d')}}>
                    </div>

                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="second_hearing_details">Details</label>
                            @error('second_hearing_details')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <textarea class="form-input resize-none" name="second_hearing_details" id="second_hearing_details" cols="30" rows="10"></textarea>
                    </div>

                </div>


                <div class="flex flex-col gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="third_hearing">Third Hearing</label>
                            @error('third_hearing')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="date" name="third_hearing" id="third_hearing" min={{date('Y-m-d')}}>
                    </div>

                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="third_hearing_details">Details</label>
                            @error('third_hearing_details')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <textarea class="form-input resize-none" name="third_hearing_details" id="third_hearing_details" cols="30" rows="10"></textarea>
                    </div>
                </div>


            </div>
        </div>

        <div  class="flex flex-row self-end mt-auto gap-3">
            <a href="{{url('/blotters/new/step-three')}}" class="secondary-btn">Back</a>
            <button class="primary-btn">Next</button>
        </div>
    </form>  
</x-layout>
