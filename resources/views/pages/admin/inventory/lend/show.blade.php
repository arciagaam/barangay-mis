
<x-layout>
    <form action="{{url("/lend/$item->id/edit")}}" method="POST" class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-7">
        @csrf
        <div class="flex w-full items-center justify-between">
            <p class="text-lg font-bold">Lend ID: {{$item->id}}</p>

            <div class="flex flex-row gap-3">
                @if (!$editing)
                    <a href="{{url("/lend/$item->id/edit")}}" class="primary-btn">Edit Details</a>
    
                    @if ($item->status == 0)
                        <button type="button" id="return_btn" class="primary-btn w-fit self-end">Return Item</button>
                    @else
                        <div class="secondary-btn">Item Returned</div>
                    @endif
                @else
                    <p class="italic text-sm">Editing</p>
                @endif
            </div>
        </div>

        @if($returnedData)
            <div class="flex flex-col gap-5">
                <p class="text-lg font-bold">Status: {{$item->status == 0 ? 'Borrowed' : 'Returned'}}</p>

                <p class="text-lg font-bold">Return Details</p>

                <div class="form-input-container w-full">
                    <div class="flex flex-row justify-between items-center">
                        <label for="returned_quantity">Returned Quantity</label>
                    </div>
                    <input class="form-input" type="text" name="returned_quantity" id="returned_quantity" disabled value="{{$returnedData->returned_quantity}}" disabled>
                </div>

                <div class="form-input-container w-full">
                    <div class="flex flex-row justify-between items-center">
                        <label for="returned_remarks">Return Remarks</label>
                        @error('returned_remarks')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <textarea class="form-input resize-none" name="returned_remarks" id="returned_remarks" cols="30" rows="10" disabled>{{$returnedData->remarks}}</textarea>
                </div>

            </div>
        @endif
        <div class="flex flex-col gap-5">
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
            <div class="flex justify-end gap-3 items-center mt-auto">
                <a class="secondary-btn" href="{{url("/lend/$item->id")}}">Cancel</a>
                <button type="submit" class="primary-btn w-fit">Save</button>
            </div>
        @endif
    </form>
</x-layout>


<div id="return_modal"  class="pl-16 absolute hidden flex inset-0 items-center justify-center min-h-[calc(100%)] min-w-[calc(100%)] flex-col bg-black/20 text-project-blue">
    <div class="flex flex-col w-3/4 min-h-10 bg-white rounded-md p-5 gap-7">
        <div class="flex justify-between">
            <p id="modal_title" class="text-lg font-bold">Return Item</p>
            <i id="close_modal" class='bx bx-sm bxs-x-circle self-end cursor-pointer'></i>
        </div>

        <div class="flex flex-col gap-5">

            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="returned_quantity">Returned Quantity</label>
                    <p id="error_returned_quantity" class="text-xs text-red-500 italic"></p>
                </div>
                <input class="form-input w-1/2" type="number" name="returned_quantity" id="returned_quantity">
            </div>

            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="return_remarks">Remarks</label>
                    <p id="error_return_remarks" class="text-xs text-red-500 italic"></p>
                </div>
                <textarea class="form-input resize-none" name="return_remarks" id="return_remarks" cols="30" rows="10"></textarea>
            </div>

        </div>

        <button id="submit_return" data-id="{{$item->id}}" class="bg-project-yellow font-bold py-2 px-4 rounded-md">Return Item</button>
    </div>
</div>
@vite('resources/js/lend.js')