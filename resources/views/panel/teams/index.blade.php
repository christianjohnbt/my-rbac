@extends('laratrust::panel.layout')

@section('title', 'Teams')

@section('content')
  <div class="flex flex-col">
    <a
      href="{{route('laratrust.teams.create')}}"
      class="self-end bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
    >
      + New Team
    </a>
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
      <div class="mt-4 align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
        <table class="min-w-full">
          <thead>
            <tr>
              <th class="th">Id</th>
              <th class="th">Name/Code</th>
              <th class="th">Display Name</th>
              <th class="th">Description</th>
              <th class="th"></th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach ($teams as $team)
            <tr>
              <td class="td text-sm leading-5 text-gray-900">
                {{$team->id}}
              </td>
              <td class="td text-sm leading-5 text-gray-900">
                {{$team->name}}
              </td>
              <td class="td text-sm leading-5 text-gray-900">
                {{$team->display_name}}
              </td>
              <td class="td text-sm leading-5 text-gray-900">
                {{$team->description}}
              </td>
              <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                <a href="{{route('laratrust.teams.edit', $team->id)}}" class="text-blue-600 hover:text-blue-900">Edit</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{ $teams->links('laratrust::panel.pagination') }}
@endsection