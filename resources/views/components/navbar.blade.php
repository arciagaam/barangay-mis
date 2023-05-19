<div class="absolute top-0 left-0 z-50">
    <nav class="relative flex flex-col min-h-screen h-screen w-16 bg-project-blue text-white z-10 transition-all duration-300 ease-out drop-shadow-lg">

        <div id="chevronContainer" class="absolute flex items-center justify-center right-0 top-4 translate-x-5 rounded-full h-8 w-8 bg-project-yellow text-project-black cursor-pointer transition-all duration-300 ease-out">
            <i id="chevron" class='bx bx-sm bx-chevron-right'></i>
        </div>

        <div id="links-container" class="flex flex-col gap-10 whitespace-nowrap h-full py-10 overflow-x-hidden">

            <a href="#" class="flex items-center gap-5 px-5">
                <img class="object-fit w-full max-w-[5rem]" src="{{$barangayInformation->logo ? url($barangayInformation->logo) : url('images/no_image.png')}}" alt="">
                <p>Barangay {{$barangayInformation->name}}</p>
            </a>

            <div class="flex flex-col gap-5 h-full">

                <a href="{{url('/dashboard')}}" class="pl-5 flex items-center gap-5 {{ request()->is('dashboard*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('dashboard*') ? 's':'' }}-home-alt-2 '></i>
                    <p class="text-sm font-normal">Dashboard</p>
                </a>
    
                <a href="{{url('/residents')}}" class="pl-5 flex items-center gap-5 {{ request()->is('residents*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('residents*') ? 's':'' }}-group'></i>
                    <p class="text-sm font-normal">Residents Information</p>
                </a>

                <a href="{{url('/officials')}}" class="pl-5 flex items-center gap-5 {{ request()->is('officials*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('officials*') ? 's':'' }}-user-pin'></i>
                    <p class="text-sm font-normal">Barangay Officials</p>
                </a>

                <a href="{{url('/blotters')}}" class="pl-5 flex items-center gap-5 {{ request()->is('blotter*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('blotter*') ? 's':'' }}-briefcase'></i>
                    <p class="text-sm font-normal">Blotters</p>
                </a>
    
                <a href="{{url('/certificates')}}" class="pl-5 flex items-center gap-5 {{ request()->is('certificates*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('certificates*') ? 's':'' }}-certification'></i>
                    <p class="text-sm font-normal">Barangay Certificate</p>
                </a>
    
                <a href="{{url('/mapping')}}" class="pl-5 flex items-center gap-5 {{ request()->is('mapping*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('mapping*') ? 's':'' }}-map-alt'></i>
                    <p class="text-sm font-normal">Mapping</p>
                </a>
                
                <a href="{{url('/inventory')}}" class="pl-5 flex items-center gap-5  {{ request()->is('inventory*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('inventory*') ? 's':'' }}-package'></i>
                    <p class="text-sm font-normal">Inventory</p>
                </a>

                <a href="{{url('/lend')}}" class="pl-5 flex items-center gap-5  {{ request()->is('lend*') ? 'text-project-yellow' : '' }}">
                    <span class="material-symbols-outlined lend">
                        real_estate_agent
                    </span>
                    <p class="text-sm font-normal">Lend Items</p>
                </a>

                <div id="maintenance_main" class="pl-5 flex flex-col {{ request()->is('maintenance*') ? 'border-t border-b border-white py-5 ':'' }} transition-all duration-300 ease-in-out">
                    <div id="maintenance" class="flex flex-row items-center gap-5 cursor-pointer">
                        <i class='bx bx-sm bx-wrench'></i>
                        <p class="text-sm font-normal">Maintenance</p>
                        <i id="nav_chevron" class='bx bx-sm bx-chevron-down'></i>
                    </div>

                    <div id="maintenance_links" class="flex flex-col gap-3 overflow-hidden {{ request()->is('maintenance*') ? 'max-h-[500px] mt-5' : 'max-h-0' }} transition-all duration-300 ease-in-out">
                        @if (auth()->user()->role_id == 1)                
                            <a class="flex items-center gap-5 {{ request()->is('maintenance/archive*') ? 'text-project-yellow' : '' }}" href="{{url('/maintenance/archive')}}">
                                <i class='bx bx-sm bx{{ request()->is('maintenance/archive*') ? 's':'' }}-box'></i>
                                <p class="text-sm font-normal">Archive</p>         
                            </a>
                        @endif

                        @if (auth()->user()->role_id == 1)         
                            <a class="flex items-center gap-5 {{ request()->is('maintenance/barangay-information*') ? 'text-project-yellow' : '' }}" href="{{url('/maintenance/barangay-information')}}">
                                <i class='bx bx-sm bx{{ request()->is('maintenance/barangay-information*') ? 's':'' }}-buildings'></i>
                                <p class="text-sm font-normal">Barangay Information</p>         
                            </a>
                        @endif
                        
                        @if (auth()->user()->role_id == 1)                   
                            <a class="flex items-center gap-5 {{ request()->is('maintenance/users*') ? 'text-project-yellow' : '' }}" href="{{url('/maintenance/users')}}">
                                <i class='bx bx-sm bx{{ request()->is('maintenance/users*') ? 's':'' }}-user-plus'></i>
                                <p class="text-sm font-normal">Users</p>         
                            </a>
                        @endif
                        
                        @if (auth()->user()->role_id == 1)   
                            <a class="flex items-center gap-5 {{ request()->is('maintenance/settings*') ? 'text-project-yellow' : '' }}" href="{{url('/maintenance/settings')}}">
                                <i class='bx bx-sm bx{{ request()->is('maintenance/settings*') ? 's':'' }}-cog'></i>
                                <p class="text-sm font-normal">System Variables</p>         
                            </a>
                        @endif

                        <a class="flex items-center gap-5 {{ request()->is('maintenance/audit-trail*') ? 'text-project-yellow' : '' }}" href="{{url('/maintenance/audit-trail')}}">
                            <i class='bx bx-sm bx{{ request()->is('maintenance/audit-trail*') ? 's':'' }}-file-find'></i>
                            <p class="text-sm font-normal">Audit Trail</p>         
                        </a>
                    </div>
                </div>

                <a href="{{url('/profile')}}" class="pl-5 flex items-center gap-5 {{ request()->is('profile*') ? 'text-project-yellow' : '' }}">
                    <i class='bx bx-sm bx{{ request()->is('profile*') ? 's':'' }}-user'></i>
                    <p class="text-sm font-normal">My Account</p>
                </a>

                <a href="{{url('/logout')}}" class="pl-5 flex items-center gap-5 mt-auto hover:text-red-500">
                    <i class='bx bx-sm bx-log-out'></i>
                    <p class="text-sm font-normal">Logout</p>
                </a>

            </div>
    
        </div>
    </nav>    
</div>

@vite('resources/js/nav.js')