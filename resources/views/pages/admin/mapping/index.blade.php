<x-layout>

    <x-pageheader>Mapping</x-pageheader>

    <div class="flex flex-row gap-5 justify-end items-center">
        <a href="{{url('/mapping/list')}}" class="py-2 px-4 border-2 border-project-blue text-project-blue rounded-md font-bold">Search</a>
        <a href="{{url('/mapping/new')}}" class="py-2 px-4 bg-project-yellow border-2 border-project-yellow text-project-blue rounded-md font-bold">Manage Mappings</a>
    </div>

    <div class="flex flex-1 w-full bg-white shadow-md rounded-md overflow-hidden">
        <div id="map" class="w-[calc(100%)] h-[calc(100%)] bg-blue-200 z-0">

        </div>
    </div>
</x-layout>

@vite('resources/js/mapping.js')