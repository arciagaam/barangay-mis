<x-layout>

    <x-pageheader>Certificates</x-pageheader>

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
                <a href="{{url('/certificates/new/step-one')}}" class="ml-auto py-2 px-4 bg-project-yellow text-project-blue rounded-md text-sm flex items-center gap-2 font-bold"><i class='bx bx-xs font-bold bx-plus'></i>Issue Certificate</a>
            </div>
        </div>


        <table class="main-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Certificate Type</th>
                    <th>Date Issued</th>
                    <th class="!text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($certificates as $certificate)
                    <tr>
                        <td>{{$certificate->first_name}} {{$certificate->middle_name}} {{$certificate->last_name}}</td>
                        <td>{{$certificate->certificate_type}}</td>
                        <td>{{$certificate->created_at}}</td>
                        <td>
                            <div class="flex flex-row flex-wrap justify-center items-center gap-2">
                                <form target="_blank" action="{{url('/certificates/print')}}" method="POST">
                                    @csrf
                                    <input class="flex items-center justify-center" type="hidden" id="certificate_id" name="certificate_id" value="{{$certificate->id}}">
                                    <input type="hidden" id="certificate_type_id" name="certificate_type_id" value="{{$certificate->certificate_type_id}}">
                                    <button title="Print" type="submit" class="flex items-center justify-center" href="{{url('/certificates')}}"><i class='bx bx-sm bx-printer'></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="w-full flex">
            {{$certificates->links()}}
        </div>        
    </div>
</x-layout>