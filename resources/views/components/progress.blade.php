<div class="flex flex-1 flex-col gap-1">
    <p class=" {{$progress == "active" ? 'text-project-yellow font-bold' : ''}} ml-1 text-sm">{{$label}}</p>
    <div class="min-h-[5px] {{$progress == 'active' ? 'bg-project-yellow' : 'bg-project-blue/30'}} "></div>
</div>