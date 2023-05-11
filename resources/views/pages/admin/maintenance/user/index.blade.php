<x-layout>

    <x-pageheader>Users</x-pageheader>

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

            <div class="flex flex-row w-full items-center">
                <a href="{{url('maintenance/users/new/step-one')}}" class="ml-auto py-2 px-4 bg-project-yellow text-project-blue rounded-md text-sm flex items-center gap-2 font-bold"><i class='bx bx-xs font-bold bx-plus'></i>Add User</a>
            </div>
        </div>


        <table class="main-table">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th class="!text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{$user->first_name}}</td>
                        <td>{{$user->middle_name ?? 'N/A'}}</td>
                        <td>{{$user->last_name}}</td>
                        <td>{{$user->username}}</td>
                        <td>{{ucfirst($user->role)}}</td>
                        <td>
                            <div class="flex flex-row flex-wrap justify-center items-center gap-2">
                                <a href="{{url("/maintenance/users/$user->id")}}" class="aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><i class='bx bx-sm bx-search-alt-2'></i></a>
                                {{-- <a href="{{url("/residents/$resident->resident_id/archive")}}" class="aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><i class='bx bx-sm bx-archive-in'></i></a> --}}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="w-full flex">
            {{$users->links()}}
        </div>        
    </div>
</x-layout>