@extends('laratrust::panel.layout')

@section('title', $model ? "Edit {$type}" : "New {$type}")

@section('content')
  <div>
  </div>
  <div class="flex flex-col">
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-32">
      <form
        x-data="laratrustForm()"
        x-init="{!! $model ? '' : '$watch(\'displayName\', value => onChangeDisplayName(value))'!!}"
        method="POST"
        action="{{$model ? route("laratrust.{$type}s.update", $model->id) : route("laratrust.{$type}s.store")}}"
        class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200 p-8"
      >
        @csrf
        @if ($model)
          @method('PUT')
        @endif
        <label class="block">
          <span class="text-gray-700">Name/Code</span>
          <input
            class="form-input mt-1 block w-full bg-gray-200 text-gray-600 @error('name') border-red-500 @enderror"
            name="name"
            placeholder="this-will-be-the-code-name"
            :value="name"
            readonly
            autocomplete="off"
          >
          @error('name')
              <div class="text-red-500 text-sm mt-1">{{ $message }} </div>
          @enderror
        </label>

        <label class="block my-4">
          <span class="text-gray-700">Display Name</span>
          <input
            class="form-input mt-1 block w-full @if($type == 'permission') bg-gray-200 text-gray-600 @endif @error('display_name')  border-red-500 @enderror"
            name="display_name"
            placeholder="Some name for the {{$type}}"
            x-model="displayName"
            @if($type == 'permission')  readonly @endif
            autocomplete="off"
          >
          @error('display_name')
              <div class="text-red-500 text-sm mt-1">{{ $message }} </div>
          @enderror
        </label>

        @if($type == 'permission') 
        <label class="block my-4">
          <span class="text-gray-700">Permission to</span>
          <input
            class="form-input mt-1 block w-full"
            name="permission_to"
            placeholder="Permission to..."
            x-model="permissionTo"
            autocomplete="off"
            @keyup="onChangeDisplayName()"
          >
            @error('permission_to')
              <div class="text-red-500 text-sm mt-1">{{ $message }} </div>
            @enderror
        </label>
        <label class="block my-4">
          <span class="text-gray-700">Module Name</span>
          <select class="form-select block w-full mt-1 @error('module_name') border-red-500 @enderror" x-model="moduleName" name="module_name" @change="onChangeDisplayName()">
              <option value="">Select Module</option>
              @foreach ($modelClasses as $modelClass)
                <option value="{{ucwords($modelClass)}}">{{ucwords($modelClass)}}</option>
              @endforeach
            </select>
            @error('module_name')
              <div class="text-red-500 text-sm mt-1">{{ $message }} </div>
            @enderror
        </label>
        @endif

        <label class="block my-4">
          <span class="text-gray-700">Description</span>
          <textarea
            class="form-textarea mt-1 block w-full"
            rows="3"
            name="description"
            placeholder="Some description for the {{$type}}"
          >{{ $model->description ?? old('description') }}</textarea>
        </label>
        @if($type == 'role')
          <span class="block text-gray-700">Permissions</span>
          <div class="flex flex-wrap justify-start mb-4">
            @foreach ($permissions as $permission)
              <label class="inline-flex items-center mr-6 my-2 text-sm" style="flex: 1 0 20%;">
                <input
                  type="checkbox"
                  class="form-checkbox h-4 w-4"
                  name="permissions[]"
                  value="{{$permission->id}}"
                  {!! $permission->assigned ? 'checked' : '' !!}
                >
                <span class="ml-2">{{$permission->display_name ?? $permission->name}}</span>
              </label>
            @endforeach
          </div>
        @endif
        <div class="flex justify-end">
          <a
            href="{{route("laratrust.{$type}s.index")}}"
            class="btn btn-red mr-4"
          >
            Cancel
          </a>
          <button class="btn btn-blue" type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>
  <script>
    window.laratrustForm =  function() {
      return {
        displayName: '{{ $model->display_name ?? old('display_name') }}',
        moduleName: '{{ $model->module_name ?? old('module_name') }}',
        @if($type == 'permission')
        permissionTo: '{{ $model->permission_to ?? old('permission_to') }}',
        @endif
        name: '{{ $model->name ?? old('name') }}',
        toKebabCase(str) {
          return str &&
            str
              .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
              .map(x => x.toLowerCase())
              .join('-')
              .trim();
        },
        capitalize(str)
        {
            return str?str[0].toUpperCase() + str.slice(1):str;
        },
        onChangeDisplayName(value) {
          @if($type == 'permission')
            this.displayName = this.moduleName?this.capitalize(this.permissionTo)+' '+this.moduleName:this.capitalize(this.permissionTo)+' ';
            this.name = this.moduleName?this.toKebabCase(this.moduleName)+'-'+this.toKebabCase(this.permissionTo):this.capitalize(this.permissionTo)+' ';
          @else
            this.name = this.toKebabCase(value);
          @endif
        },
      }
    }
  </script>
@endsection