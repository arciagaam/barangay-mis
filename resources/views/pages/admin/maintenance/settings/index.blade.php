<x-layout>

    <x-pageheader>System Variables</x-pageheader>

    <div class="grid grid-cols-3 w-full gap-5">
        <a href="{{url('/maintenance/settings/positions')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bxs-user-badge' ></i>
            <p class="text-lg">Barangay Official Positions</p>
        </a>

        <a href="{{url('/maintenance/settings/civil-status')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bxs-universal-access' ></i>
            <p class="text-lg">Civil Status</p>
        </a>

        <a href="{{url('/maintenance/settings/occupations')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bxs-briefcase-alt-2' ></i>
            <p class="text-lg">Occupations</p>
        </a>


        <a href="{{url('/maintenance/settings/religions')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bxs-church' ></i>
            <p class="text-lg">Religions</p>
        </a>

        <a href="{{url('/maintenance/settings/security-questions')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bx-question-mark'></i>
            <p class="text-lg">Security Questions</p>
        </a>

        <a href="{{url('/maintenance/settings/sex')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bx-male-female'></i>
            <p class="text-lg">Sex</p>
        </a>

        <a href="{{url('/maintenance/settings/archive-reasons')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bxs-bookmark-plus'></i>
            <p class="text-lg">Archive Reasons</p>
        </a>

        
        <a href="{{url('/maintenance/settings/streets')}}" class="flex gap-3 items-center bg-white p-5 rounded-md">
            <i class='bx bx-lg bx-street-view'></i>
            <p class="text-lg">Streets</p>
        </a>

    </div>
</x-layout>