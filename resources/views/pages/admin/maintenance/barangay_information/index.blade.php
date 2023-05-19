<x-layout>
    <div class="flex flex-col bg-white flex-1 rounded-md shadow-md py-5 px-5 gap-7">
        <p class="text-lg font-bold">Barangay Information</p>

        <form method="POST" action="{{url("/maintenance/barangay-information/edit")}}" class="flex flex-row h-full gap-5" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col flex-1 gap-5 items-center">
                <div class="aspect-square w-[calc(100%)] overflow-hidden flex items-center justify-center rounded-md">
                    <img id="img_logo" class="w-full object-fill scale-105" src="{{$barangayInformation->logo ? url("$barangayInformation->logo") : url('images/no_image.png')}}" alt="">
                </div>

                <div class="flex">
                    @if ($editing)
                        <label for="logo_input" class="bg-project-yellow text-project-blue font-bold py-2 px-4 rounded-md cursor-pointer">
                            <input id="logo_input" type="file" id="logo" name="logo" class="w-fit hidden" >
                            Change Image
                        </label>                        
                    @endif
                </div>
            </div>

            <div class="flex flex-col gap-5 flex-[2]">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="name">Barangay Name  @if($editing) <span class="text-project-blue/20 text-xs">Do not include the word "Barangay"</span> @endif </label>
                        @error('name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="name" id="name" value="{{$barangayInformation->name}}" {{$editing ? '' : 'disabled'}} >
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="phone_number">Phone Number</label>
                        @error('phone_number')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="phone_number" id="phone_number" value="{{$barangayInformation->phone_number}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="email_address">Email Address</label>
                        @error('email_address')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="email_address" id="email_address" value="{{$barangayInformation->email_address}}" {{$editing ? '' : 'disabled'}}>
                </div>
                @if($editing)
                <div class="flex flex-row self-end justify-self-end gap-3">
                    <a class="secondary-btn" href="{{url('/maintenance/barangay-information/')}}">Cancel</a>
                    <button class="self-end justify-self-end bg-project-yellow text-project-blue font-bold py-2 px-4 rounded-md" href="{{url('/maintenance/barangay-information/edit')}}">Save</button>
                </div>
                @else
                <a class="self-end justify-self-end bg-project-yellow text-project-blue font-bold py-2 px-4 rounded-md" href="{{url('/maintenance/barangay-information/edit')}}">Edit</a>
                @endif
            </div>
        </form>
    </div>
</x-layout>

@vite('resources/js/barangay_information.js')