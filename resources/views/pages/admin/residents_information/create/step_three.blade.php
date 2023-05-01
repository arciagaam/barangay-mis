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

        <p class="text-2xl font-bold">Additional Information</p>

        <form class="flex flex-col gap-5 h-full" method="POST" action="{{url('residents/new/step-three')}}">
            @csrf
            <div class="flex flex-col gap-5">

                <div class="flex flex-col gap-2">
                    <p class="font-bold text-lg">Disability</p>

                    <div class="form-input-container flex-row gap-5">
                        <div class="flex flex-row justify-between items-center">
                            <label for="disabled">Disabled</label>

                            @error('disabled')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input type="checkbox" name="disabled" id="disabled">
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

                        <select class="form-input w-fit" name="voter_status" id="voter_status">
                            <option value="0">Unregistered</option>
                            <option value="1">Registered</option>
                        </select>
                    </div>

                    <div class="form-input-container gap-1 hidden">
                        <div class="flex flex-row justify-between items-center">
                            <label for="preinct_number">Precint Number</label>
                            @error('preinct_number')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>

                        <input class="form-input w-1/2" type="text" name="precinct_number" id="precinct_number">
                    </div>

                </div>

            </div>


            <div class="flex items-center gap-2 self-end mt-auto">
                <a href="" class="py-2 px-4 bg-table-even text-project-blue/40 rounded-md">Cancel</a>
                <button type="submit" class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md">Next</button>
            </div>

        </form>

    </div>
</x-layout>

@vite('resources/js/residents.js')
