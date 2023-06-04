<x-layout>

    <x-pageheader>Blotters/Complaints</x-pageheader>

    <div class="flex flex-col gap-3 min-h-[88vh]">
        {{-- TABLE ACTIONS --}}
        <div class="flex gap-3 w-fit self-start">
            <a href="{{url('/blotters')}}" class="{{request()->is('blotters*') ? 'bg-project-yellow text-project-blue border-project-yellow border' : 'border-project-blue border'}}  ml-auto py-2 px-4 rounded-md text-sm flex items-center gap-2 font-bold">Blotters</a>
            <a href="{{url('/complaints')}}" class="{{request()->is('complaints*') ? 'bg-project-yellow text-project-blue border-project-yellow border' : 'border-project-blue border'}}  ml-auto py-2 px-4 rounded-md text-sm flex items-center gap-2 font-bold">Complaints</a>
        </div>
        <div class="flex flex-row w-full justify-between items-center">
            <form class="flex w-full gap-6 items-center">
                <div class="flex gap-2 items-center">
                    <label class="text-sm" for="search">Search</label>
                    <div class="flex items-center border border-table-even focus-within:border-project-blue rounded-md overflow-hidden gap-2 px-1 bg-white transition-all duration-300 ease-in-out">
                        <input class="w-full outline-none px-1 text-sm py-1" type="text" name="search" id="search" value="{{ request()->query()['search'] ?? null }}">
                        <button class="w-fit h-fit aspect-square flex items-center justify-center"><i class='bx bx-search'></i></button>
                    </div>
                </div>

                <div class="flex gap-2 items-center">
                    <label class="text-sm" for="rows">Rows per page</label>
                    <div class="flex w-10 items-center border border-table-even focus-within:border-project-blue rounded-md overflow-hidden gap-2 px-1 bg-white transition-all duration-300 ease-in-out">
                        <input class="w-full outline-none px-1 text-sm py-1" type="text" name="rows" id="rows" value={{ request()->query()['rows'] ?? 10 }}>
                    </div>
                </div>

                <div class="flex gap-2 items-center">
                    <label class="text-sm" for="filter">Filter</label>
                    <select onchange="this.form.submit()" class="form-input" name="filter" id="filter">
                        <option value="none" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'none' ? 'selected' : '') : ''}}>No Filter</option>
                        <option value="active" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'active' ? 'selected' : '') : ''}}>Active</option>
                        <option value="settled" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'settled' ? 'selected' : '') : ''}}>Settled</option>
                        <option value="rescheduled" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'rescheduled' ? 'selected' : '') : ''}}>Rescheduled</option>
                        <option value="unsettled" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'unsettled' ? 'selected' : '') : ''}}>Unsettled</option>
                    </select>
                </div>
            </form>

            <div class="flex flex-row w-full items-center">
                <a href="{{url('/blotters/new/step-one')}}" class="ml-auto py-2 px-4 bg-project-yellow text-project-blue rounded-md text-sm flex items-center gap-2 font-bold"><i class='bx bx-xs font-bold bx-plus'></i>Issue Blotter</a>
            </div>
        </div>

        <table class="main-table max-h-[65vh]">
            <thead>
                <tr>
                    <th>Blotter No.</th>
                    <th>Incident Type</th>
                    <th>Reporting Person</th>
                    <th>Victim</th>
                    <th>Suspect</th>
                    <th>Place of Incident</th>
                    <th>Status</th>
                    <th class="!text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($blotters as $blotter)
        
                    @php
                        $reporterIndex = array_keys(array_column(json_decode($blotter->recipients, true), 'blotter_role_id'), 1);
                        $victimIndex = array_keys(array_column(json_decode($blotter->recipients, true), 'blotter_role_id'), 2);
                        $suspectIndex = array_keys(array_column(json_decode($blotter->recipients, true), 'blotter_role_id'), 3);
                    @endphp
                    <tr>
                        <td>{{$blotter->blotter_id}}</td>
                        <td>{{$blotter->incident_type}}</td>
                        <td>
                            @foreach ($reporterIndex as $index)
                                <p>{{$blotter->recipients[$index]->first_name}} {{$blotter->recipients[$index]->middle_name}} {{$blotter->recipients[$index]->last_name}}</p>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($victimIndex as $index)
                                <p>{{$blotter->recipients[$index]->first_name}} {{$blotter->recipients[$index]->middle_name}} {{$blotter->recipients[$index]->last_name}}</p>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($suspectIndex as $index)
                                <p>{{$blotter->recipients[$index]->first_name}} {{$blotter->recipients[$index]->middle_name}} {{$blotter->recipients[$index]->last_name}}</p>
                            @endforeach
                        </td>
                        <td>{{$blotter->incident_place}}</td>
                        <td>{{ucfirst($blotter->status)}}</td>
                        <td>
                            <div class="flex flex-row flex-wrap justify-center items-center gap-2">
                                <a href="{{url("/blotters/$blotter->blotter_id")}}" class="aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><span class="material-symbols-outlined">visibility</span></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="grid grid-cols-4 gap-5 mt-auto">

            <a href="{{url('/blotters?filter=active')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                <div class="flex flex-1 items-center justify-center">
                    <i class='bx bx-sm bxs-file'></i>
                </div>

                <div class="flex flex-1 flex-col">
                    <p>Active Blotters</p>
                    <p class="text-3xl font-bold">{{$countData['active_blotters']}}</p>
                </div>
            </a>

            <a href="{{url('/blotters?filter=settled')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">

                <div class="flex flex-1 items-center justify-center">
                    <i class='bx bx-sm bxs-file'></i>
                </div>

                <div class="flex flex-1 flex-col">
                    <p>Settled Blotters</p>
                    <p class="text-3xl font-bold">{{$countData['settled_blotters']}}</p>
                </div>
            </a>

            <a href="{{url('/blotters?filter=rescheduled')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">

                <div class="flex flex-1 items-center justify-center">
                    <i class='bx bx-sm bxs-file'></i>
                </div>

                <div class="flex flex-1 flex-col">
                    <p>Rescheduled Blotters</p>
                    <p class="text-3xl font-bold">{{$countData['rescheduled_blotters']}}</p>
                </div>
            </a>
                
            <a href="{{url('/blotters?filter=unsettled')}}"class="flex justify-center items-center bg-white shadow-md rounded-md p-3 gap-5">
    
                <div class="flex flex-1 items-center justify-center">
                    <i class='bx bx-sm bxs-file'></i>
                </div>

                <div class="flex flex-1 flex-col">
                    <p>Unsettled Blotters</p>
                    <p class="text-3xl font-bold">{{$countData['unresolved_blotters']}}</p>
                </div>
            </a>

        </div>
        <div class="w-full flex">
            {{$blotter_pagination->links()}}
        </div>     
    </div>
</x-layout>