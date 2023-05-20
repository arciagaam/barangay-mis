<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="inactive" label="Complete"/>

        </div>
    </div>

    <form method="POST" action="{{url('/inventory/new/step-one')}}" class="flex flex-col bg-white flex-1 rounded-md shadow-md py-5 px-5 gap-10 h-full">
        @csrf

        <div class="flex flex-col gap-3">
            
            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="name">Item Name <span class="text-xs text-red-500">*</span></label>
                    @error('name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
                <input class="form-input" type="text" name="name" id="name">
            </div>
    
            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="quantity">Quantity <span class="text-xs text-red-500">*</span></label>
                    @error('quantity')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
                <input class="form-input" type="number" name="quantity" min="1" id="quantity">
            </div>
    
            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="remarks">Remarks</label>
                    @error('remarks')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
                <textarea class="form-input resize-none" name="remarks" id="remarks" cols="30" rows="6"></textarea>
            </div>
        </div>
        

        <div class="flex flex-row self-end gap-3 mt-auto">
            <a href="{{url('/inventory')}}" class="secondary-btn">Cancel</a>
            <button class="primary-btn">Next</button>
        </div>
        
    </form>    
</x-layout>