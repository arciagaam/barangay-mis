<x-layout>

    <x-pageheader>Lent Items</x-pageheader>

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

            <div class="flex flex-row items-center whitespace-nowrap gap-3">
                <a href="{{url('lend/new/step-one')}}" class="ml-auto py-2 px-4 bg-project-yellow text-project-blue rounded-md text-sm flex items-center gap-2 font-bold"><i class='bx bx-xs font-bold bxs-hand'></i>Lend Item to Resident</a>
            </div>
        </div>


        <table class="main-table">
            <thead>
                <tr>
                    <th>Resident Name</th>
                    <th>Contact Details</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th class="!text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lent_items as $item)

                    <tr>
                        <td>{{$item->first_name}} {{$item->middle_name}} {{$item->last_name}}</td>
                        <td>{{$item->contact}}</td>
                        <td>{{$item->item_name}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->status == 0 ? 'Borrowing' : 'Returned'}}</td>
                        <td>
                            <div class="flex flex-row flex-wrap justify-center items-center gap-2">
                                <a href="{{url("/lend/$item->id")}}" class="aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><span class="material-symbols-outlined">visibility</span></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="w-full flex">
            {{-- {{$certificates->links()}} --}}
        </div>        
    </div>
</x-layout>