<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="active" label="Step Two"/>
            <x-progress progress="inactive" label="Complete"/>
        </div>
    </div>

    <div class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-10">

        <p class="text-2xl font-bold">Position</p>

        <form class="flex flex-col gap-5 h-full" method="POST" action="{{url('officials/new/step-two')}}">
            @csrf
            <div class="flex flex-col gap-5">

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="position_id">Position</label>
                        @error('position_id')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <select class="form-input w-fit" name="position_id" id="position_id">
                        @foreach ($positions as $position)
                            <option value="{{$position->id}}">{{ucfirst($position->name)}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-5">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center w-fit gap-3">
                            <label for="term_start">Term Start</label>
                            @error('term_start')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input w-fit" type="date" name="term_start" id="term_start">
                    </div>
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center w-fit gap-3">
                            <label for="term_end">Term End</label>
                            @error('term_end')
                                <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input w-fit" type="date" name="term_end" id="term_end">
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

