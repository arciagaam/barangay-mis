
<x-layout>
    <form action="{{url("/inventory/$item->id/edit")}}" method="POST" class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-7">
        @csrf
        <div class="flex w-full items-center justify-between">
            <p class="font-bold text-xl">{{$item->name}}</p>
            @if (!$editing)
                <a href="{{url("/inventory/$item->id/edit")}}" class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md">Edit</a>
            @else
                <p class="italic text-sm">Editing</p>
            @endif
        </div>

        <div class="flex flex-col gap-5">
            <div class="flex flex-col gap-3">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="name" >Item Name</label>
                        @error('name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="name" id="name" value="{{$item->name}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="quantity" >Quantity</label>
                        @error('quantity')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="number" name="quantity" id="quantity" value="{{$item->quantity}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="remarks" >Remarks</label>
                        @error('remarks')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <textarea class="form-input" name="remarks" id="remarks" cols="30" rows="10"{{$editing ? '' : 'disabled'}}>{{$item->remarks}}</textarea>
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