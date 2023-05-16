
<x-layout>
    
    <div class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-7">
        
        <form href="{{url("/profile/edit")}}" method="POST" class="flex flex-col gap-7 h-full">
            @csrf
            <div class="flex w-full items-center justify-between">
                <p class="font-bold text-xl">My Profile</p>
                @if (!$editing)
                    <a href="{{url("/profile/edit")}}" class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md">Edit</a>
                @else
                    <p class="italic text-sm">Editing</p>
                @endif
            </div>
    
            <div class="flex flex-col gap-5">
                <p class="text-lg font-bold">Personal Details</p>
    
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="first_name" >First Name</label>
                            @error('first_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="first_name" id="first_name" value="{{$user->first_name}}" {{$editing ? '' : 'disabled'}}>
                    </div>
                </div>
    
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="middle_name" >Middle Name</label>
                            @error('middle_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="middle_name" id="middle_name" value="{{$user->middle_name}}" {{$editing ? '' : 'disabled'}}>
                    </div>
                </div>
    
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="last_name" >Last Name</label>
                            @error('last_name')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="last_name" id="last_name" value="{{$user->last_name}}" {{$editing ? '' : 'disabled'}}>
                    </div>
                </div>
            </div>
    
            <div class="flex flex-col gap-5">
                <p class="text-lg font-bold">Account Details</p>
    
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="role_id">Role</label>
                            @error('role_id')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
    
                        <input class="form-input" type="text" name="role_id" id="role_id" value="{{ucfirst($user->role)}}" disabled>
                    </div>
                </div>
    
                <div class="grid grid-cols-3 gap-3">
                    <div class="form-input-container">
                        <div class="flex flex-row justify-between items-center">
                            <label for="username">Username</label>
                            @error('username')
                            <p class="text-xs text-red-500 italic">{{$message}}</p>
                            @enderror
                        </div>
                        <input class="form-input" type="text" name="username" id="username" value="{{$user->username}}" {{$editing ? '' : 'disabled'}}>
                    </div>
                </div>
            </div>

            @if ($editing)
                <div class="flex justify-end gap-5 items-center mt-auto">
                    <a class="py-2 px-4 bg-table-even text-project-blue/50 rounded-md w-fit" href="{{url("/profile")}}">Cancel</a>
                    <button class="py-2 px-4 bg-project-yellow text-project-blue font-bold rounded-md w-fit">Save</button>
                </div>
            @endif
        </form>


        @if(!$editing)
        <form method="POST" action="{{url('/profile/change-password')}}" class="flex flex-col gap-5">
            @csrf
            <p class="text-lg font-bold">Change Password</p>

            @if(session()->has('error'))
                <p class="text-xs text-red-500 italic">{{session()->get('error')}}</p>
            @endif

            @if(session()->has('success'))
            <p class="text-xs text-green-500 italic">{{session()->get('success')}}</p>
        @endif

            <div class="grid grid-cols-3 gap-3">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="current_password" >Current Password</label>
                        @error('current_password')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="password" name="current_password" id="current_password">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="password" >New Password</label>
                        @error('password')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="password" name="password" id="password" >
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="confirm_password" >Confirm Password</label>
                        @error('confirm_password')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input" type="password" name="confirm_password" id="confirm_password">
                </div>
            </div>

            <button class="py-2 px-4 bg-project-yellow font-bold rounded-md w-fit">Change Password</button>
        </form>
        @endif
    </div>
</x-layout>