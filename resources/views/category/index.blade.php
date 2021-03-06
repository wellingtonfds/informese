@extends('layouts.dashboard')

@section('content')

    <div class="row">
        <div class="page-header">
            <h1>Categorias
                <small> Lista de categorias</small>
            </h1>
        </div>
        <div class="col-md-12">
            <div class="panel-body">
                <button class="btn btn-default" data-toggle="modal" data-target="#newCategory">Novo</button>
            </div>
        </div>
        <table class="table table-striped hover">
            <thead>
            <th>Name</th>
            <th>Descrição</th>
            <th>Ações</th>
            </thead>
            <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{$category->name}}</td>
                    <td>{{$category->description}}</td>
                    <td>
                        <input type="hidden" class="id-category" value="{{$category->id}}">
                        <button class="btn btn-primary btn-xs editar">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button class="btn btn-danger btn-xs excluir">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td rowspan="3">
                        Sem registros
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{$categories->links()}}
    </div>

    <!-- Modal New Category-->
    <div class="modal fade" id="newCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nova Categoria</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Descrição</label>
                                    <textarea type="text" class="form-control" name="description" id="description">
                                        </textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary insertCategory">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Category-->
    <div class="modal fade" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Editar Categoria</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" class="form-control" name="nameEdit" id="nameEdit" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Descrição</label>
                                    <textarea type="text" class="form-control" name="descriptionEdit"
                                              id="descriptionEdit">
                                        </textarea>
                                    <input type="hidden" id="idEdit">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary updateCategory">Salvar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.insertCategory').click(function () {
                var category = {
                    name: $('#name').val(),
                    description: $('#description').val().trim()
                }
                request('categories', 'post', category).done(function (response) {

                    swal({
                        title: 'Categoria inserida!',
                        text: 'A tela irá se recarregar em 2 segundos.',
                        timer: 2000
                    }).then(
                            function () {
                                location.reload();
                            },
                            // handling the promise rejection
                            function (dismiss) {
                                if (dismiss === 'timer') {
                                    location.reload();
                                }
                            }
                    )
                })
            });

            $(document).on('click', '.editar', function () {
                var id = $(this).parent().find('.id-category').val();
                request('categories/' + id + '/edit', 'get').done(function (response) {
                    $('#nameEdit').val(response.name);
                    $('#descriptionEdit').val(response.description)
                    $('#idEdit').val(response.id);
                    $('#editCategory').modal('show');
                })
            });
            $(document).on('click', '.excluir', function () {
                var id = $(this).parent().find('.id-category').val();
                swal({
                    title: 'Você tem certeza?',
                    text: "Não será possível reverter",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, faça isso!',
                    cancelButtonText: 'Não, cancelar!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {


                    request('categories/' + id, 'delete').done(function (response) {
                        swal({
                            title:'Apagado!',
                            text:'A categoria foi deletada',
                            type:'success'
                        }).then(function(){
                            location.reload();
                        })
                    });

                }, function (dismiss) {
                    // dismiss can be 'cancel', 'overlay',
                    // 'close', and 'timer'
                    if (dismiss === 'cancel') {
                        swal(
                                'Cancelado',
                                'Nenhum dado foi removido',
                                'error'
                        )
                    }
                })


            })
            $(".updateCategory").click(function () {
                var category = {
                    name: $('#nameEdit').val(),
                    description: $('#descriptionEdit').val(),
                    id: $('#idEdit').val()
                }
                request('categories/' + category.id, 'put', category).done(function (response) {

                    swal({
                        title: 'Categoria atualizada!',
                        text: 'A tela irá se recarregar em 2 segundos.',
                        timer: 2000
                    }).then(
                            function () {
                                location.reload();
                            },
                            // handling the promise rejection
                            function (dismiss) {
                                if (dismiss === 'timer') {
                                    location.reload();
                                }
                            }
                    )
                });

            })
        });
    </script>

@endsection