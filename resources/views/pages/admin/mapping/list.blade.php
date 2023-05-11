
<x-layout>
    <x-pageheader>Manage Mappings</x-pageheader>

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
                    <th>Name</th>
                    <th>Address</th>
                    <th>Longitude</th>
                    <th>Latitude</th>
                    <th class="!text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mappings as $mapping)

                    @php
                        $address = 
                        ($mapping->block ? "Blk $mapping->block" : '') . 
                        ($mapping->lot ? " Lot $mapping->lot" : '') . 
                        ($mapping->block || $mapping->block ? " " : '') .
                        $mapping->house_number . 
                        ($mapping->others ? " $mapping->others" : '' ) . 
                        ($mapping->subdivision ? ", $mapping->subdivision" : '') 
                    @endphp

                    <tr>
                        <td>{{$mapping->first_name}} {{$mapping->middle_name}} {{$mapping->last_name}}</td>
                        <td>{{$address}}</td>
                        <td>{{$mapping->longitude ?? 'N/A'}}</td>
                        <td>{{$mapping->latitude ?? 'N/A'}}</td>
                        <td>
                            <div class="flex flex-row flex-wrap justify-center items-center gap-2">
                                <button data-longitude='{{$mapping->longitude}}' data-latitude='{{$mapping->latitude}}' data-resident='{{$mapping->resident_id}}' data-mapping='{{$mapping->id ?? ''}}' class="mapping_btn flex justify-center items-center"><i class='bx bx-sm bx-search-alt-2'></i></button>
                                <a href="{{url("/mapping/$mapping->id/archive")}}" class="aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><i class='bx bx-sm bx-archive-in'></i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="w-full flex">
            {{$mappings->links()}}
        </div>        
    </div>

    <div id="map_modal" class="absolute inset-0 invisible items-center justify-center py-10 px-5 bg-black/30 z-0">
        <div class="flex flex-col gap-5 bg-white min-h-[100%] w-3/4 rounded-md p-4 shadow-md">
            <i id="close_map" class='bx bx-sm bxs-x-circle self-end cursor-pointer'></i>
            <div class="flex flex-1 min-h-full min-w-full rounded-md overflow-hidden">
                <div id="view_map" class="min-h-full min-w-[calc(100%)] bg-blue-500"></div>
            </div>
    
            <div class="flex flex-row gap-5 items-center">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="longitude">Longitude</label>
                    </div>
                    <input class="form-input" type="text" name="longitude" id="longitude" disabled>
                </div>
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="latitude">Latitude</label>
                    </div>
                    <input class="form-input" type="text" name="latitude" id="latitude" disabled>
                </div>
            </div>
        </div>
    </div>
    
    @vite('resources/js/mapping.js')
</x-layout>