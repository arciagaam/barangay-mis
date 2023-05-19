<div id="warning_modal" class="pl-16 absolute hidden flex inset-0 items-center justify-center min-h-[calc(100%)] min-w-[calc(100%)] flex-col bg-black/20 text-project-blue z-50">
    <div class="flex flex-col min-w-[400px] min-h-10 bg-white rounded-md p-5 gap-7 items-center">
        <p id="confirm_text" class="text-xl"></p>
        @isset($reasons)            
            <div id="archive_input" class="flex flex-col hidden w-full">
                <div class="form-input-container w-full">
                    <div class="flex flex-row justify-between items-center w-full">
                        <label for="reason">Reason for archiving <span class="text-xs text-red-500">*</span></label>
                        @error('reason')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <select class="form-input" name="reason" id="reason">
                        @foreach ($reasons as $reason)
                            <option value="{{$reason->id}}">{{$reason->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endisset

        <div class="flex flex-row mt-auto gap-3">
            <button id="cancel" data-group="" class="secondary-btn">Cancel</button>
            <button id="submit" data-group="" class="bg-project-yellow font-bold py-2 px-4 rounded-md">Continue</button>
        </div>
    </div>
</div>

@vite('resources/js/popup.js')