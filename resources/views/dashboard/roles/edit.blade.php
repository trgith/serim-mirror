@extends('dashboard.base')

@section('content')


<div class="container-fluid">
  <div class="fade-in">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header"><h4>Editar rol</h4></div>
            <div class="card-body">
                @if(Session::has('message'))
                    <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
                @endif
                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $role->id }}"/>
                    <table class="table table-bordered datatable">
                        <tbody>
                            <tr>
                                <th>
                                    Nombre
                                </th>
                                <td>
                                    <input class="form-control" name="name" value="{{ $role->name }}" type="text"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                    <a class="btn btn-secondary" href="{{ route('roles.index') }}">Regresar</a>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('javascript')


@endsection