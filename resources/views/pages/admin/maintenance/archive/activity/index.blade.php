<x-layout>

    <x-pageheader>Archived Inventory</x-pageheader>

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
                    <div class="flex w-10 items-center border border-table-even focus-within:border-project-blue rounded-md overflow-hidden gap-2 px-1 bg-white transition-all duration-300 ease-in-out">
                        <input class="w-full outline-none px-1 text-sm py-1" type="text" name="rows" id="rows" value={{ request()->query()['rows'] ?? 10 }}>
                    </div>
                </div>
            </form>
        </div>


        <table class="main-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Activity</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Archive Reason</th>
                    <th class="!text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
                    <tr>
                        <td>{{$activity->id}}</td>
                        <td>{{$activity->name}}</td>
                        <td>{{$activity->start_date}}</td>
                        <td>{{$activity->end_date ?? 'N/A'}}</td>
                        <td>{{$activity->reason ?? 'N/A'}}</td>
                        <td>
                            <div class="flex flex-row flex-wrap justify-center items-center gap-2">
                                {{-- <a href="{{url("/residents/$resident->resident_id")}}" class="aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><i class='bx bx-sm bx-search-alt-2'></i></a> --}}
                                <a href="{{url("/calendar/activity/$activity->id/recover")}}" class="aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><i title="Recover from archive" class='bx bx-sm bx-undo'></i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="w-full flex">
            {{$activities->links()}}
        </div>        
    </div>
</x-layout>