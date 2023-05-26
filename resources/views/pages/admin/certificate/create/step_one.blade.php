<x-layout>
        <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
            <div class="flex self-center w-[80%]">
                <x-progress progress="active" label="Step One"/>
                <x-progress progress="inactive" label="Step Two"/>
                <x-progress progress="inactive" label="Step Three"/>
                <x-progress progress="inactive" label="Complete"/>
    
            </div>
        </div>
    
        <form method="POST" action="{{url('/certificates/new/step-one')}}" class="flex flex-col bg-white flex-1 rounded-md shadow-md py-5 px-5 gap-10 h-full items-center justify-center">
            @csrf
            <div class="flex h-full flex-col items-center justify-center">
                <p>Select Certificate</p>

                <select class="form-input w-fit" name="certificate_type_id" id="certificate_type_id">
                    @foreach ($certificate_types as $certificate_type)
                        <option value="{{$certificate_type->id}}">{{$certificate_type->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-row self-end mt-auto gap-3">
                <a href="{{url('/certificates')}}" class="secondary-btn">Cancel</a>
                <button class="primary-btn">Next</button>
            </div>
            
        </form>    
</x-layout>