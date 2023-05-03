
<x-layout>
    <form action="{{url("/inventory/lend/$item->id/edit")}}" method="POST" class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-7">
        @csrf
        <div class="flex w-full items-center justify-between">
            <p class="text-lg font-bold">Lend ID: {{$item->id}}</p>

            <div class="flex flex-row gap-3">
                @if (!$editing)
                    <a href="{{url("/inventory/lend/$item->id/edit")}}" class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md">Edit Details</a>
    
                    @if ($item->status == 0)
                        <a href="{{url("/inventory/lend/$item->id/return")}}" class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md w-fit self-end">Return Item</a>
                    @else
                        <div class="py-2 px-4 bg-table-even text-project-blue/40 rounded-md">Item Returned</div>

                    @endif
                @else
                    <p class="italic text-sm">Editing</p>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-5">
            <p>Status: {{$item->status == 0 ? 'Borrowed' : 'Returned'}}</p>
            <div class="flex flex-col gap-3">
    
                <p class="text-lg font-bold">Item Details</p>
                <div class="flex flex-row gap-3">
                    <div class="form-input-container w-full">
                        <div class="flex flex-row justify-between items-center">
                            <label for="name">Item Name</label>
                            @error('name')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="name" id="name" disabled value="{{$item->item_name}}">
                    </div>
                </div>
                
        
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="remarks">Remarks</label>
                        @error('remarks')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <textarea class="form-input resize-none" name="remarks" id="item_remarks" cols="30" rows="6" disabled>{{$item->item_remarks}}</textarea>
                </div>
    
            </div>

            <div class="flex flex-col gap-3">
                <p class="font-bold text-lg">Borrower Information</p>
    
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="first_name">First Name</label>
                        </div>
                        <input class="form-input" type="text" name="first_name" id="first_name" value="{{$item->first_name ?? ''}}" disabled>
                    </div>
        
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="middle_name">Middle Name</label>
                        </div>
                        <input class="form-input" type="text" name="middle_name" id="middle_name" value="{{$item->middle_name ?? ''}}" disabled>
                    </div>
        
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="last_name">Last Name</label>
                        </div>
                        <input class="form-input" type="text" name="last_name" id="last_name" value="{{$item->last_name ?? ''}}" disabled>
                    </div>
    
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <p class="text-lg text-project-blue font-bold">Additional Details</p>
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="quantity">Quantity</label>
                    </div>
                    <input class="form-input w-1/2" type="number" name="quantity" id="quantity" disabled value="{{$item->quantity}}">
                </div>
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="remarks">Remarks</label>
                        @error('remarks')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                    </div>
    
                    <textarea class="form-input w-1/2 resize-none" name="remarks" id="remarks" cols="30" rows="10" {{$editing ? '' : 'disabled'}}>{{$item->remarks ?? 'N/A'}}</textarea>
                </div>
    
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="contact">Contact</label>
                        @error('contact')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="number" name="contact" id="contact" value="{{$item->contact}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="flex flex-row w-full gap-3">

                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="lend_date">Lend Date</label>
                            @error('lend_date')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="date" name="lend_date" id="lend_date" value="{{explode(' ', $item->lend_date)[0]}}" disabled>
                    </div>

                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="return_date">Return Date</label>
                            @error('return_date')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="date" name="return_date" id="return_date" value="{{$item->return_date}}" {{$editing ? '' : 'disabled'}}>
                    </div>
                </div>



            </div>
        </div>
        @if ($editing)
            <div class="flex justify-end gap-5 items-center mt-auto">
                <a class="py-2 px-4 bg-table-even text-project-blue/50 rounded-md w-fit" href="{{url("/inventory/$item->id")}}">Cancel</a>
                <button type="submit" class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md w-fit">Save</button>
            </div>
        @endif
    </form>
</x-layout>