@php
    $id = $certificate->certificate_type_id;
@endphp

<x-layout>
    <div class="flex flex-col bg-white rounded-md shadow-md py-5 px-2 gap-10">
        <div class="flex self-center w-[80%]">
            <x-progress progress="active" label="Step One"/>
            <x-progress progress="active" label="Step Two"/>
            <x-progress progress="active" label="Step Three"/>
            <x-progress progress="inactive" label="Complete"/>
        </div>
    </div>

    <form method="POST" action="{{url('/certificates/new/step-three')}}">
        @csrf
        <div class="flex flex-col bg-white flex-1 rounded-md shadow-md py-5 px-5 gap-7">
            @if ($id == 1)
                {{-- @include('pages.admin.certificate.create.certificate_types.business_permit') --}}
                @include('pages.admin.certificate.create.certificate_types.barangay_clearance')
            @elseif ($id == 2)
                {{-- @include('pages.admin.certificate.create.certificate_types.barangay_clearance') --}}
                @include('pages.admin.certificate.create.certificate_types.certificate_of_indigency')
            {{-- @elseif ($id == 3)
                @include('pages.admin.certificate.create.certificate_types.certificate_of_indigency') --}}
            @endif

            <div class="flex flex-row self-end mt-auto gap-3">
                <a href="{{url('/certificates/new/step-two')}}" class="secondary-btn">Back</a>
                <button class="primary-btn">Next</button>
            </div>
        </div>

    </form>
</x-layout>