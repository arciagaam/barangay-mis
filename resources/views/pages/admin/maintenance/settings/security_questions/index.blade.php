<x-layout>

    <x-pageheader>Security Questions</x-pageheader>

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
                <button id="add_security_question" class="ml-auto py-2 px-4 bg-project-yellow text-project-blue rounded-md text-sm flex items-center gap-2 font-bold"><i class='bx bx-xs font-bold bx-plus'></i>Add Security Question</button>
            </div>
        </div>


        <table class="main-table">
            <thead>
                <tr>
                    <th>Position</th>
                    <th class="!text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($security_questions as $sq)
                    <tr>
                        <td>{{ucfirst($sq->name)}}</td>
                        <td>
                            <div class="flex flex-row flex-wrap justify-center items-center gap-2">
                                <button data-id="{{$sq->id}}" data-type="security_questions" class="view_btn aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><i class='bx bx-sm bxs-pencil'></i></button> 
                                <button data-id="{{$sq->id}}" data-route="security_questions" data-url="{{url("api/security_questions/$sq->id/delete")}}" data-type="delete" data-group="security question" class="popup_trigger delete_btn aspect-square rounded-md h-fit flex items-center justify-center p-[.25rem]"><i class='bx bx-sm bx-trash'></i></button>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="w-full flex">
            {{$security_questions->links()}}
        </div>        
    </div>
</x-layout>

<div id="add_modal"  class="pl-16 absolute hidden flex inset-0 items-center justify-center min-h-[calc(100%)] min-w-[calc(100%)] flex-col bg-black/20 text-project-blue">
    <div class="flex flex-col w-3/4 min-h-10 bg-white rounded-md p-5 gap-7">
        <div class="flex justify-between">
            <p id="modal_title" class="text-lg font-bold">Add New Security Question</p>
            <i id="close_modal" class='bx bx-sm bxs-x-circle self-end cursor-pointer'></i>
        </div>

        <div class="flex flex-col gap-5">

            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="name">Security Question</label>
                    <p id="error_name" class="text-xs text-red-500 italic"></p>
                </div>
                <input class="form-input w-1/2" type="text" name="name" id="name">
            </div>

        </div>


        <button id="submit" data-group="security_questions" class="bg-project-yellow font-bold py-2 px-4 rounded-md">Add Security Question</button>

    </div>
</div>

@vite('resources/js/settings.js')