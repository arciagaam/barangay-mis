<div class="absolute top-0 left-0">
    <nav class="relative flex flex-col min-h-screen h-screen w-16 bg-project-blue text-white px-5 py-5 z-10 transition-all duration-300 ease-out">

        <div id="chevronContainer" class="absolute flex items-center justify-center right-0 top-4 translate-x-5 rounded-full h-8 w-8 bg-project-yellow text-project-black cursor-pointer transition-all duration-300 ease-out">
            <i id="chevron" class='bx bx-sm bx-chevron-right'></i>
        </div>

        {{-- <div class="flex flex-row">
            <p>LOGO</p>
            <p>BARANGAY NAME</p>
        </div> --}}

        <div class="flex flex-col gap-10 whitespace-nowrap h-full overflow-hidden">

            <a href="#" class="flex items-center gap-5">
                <i class='bx bx-sm bx-home-alt-2'></i>
                <p>BARANGAY</p>
            </a>

            <div class="flex flex-col gap-5 h-full">

                <a href="{{url('/dashboard')}}" class="flex items-center gap-5 {{ request()->is('dashboard*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('dashboard*') ? 's':'' }}-home-alt-2 '></i>
                    <p class="text-sm font-normal">Dashboard</p>
                </a>
    
                <a href="{{url('/residents')}}" class="flex items-center gap-5 {{ request()->is('residents*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('residents*') ? 's':'' }}-group'></i>
                    <p class="text-sm font-normal">Residents Information</p>
                </a>

                <a href="{{url('/blotter')}}" class="flex items-center gap-5 {{ request()->is('blotter*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('blotter*') ? 's':'' }}-briefcase'></i>
                    <p class="text-sm font-normal">Blotters</p>
                </a>
    
                <a href="{{url('/certificates')}}" class="flex items-center gap-5 {{ request()->is('certificates*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('certificates*') ? 's':'' }}-certification'></i>
                    <p class="text-sm font-normal">Barangay Certificate</p>
                </a>
    
                <a href="#" class="flex items-center gap-5 {{ request()->is('mapping*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('mapping*') ? 's':'' }}-map-alt'></i>
                    <p class="text-sm font-normal">Mapping</p>
                </a>
    
                <a href="#" class="flex items-center gap-5 {{ request()->is('inventory*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('inventory*') ? 's':'' }}-package'></i>
                    <p class="text-sm font-normal">Inventory</p>
                </a>
    
                <a href="#" class="flex items-center gap-5 mt-auto hover:text-red-500">
                    <i class='bx bx-sm bx-log-out'></i>
                    <p class="text-sm font-normal">Logout</p>
                </a>

            </div>
    
        </div>
    </nav>    
</div>

@vite('resources/js/nav')