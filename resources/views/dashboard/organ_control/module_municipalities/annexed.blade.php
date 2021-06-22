@extends('dashboard.base')

@section('content')

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header card__header-modified">
                        <h3 class="users__title">Anexos</h3>
                    </div>

                    <div class="card-body">
                        <table id="annexed_table">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Nombre</th>
                                    <th>Area</th>
                                    <th>Observaciones</th>
                                    <th>Estatus</th>
                                    <th>Descargar Anexo</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inicia Cards de Anexos -->
    <div class="container-fluid">
        <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header card__header-modified">
                        <i class="fa fa-align-justify"></i>
                        <h4 class="users__title">Lista Anexos</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            @php
                            $i = 1;
                            @endphp
                            @foreach($descriptions as $desc)
                            <div class="col-md-3 mb-3">
                                <!-- style="background-image: url(https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTIHhs99QyEtdjlYYK44MU0b3LKApyO83oA9Q&usqp=CAU)" -->
                                <div class="card text-white mt-5 h-100" style="background-color: #445554;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h2 class="users__title">
                                                    @php
                                                    echo($i);
                                                    @endphp
                                                </h2>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="card-text">{{$desc}}</p>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-12 text-right">
                                                <a class="btn btn-danger" href="/build_annexed/{{$i}}">
                                                    <i class="cil-search"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
<!-- Cierra Cars de Anexos -->
@endsection


@section('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script src="{{asset('assets/js/organ_control/municipality.js')}}"></script>
@endsection
