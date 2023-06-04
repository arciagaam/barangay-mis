<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="active" label="Step Two"/>
            <x-progress progress="inactive" label="Step Three"/>
            <x-progress progress="inactive" label="Complete"/>

        </div>
    </div>

    <div class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-10">

        <div class="flex">
            <p class="text-2xl font-bold">Contact Information</p>
            <p class="text-project-blue/50 ml-auto text-xs italic">Fields with * are required.</p>
        </div>

        <form class="flex flex-col gap-5 h-full" method="POST" action="{{url('residents/new/step-two')}}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="house_number" class="flex gap-2 items-center"> House Number <span class="text-xs text-red-500">*</span></label>
                        @error('house_number')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="house_number" id="house_number" value="{{ old('house_number') ?? ($household->house_number ?? '') }}">
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="street_id" class="flex gap-2 items-center">Street<span class="text-xs text-red-500">*</span></label>
                        @error('street_id')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>

                    <select class="form-input" name="street_id" id="street_id">
                        @php
                            $_streetId = $residentData->street_id ?? null
                        @endphp
                        @foreach ($streets as $street)
                            <option value="{{$street->id}}" {{$_streetId == $street->id ? 'selected' : ''}}>{{$street->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="others">Unit/Bldg/Others</label>
                        @error('others')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="others" id="others" value="{{ old('others') ?? ($household->others ?? '') }}">
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="phone_number" class="flex gap-2 items-center">Phone Number <span class="text-xs text-red-500">*</span></label>
                        @error('phone_number')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="flex border-[1px] border-nav-inactive rounded-md text-small px-3 items-center focus-within:border-project-blue transition-all duration-300 ease-in-out">
                        <p class="border-r pr-3 text-nav-inactive font-bold text-sm">+63</p>
                        <input class="form-input focus:outline-0 pl-3 w-full placeholder-neutral border-none" type="text" id="phone_number" name="phone_number" placeholder="Enter Phone Number" maxlength="10" pattern="[0-9]{10}" value="{{ old('phone_number') ?? ($resident->phone_number ?? '') }}">
                    </div>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="telephone_number">Telephone Number</label>
                        @error('telephone_number')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="text" name="telephone_number" id="telephone_number" pattern="[0-9 +()]+$" value="{{ old('telephone_number') ?? ($resident->telephone_number ?? '') }}">
                </div>
            </div>


            <div class="flex items-center gap-2 self-end mt-auto">
                <a href={{url('/residents/new/step-one')}} class="secondary-btn">Back</a>
                <button type="submit" class="primary-btn">Next</button>
            </div>

        </form>

    </div>
</x-layout>
