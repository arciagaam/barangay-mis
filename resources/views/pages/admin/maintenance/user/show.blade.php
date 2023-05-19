
<x-layout>
    <form action="{{url("/maintenance/users/$user->id/edit")}}" method="POST" class="flex flex-col bg-white flex-1 h-fit rounded-md shadow-md py-5 px-5 gap-7">
        @csrf
        <div class="flex w-full items-center justify-between">
            <p class="font-bold text-xl">{{$user->username}}</p>
            @if (!$editing)
                <div class="flex gap-3">
                    @if (auth()->user()->role_id == 1)  
                        <button type="button" data-url="{{url("/maintenance/users/$user->id/delete")}}" data-type="delete" data-group="user" class="popup_trigger primary-btn bg-red-500 font-normal text-white">Delete User</button>
                    @endif
                    <a href="{{url("/maintenance/users/$user->id/edit")}}" class="primary-btn">Edit</a>
                </div>
            @else
                <p class="italic text-sm">Editing</p>
            @endif
        </div>

        <div class="flex flex-col gap-5">
            <div class="flex flex-row gap-3">
                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="first_name" >First Name</label>
                        @error('first_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input w-fit" type="text" name="first_name" id="first_name" value="{{$user->first_name}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="middle_name" class="flex gap-2 items-center">Middle Name <span class="text-xs text-project-blue/20">optional</span></label>
                        @error('middle_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input w-fit" type="text" name="middle_name" id="middle_name" value="{{$user->middle_name}}" {{$editing ? '' : 'disabled'}}>
                </div>

                <div class="form-input-container">
                    <div class="flex flex-row justify-between items-center">
                        <label for="last_name" >Last Name</label>
                        @error('last_name')
                        <p class="text-xs text-red-500 italic">{{$message}}</p>
                        @enderror
                    </div>
                    <input class="form-input w-fit" type="text" name="last_name" id="last_name" value="{{$user->last_name}}" {{$editing ? '' : 'disabled'}}>
                </div>
            </div>

            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="username" >Username</label>
                    @error('username')
                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
                <input class="form-input w-fit" type="text" name="username" id="username" value="{{$user->username}}" {{$editing ? '' : 'disabled'}}>
            </div>

            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    <label for="password">Password</label>
                    @error('password')
                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>
                <input class="form-input w-1/4" type="password" name="password" id="password" value="{{$user->password}}" {{$editing ? '' : 'disabled'}}>
            </div>

            <div class="form-input-container">
                <div class="flex flex-row justify-between items-center">
                    @if($editing)
                        @if(auth()->user()->role_id == 1)
                            <label for="role_id">Role</label>
                        @endif
                    @else
                        <label for="role_id">Role</label>
                    @endif
                    @error('role_id')
                    <p class="text-xs text-red-500 italic">{{$message}}</p>
                    @enderror
                </div>

                @if ($editing)
                    @if(auth()->user()->role_id == 1)                    
                        <select class="form-input w-fit" name="role_id" id="role_id">
                            @foreach ($roles as $role)
                                <option value="{{$role->id}}">{{ucfirst($role->name)}}</option>
                            @endforeach
                        </select>
                    @endif
                @else
                    <input class="form-input w-fit" type="text" name="role_id" id="role_id" value="{{ucfirst($user->role)}}" {{$editing ? '' : 'disabled'}}>  
                @endif
            </div>
        </div>
        @if ($editing)
            <div class="flex justify-end gap-5 items-center mt-auto">
                <a class="secondary-btn" href="{{url("/maintenance/users/$user->id")}}">Cancel</a>
                <button type="submit" class="primary-btn w-fit">Save</button>
            </div>
        @endif
    </form>
</x-layout>