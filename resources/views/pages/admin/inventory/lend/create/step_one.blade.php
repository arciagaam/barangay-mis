<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="inactive" label="Complete"/>

        </div>
    </div>

    <form method="POST" action="{{url('/lend/new/step-one')}}" class="flex flex-col bg-white flex-1 rounded-md shadow-md py-5 px-5 gap-7">
        @csrf

        @if ($item)
            <input type="hidden" name="id" id="id" value="{{$item->id}}">
        @endif

        <p class="text-red-500 italic text-sm">{{session()->get('error')}}</p>
        <div class="flex flex-col gap-2">    
            <div class="flex flex-col gap-3">
    
                <p class="text-lg font-bold">Inventory</p>

                <div class="form-input-container w-full">
                    <div class="flex flex-row justify-between items-center">
                        <label for="id">Items <span class="text-xs text-red-500">*</span></label>
                        @error('id')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>

                    @php
                        if(isset($item)) {
                            $_item = $item;
                        }
                    @endphp

                    <select class="form-input" name="id" id="inventory_id">
                        @foreach ($inventory as $item)
                            <option value="{{$item->id}}" {{isset($_item) ? ($_item->id == $item->id ? 'selected' : '') : ''}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-row gap-3">
                    <div class="form-input-container w-full">
                        <div class="flex flex-row justify-between items-center">
                            <label for="name">Item Name</label>
                            @error('name')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="name" id="name" disabled>
                    </div>
            
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="quantity">Stocks Left</label>
                            @error('quantity')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="number" name="quantity" min="1" id="item_quantity" disabled>
                    </div>
                </div>
                
        
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="remarks">Remarks</label>
                        @error('remarks')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <textarea class="form-input resize-none" name="remarks" id="item_remarks" cols="30" rows="6" disabled></textarea>
                </div>
    
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <div class="flex gap-3">
                <div class="flex gap-2 w-1/2 items-center">
                    <label class="text-sm" for="search">Search</label>
                    <div class="relative w-full">
                        <div class="flex items-center border w-full border-table-even focus-within:border-project-blue rounded-md overflow-hidden gap-2 px-1 bg-white transition-all duration-300 ease-in-out">
                            <input id="resident_search" class="w-full outline-none px-1 text-sm py-1" type="text" name="search" id="search" placeholder="Search for Resident">
                            <button class="w-fit h-fit aspect-square flex items-center justify-center"><i class='bx bx-search'></i></button>
                        </div>
                        <div id="search_names_container" class="absolute top-[calc(100% + 2em)] flex flex-col gap-1 bg-white rounded-md shadow-md w-full p-2 hidden">
                        </div>
                    </div>
                </div>
    
                <button type="button" class="py-2 px-4 bg-project-yellow text-project-blue rounded-md text-sm">Search</button>
            </div>
    
            <div class="flex flex-col gap-3">
                <p class="font-bold text-lg">Borrower Information <span class="text-xs text-red-500">*</span></p>
    
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="first_name">First Name</label>
                        </div>
                        <input class="form-input" type="text" name="first_name" id="first_name" value="{{$residentData->first_name ?? ''}}" disabled>
                    </div>
        
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="middle_name">Middle Name</label>
                        </div>
                        <input class="form-input" type="text" name="middle_name" id="middle_name" value="{{$residentData->middle_name ?? ''}}" disabled>
                    </div>
        
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="last_name">Last Name</label>
                        </div>
                        <input class="form-input" type="text" name="last_name" id="last_name" value="{{$residentData->last_name ?? ''}}" disabled>
                    </div>
        
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="nickname">Nickname</label>
                        </div>
                        <input class="form-input" type="text" name="nickname" id="nickname" value="{{$residentData->nickname ?? ''}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="sex">Sex</label>
                        </div>
                        <input class="form-input" type="text" name="sex" id="sex" value="{{$residentData->sex ?? ''}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="birth_date">Birth Date</label>
                        </div>
                        <input class="form-input" type="text" name="birth_date" id="birth_date" value="{{$residentData->birth_date ?? ''}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="age">Age</label>
                        </div>
                        <input class="form-input" type="text" name="age" id="age" value="{{$residentData->age ?? ''}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="place_of_birth">Place of Birth</label>
                        </div>
                        <input class="form-input" type="text" name="place_of_birth" id="place_of_birth" value="{{$residentData->place_of_birth ?? ''}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="occupation">Occupation</label>
                        </div>
                        <input class="form-input" type="text" name="occupation" id="occupation" value="{{$residentData->occupation ?? ''}}" disabled>
                    </div>
    
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="religion">Religion</label>
                        </div>
                        <input class="form-input" type="text" name="religion" id="religion" value="{{$residentData->religion ?? ''}}" disabled>
                    </div>
                </div>
            </div>


        </div>

        <div class="flex flex-col gap-2">
            <p class="text-lg text-project-blue font-bold">Additional Details</p>

            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="quantity">Quantity <span class="text-xs text-red-500">*</span></label>
                    @error('quantity')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
                <input class="form-input w-1/2" type="number" name="quantity" id="quantity">
            </div>

            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="remarks">Remarks</label>
                    @error('remarks')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>

                <textarea class="form-input w-1/2 resize-none" name="remarks" id="remarks" cols="30" rows="10"></textarea>
            </div>

            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="contact">Contact Number <span class="text-xs text-red-500">*</span></label>
                    @error('contact')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
                <input class="form-input w-1/2" type="number" name="contact" id="contact">
            </div>

            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="return_date">Return Date <span class="text-xs text-red-500">*</span></label>
                    @error('return_date')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
                <input class="form-input w-1/2" type="date" name="return_date" id="return_date">
            </div>
        </div>

        <div class="flex flex-row self-end mt-auto gap-3">
            <a href={{url('/lend')}} class="secondary-btn">Cancel</a>
            <button class="primary-btn">Next</button>
        </div>
        
    </form>    
</x-layout>

@vite('resources/js/searchresident.js')
@vite('resources/js/searchitem.js')