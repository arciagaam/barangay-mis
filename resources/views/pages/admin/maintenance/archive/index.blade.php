<x-layout>

    <x-pageheader>Archives</x-pageheader>

    <div class="grid grid-cols-3 w-full gap-5">
        <a href="{{url('/maintenance/archive/residents')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bxs-group' ></i>
            <p class="text-lg">Residents</p>
        </a>

        <a href="{{url('/maintenance/archive/inventory')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bxs-package' ></i>
            <p class="text-lg">Inventory</p>
        </a>

        <a href="{{url('/maintenance/archive/mapping')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bxs-map-alt' ></i>
            <p class="text-lg">Mapping</p>
        </a>

        <a href="{{url('/maintenance/archive/activity')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bxs-calendar-x'></i>
            <p class="text-lg">Activity</p>
        </a>
    </div>
</x-layout>