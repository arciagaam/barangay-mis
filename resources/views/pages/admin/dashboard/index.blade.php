<x-layout>
    <p class="text-3xl font-bold">Dashboard</p>

    <div class="flex flex-col gap-5">
        <div class="flex flex-col min-h-[70vh] gap-7">
            <div class="dashboard-grid grid grid-cols-3 gap-5 h-full">
                <a href="{{url('/residents')}}" class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-lg bxs-user-account'></i>
                    </div> 
    
                    <div class="flex flex-1 flex-col">
                        <p>Total Residents</p>
                        <p class="text-3xl font-bold">{{$data['residents']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/residents?filter=male')}}" class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bx-male-sign' ></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Total Male</p>
                        <p class="text-3xl font-bold">{{$data['male']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/residents?filter=female')}}" class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bx-female-sign' ></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Total Female</p>
                        <p class="text-3xl font-bold">{{$data['female']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/residents?filter=voter')}}" class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bx-fingerprint' ></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Total Voters</p>
                        <p class="text-3xl font-bold">{{$data['voter']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/residents?filter=non-voter')}}" class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                    <div class="flex flex-1 items-center justify-center">
                        <div class="relative">
                            <i class='bx bx-sm bx-fingerprint'></i>
                            <i class='bx bx-sm bx-x absolute top-0 right-0 -translate-y-5 translate-x-5 text-project-yellow font-bold'></i>
                        </div>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Total Non-Voters</p>
                        <p class="text-3xl font-bold">{{$data['non_voter']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/residents?filter=toddler')}}" class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bxs-baby-carriage' ></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Total Toddlers</p>
                        <p class="text-3xl font-bold">{{$data['toddler']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/residents?filter=senior')}}" class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
                    
                    <div class="flex flex-1 items-center justify-center">
                        <div class="relative">
                            <i class='bx bx-sm bxs-user-account'></i>
                            <p class='text-3xl absolute top-0 right-0 -translate-y-5 translate-x-10 text-project-yellow font-bold'>60+</p>
                        </div>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Total Senior</p>
                        <p class="text-3xl font-bold">{{$data['senior']}}</p>
                    </div>
                </a>
    
            </div>

            <p class="text-lg font-bold">Blotters</p>

            <div class="dashboard-grid grid grid-cols-3 gap-5 h-full">
                <a href="{{url('/blotters')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bxs-briefcase-alt'></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Total Blotter</p>
                        <p class="text-3xl font-bold">{{$data['blotter_count']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/blotters?filter=unsettled')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bxs-briefcase-alt'></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Unsettled Blotters</p>
                        <p class="text-3xl font-bold">{{$data['unresolved_blotters']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/blotters?filter=active')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bxs-briefcase-alt'></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Active Blotters</p>
                        <p class="text-3xl font-bold">{{$data['active_blotters']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/blotters?filter=rescheduled')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bxs-briefcase-alt'></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Rescheduled Blotters</p>
                        <p class="text-3xl font-bold">{{$data['rescheduled_blotters']}}</p>
                    </div>
                </a>
            </div>

            <p class="text-lg font-bold">Complaints</p>
    
            <div class="dashboard-grid grid grid-cols-3 gap-5 h-full">
                <a href="{{url('/complaints')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
        
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bxs-file'></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Total Complaints</p>
                        <p class="text-3xl font-bold">{{$data['complaints_count']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/complaints?filter=unsettled')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bxs-file'></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Unsettled Complaints</p>
                        <p class="text-3xl font-bold">{{$data['unresolved_complaints']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/complaints?filter=active')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bxs-file'></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Active Complaints</p>
                        <p class="text-3xl font-bold">{{$data['active_complaints']}}</p>
                    </div>
                </a>
    
                <a href="{{url('/complaints?filter=rescheduled')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                    <div class="flex flex-1 items-center justify-center">
                        <i class='bx bx-sm bxs-file'></i>
                    </div>
    
                    <div class="flex flex-1 flex-col">
                        <p>Rescheduled Complaints</p>
                        <p class="text-3xl font-bold">{{$data['rescheduled_complaints']}}</p>
                    </div>
                </a>
            </div>

        </div>



        <div class="flex flex-col gap-5 mt-5">
            <p class="text-3xl font-bold">Calendar</p>
            <div class="h-screen" id="calendarEl"></div>
        </div>
    </div>
</x-layout>

<div id="add_activity" class="pl-16 absolute hidden flex inset-0 items-center justify-center min-h-[calc(100%)] min-w-[calc(100%)] flex-col bg-black/20 text-project-blue ">
    <div class="flex flex-col w-3/4 min-h-10 bg-white rounded-md p-5 gap-7">
        <div class="flex justify-between">
            <p class="text-lg font-bold">Add New Activity</p>
            <i id="close_modal" class='bx bx-sm bxs-x-circle self-end cursor-pointer'></i>
        </div>

        <div class="flex gap-5 items-center">

            <div class="flex gap-3">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="start_date">Start Date</label>
                        @error('start_date')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input w-fit" type="date" name="start_date" id="start_date" disabled>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="start_time">Start Time</label>
                        @error('start_time')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input w-fit" type="time" name="start_time" id="start_time">
                </div>
            </div>

            <p class="h-fit">-</p>

            <div class="flex gap-3">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="end_date">End Date</label>
                        @error('end_date')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input w-fit" type="date" name="end_date" id="end_date">
                </div>

                
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="end_time">End Time</label>
                        @error('end_time')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input w-fit" type="time" name="end_time" id="end_time">
                </div>
            </div>



            <div class="flex items-center gap-3">
                <input type="checkbox" name="all_day" id="all_day">
                <label for="all_day">All Day</label>
            </div>

            <p id="error_start_end_time" class="text-xs text-red-500 italic ml-auto"></p>

        </div>

        <div class="form-input-container">
            <div class="flex flex-row justify-between items-center">
                <label for="name">Activity Name</label>
                <p id="error_name" class="text-xs text-red-500 italic"></p>
            </div>
            <input class="form-input w-1/2" type="text" name="name" id="name">
        </div>

        <div class="form-input-container">
            <div class="flex flex-row justify-between items-center">
                <label for="description">Description</label>
                    <p id="error_description" class="text-xs text-red-500 italic"></p>
            </div>
            <textarea class="form-input w-1/2 resize-none" t name="description" id="description" cols="30" rows="10"></textarea>
        </div>

        <button id="submit_activity" class="bg-project-yellow font-bold py-2 px-4 rounded-md">Add Activity</button>
    </div>
</div>


@vite('resources/js/calendar.js')