<x-layout>
    <x-pageheader>Barangay Officials</x-pageheader>

    <div class="flex flex-col gap-3">
        {{-- TABLE ACTIONS --}}
        <div class="flex flex-row w-full justify-between items-center">
            <form id="actions_form" class="flex w-full gap-6 items-center">
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

                <div class="flex gap-2 items-center">
                    <label class="text-sm" for="rows">Term</label>
                    <div class="form-input flex w-fit items-center border border-table-even focus-within:border-project-blue rounded-md overflow-hidden gap-2 px-1 bg-white transition-all duration-300 ease-in-out">
                        <select class="outline-none" name="year" id="year" onchange="this.form.submit()">
                            @foreach ($years as $year)
                                <option value="{{$year->year}}" {{$selectedYear == $year->year ? 'selected' : ''}}>{{$year->year}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <div class="flex flex-row w-full items-center">
                <a href="{{url('/officials/new/step-one')}}" class="ml-auto py-2 px-4 bg-project-yellow text-project-blue rounded-md text-sm flex items-center gap-2 font-bold"><i class='bx bx-xs font-bold bx-plus'></i>Add Official</a>
            </div>
        </div>


        <table class="main-table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Position</th>
                    <th>Term Start</th>
                    <th>Term End</th>
                    <th class="!text-center">Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($officials as $official)
                    <tr>
                        <td>{{$official->first_name}} {{$official->middle_name}} {{$official->last_name}}</td>
                        <td>{{$official->position}}</td>
                        <td>{{$official->term_start}}</td>
                        <td>{{$official->term_end}}</td>
                        <td>
                            <div class="flex flex-row flex-wrap justify-center items-center gap-2">
                                <a href="{{url("/officials/$official->id")}}" class="aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><span class="material-symbols-outlined">visibility</span></a>
                                <a href="{{url("/officials/$official->id/edit")}}" class="aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><i class='bx bx-sm bxs-pencil'></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="w-full flex">
            {{$officials->links()}}
        </div>        
    </div>
</x-layout>

