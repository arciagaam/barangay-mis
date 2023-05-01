<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="active" label="Step Two"/>
            <x-progress progress="active" label="Step Three"/>
            <x-progress progress="active" label="Complete"/>
        </div>
    </div>

    <div class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-10 items-center justify-center">

            <div class="flex flex-col items-center gap-10">

                <div class="flex flex-col items-center">
                    <i id="complete-check" class='bx bx-lg bx-check-circle'></i>
                    <p class="text-2xl">Certificate Issued!</p>
                </div>

                <div class="flex flex-row">
                    <a class="py-2 px-3 bg-project-yellow text-project-blue font-bold rounded-md" href="{{url('/certificates')}}">Back to Certificates List</a>
                </div>
            </div>

    </div>
</x-layout>

@vite('resources/js/residents.js')
