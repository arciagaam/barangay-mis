<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="active" label="Step Two"/>
            <x-progress progress="active" label="Step Three"/>
            <x-progress progress="active" label="Step Four"/>
            <x-progress progress="inactive" label="Complete"/>
        </div>
    </div>
    

    <form method="POST" action="{{url('/blotters/new/step-four')}}" class="flex flex-col bg-white flex-1 rounded-md shadow-md py-5 px-5 gap-7">
        @csrf
        <div class="flex flex-col gap-5">
            <p class="font-bold text-lg">Blotter Details</p>

            <div class="flex flex-col gap-5">

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="date_time_incident">Date and Time of Incident</label>
                        @error('date_time_incident')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="datetime-local" name="date_time_incident" id="date_time_incident" value="{{$residentData->date_time_incident ?? ''}}">
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="incident_place">Place of Incident</label>
                        @error('incident_place')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="incident_place" id="incident_place" value="{{$residentData->incident_place ?? ''}}">
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="incident_type">Type of Incident</label>
                        @error('incident_type')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="incident_type" id="incident_type" value="{{$residentData->incident_type ?? ''}}">
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="description">Description</label>
                        @error('description')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <textarea name="description" class="form-input resize-none" id="description" cols="30" rows="10"></textarea>
                </div>

            </div>
        </div>

        <div  class="flex flex-row self-end mt-auto gap-3">
            <a href="{{url('/blotters/new/step-three')}}" class="py-2 px-4 bg-table-even text-project-blue/40 rounded-md">Back</a>
            <button class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md">Next</button>
        </div>
    </form>  
</x-layout>
