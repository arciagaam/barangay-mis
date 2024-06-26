<x-layout>

    <x-pageheader>Residents</x-pageheader>

    <div class="flex flex-col gap-3">
        {{-- TABLE ACTIONS --}}
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
                    <input class="form-input w-10" type="text" name="rows" id="rows" value={{ request()->query()['rows'] ?? 10 }}>
                </div>

                <div class="flex gap-2 items-center">
                    <label class="text-sm" for="filter">Filter</label>
                    <select onchange="this.form.submit()" class="form-input" name="filter" id="filter">
                        <option value="none" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'none' ? 'selected' : '') : ''}}>No Filter</option>
                        <option value="male" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'male' ? 'selected' : '') : ''}}>Male</option>
                        <option value="female" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'female' ? 'selected' : '') : ''}}>Female</option>
                        <option value="voter" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'voter' ? 'selected' : '') : ''}}>Voter</option>
                        <option value="non-voter" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'non-voter' ? 'selected' : '') : ''}}>Non-Voter</option>
                        <option value="toddler" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'toddler' ? 'selected' : '') : ''}}>Toddler</option>
                        <option value="senior" {{isset(request()->query()['filter']) ? (request()->query()['filter'] == 'senior' ? 'selected' : '') : ''}}>Senior</option>
                    </select>
                </div>
            </form>

            <div class="flex flex-row w-full items-center">
                <a href="{{url('/residents/new/step-one')}}" class="ml-auto py-2 px-4 bg-project-yellow text-project-blue rounded-md text-sm flex items-center gap-2 font-bold"><i class='bx bx-xs font-bold bx-plus'></i>Add Resident</a>
            </div>
        </div>

        <table class="main-table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Birth Date</th>
                    <th>Place of Birth</th>
                    <th>Sex</th>
                    <th>Address</th>
                    <th class="!text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($residents as $resident)

                    @php
                        $address = 
                        $resident->house_number . ' ' . 
                        $resident->street . ' ' .
                        ($resident->others ? " $resident->others" : '' );
                    @endphp

                    <tr>
                        <td>{{$resident->first_name}} {{$resident->middle_name}} {{$resident->last_name}}</td>
                        <td>{{$resident->birth_date}}</td>
                        <td>{{$resident->place_of_birth}}</td>
                        <td>{{$resident->sex}}</td>
                        <td>{{$address}}</td>
                        <td>
                            <div class="flex flex-row flex-wrap justify-center items-center gap-2">
                                <a href="{{url("/residents/$resident->resident_id")}}" class="aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><span class="material-symbols-outlined">visibility</span></a>
                                <a href="{{url("/residents/$resident->resident_id/edit")}}" class="aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><i class='bx bx-sm bxs-pencil'></i></a>
                                @if (auth()->user()->role_id == 1)  
                                    <button data-url="{{url("/residents/$resident->resident_id/archive")}}" data-type="archive" data-group="resident" class="popup_trigger aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><i class='bx bx-sm bx-archive-in'></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="w-full flex">
            {{$residents->links()}}
        </div>        
    </div>
</x-layout>
