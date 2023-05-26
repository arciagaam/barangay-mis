<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="active" label="Step Two"/>
            <x-progress progress="active" label="Step Three"/>
            <x-progress progress="inactive" label="Complete"/>
        </div>
    </div>

    <div class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-10">

        <div class="flex">
            <p class="text-2xl font-bold">Additional Information</p>
            <p class="text-project-blue/50 ml-auto text-xs italic">Fields with * are required.</p>
        </div>

        <form class="flex flex-col gap-5 h-full" method="POST" action="{{url('residents/new/step-three')}}">
            @csrf
            <div class="flex flex-col gap-5">

                <div class="flex flex-col gap-2">
                    <div class="form-input-container flex-row gap-5">
                        <div class="flex flex-row justify-between items-center">
                            <label for="disabled">Disabled</label>

                            @error('disabled')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input type="checkbox" name="disabled" id="disabled">
                    </div>

                    <div class="form-input-container flex-row gap-5">
                        <div class="flex flex-row justify-between items-center">
                            <label for="single_parent">Single Parent</label>

                            @error('single_parent')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input type="checkbox" name="single_parent" id="single_parent">
                    </div>

                </div>

                <div class="flex min-h-[1px] h-[1px] bg-table-even rounded-md"></div>

                <div class="flex flex-col gap-2">
                    <p class="font-bold text-lg">Voter Information</p>

                    <div class="form-input-container gap-1">
                        <div class="flex flex-row justify-between items-center">
                            <label for="voter_status">Voter Status</label>
                            @error('voter_status')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>

                        @php
                            $status = old('voter_status') ?? ($resident->voter_status ?? '')
                        @endphp

                        <select class="form-input w-fit" name="voter_status" id="voter_status">
                            <option value="0" {{$status == 0 ? 'selected' : ''}}>Unregistered</option>
                            <option value="1" {{$status == 1 ? 'selected' : ''}}>Registered</option>
                        </select>
                    </div>

                    <div class="form-input-container gap-1 hidden">
                        <div class="flex flex-row justify-between items-center">
                            <label for="preinct_number">Precinct Number</label>
                            @error('preinct_number')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>

                        <input class="form-input w-1/2" type="text" name="precinct_number" id="precinct_number">
                    </div>

                </div>

            </div>


            <div class="flex items-center gap-2 self-end mt-auto">
                <a href={{url('/residents/new/step-two')}} class="secondary-btn">Back</a>
                <button type="submit" class="primary-btn">Next</button>
            </div>

        </form>

    </div>
</x-layout>

@vite('resources/js/residents.js')
