<div id="warning_modal" class="pl-16 absolute hidden flex inset-0 items-center justify-center min-h-[calc(100%)] min-w-[calc(100%)] flex-col bg-black/20 text-project-blue z-50">
    <div class="flex flex-col min-w-[400px] min-h-10 bg-white rounded-md p-5 gap-7 items-center">
        <p class="text-xl">Confirm Delete?</p>

        <div class="flex flex-row mt-auto gap-3">
            <button id="cancel" data-group="civil_status" class="bg-project-yellow font-bold py-2 px-4 rounded-md">Cancel</button>
            <button id="submit" data-group="civil_status" class="bg-project-yellow font-bold py-2 px-4 rounded-md">Continue</button>
        </div>
    </div>
</div>

@vite('resources/js/popup.js')