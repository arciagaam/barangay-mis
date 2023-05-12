<x-layout>
    <div class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-7">

        <div class="flex w-full items-center justify-between">
            <p class="font-bold text-xl">{{$activity->name}}</p>

            <div class="flex gap-3">
                @if (!$editing)
                    <form action="{{url("/calendar/activity/$activity->id/delete")}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{$activity->id}}">
                        <button class="py-2 px-4 bg-red-500 text-white font-bold rounded-md">Remove Activity</button>
                    </form>
                    <a href="{{url("/calendar/activity/$activity->id/edit")}}" class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md">Edit</a>
                @else
                    <p class="italic text-sm">Editing</p>
                @endif
            </div>
        </div>

        <form href="{{url("/calendar/activity/$activity->id/edit")}}" method="POST" class="flex flex-col">
            @csrf
            <div class="flex flex-col gap-5">
                <div class="flex gap-5 items-center">
    
                    <div class="flex gap-3">
                        <div class="form-input-container border-0 focus-within:font-normal">
                            <div class="flex flex-row justify-between items-center">
                                <label for="start_date">Start Date</label>
                                @error('start_date')
                                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                                @enderror
                            </div>
                            <input class="form-input w-fit read-only:focus:border-project-blue/10 read-only:bg-project-blue/10" type="date" name="start_date" id="start_date" value="{{$activity->start_date}}" readonly>
                        </div>
        
                        <div class="form-input-container {{$activity->is_all_day == 1 ? 'hidden' : ''}}">
                            <div class="flex flex-row justify-between items-center">
                                <label for="start_time">Start Time</label>
                            </div>
                            <input class="form-input w-fit" type="time" name="start_time" id="start_time" value="{{$activity->start_time}}" {{$editing ? '' : 'disabled'}}>
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
                            <input class="form-input w-fit" type="date" name="end_date" id="end_date" value="{{$activity->end_date}}" {{$editing ? '' : 'disabled'}}>
                        </div>
        
                        
                        <div class="form-input-container {{$activity->is_all_day == 1 ? 'hidden' : ''}}">
                            <div class="flex flex-row justify-between items-center ">
                                <label for="end_time">End Time</label>
                            </div>
                            <input class="form-input w-fit" type="time" name="end_time" id="end_time" value="{{$activity->end_time}}" {{$editing ? '' : 'disabled'}}>
                        </div>
                    </div>
        
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="all_day" id="all_day" {{$activity->is_all_day == 1 ? 'checked' : ''}} {{$editing ? '' : 'disabled'}}>
                        <label for="all_day">All Day</label>
                    </div>

                    <div class="flex flex-col ml-auto gap-2">
                        @error('start_time')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                        @error('end_time')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                </div>
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="name" >Activity Name</label>
                        @error('name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="name" id="name" value="{{$activity->name}}" {{$editing ? '' : 'disabled'}} >
                </div>
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="description" >Activity Description</label>
                        @error('description')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <textarea class="form-input resize-none" name="description" id="description" cols="30" rows="10" {{$editing ? '' : 'disabled'}}>{{$activity->description}}</textarea>
    
                </div>
            </div>
    
            @if ($editing)
                <div class="flex justify-end gap-5 items-center">
                    <a class="py-2 px-4 bg-table-even text-project-blue/50 rounded-md w-fit" href="{{url("calendar/activity/$activity->id")}}">Cancel</a>
                    <button class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md w-fit">Save</button>
                </div>
             @endif
        </form>
    </div>
</x-layout>

@vite('resources/js/calendar.js')