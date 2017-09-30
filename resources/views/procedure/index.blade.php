@extends('layouts.dashboard')

@section('content')
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/editor.css')}}" type="text/css" media="screen" charset="utf-8">
    <link rel="stylesheet"
          href="{{asset('js/medium-editor-insert-plugin/dist/css/medium-editor-insert-plugin.min.css')}}"
          type="text/css" media="screen" charset="utf-8">
    <link rel="stylesheet"
          href="{{asset('js/medium-editor-insert-plugin/dist/css/medium-editor-insert-plugin-frontend.min.css')}}"
          type="text/css" media="screen" charset="utf-8">
    <style>
        .medium-insert-images figure figcaption,
        .mediumInsert figure figcaption,
        .medium-insert-embeds figure figcaption,
        .mediumInsert-embeds figure figcaption {
            font-size: 12px;
            line-height: 1.2em;
        }

        .medium-insert-images-slideshow figure {
            width: 100%;
        }

        .medium-insert-images-slideshow figure img {
            margin: 0;
        }

        .medium-insert-images.medium-insert-images-grid.small-grid figure {
            width: 12.5%;
        }

        @media (max-width: 750px) {
            .medium-insert-images.medium-insert-images-grid.small-grid figure {
                width: 25%;
            }
        }

        @media (max-width: 450px) {
            .medium-insert-images.medium-insert-images-grid.small-grid figure {
                width: 50%;
            }
        }
    </style>
    <link href="{{ asset('/css/all.css') }}" rel="stylesheet">

    <style>
        .space-line {
            line-height: 0.2
        }

        .hide {
            display: none;
        }
    </style>
    <div class="row">
        <div class="page-header">
            <h1>Procedimentos
                <small> Lista de procedimentos</small>
            </h1>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <button class="btn btn-default" data-toggle="modal" data-target="#newCategory">Novo</button>
            </div>
        </div>
        <table class="table table-striped hover">
            <thead>
            <th>Nome</th>
            <th>Versão</th>
            <th>Categoria</th>
            <th>Publicação</th>
            <th>Final publicação</th>
            <th>Fase</th>
            <th></th>
            </thead>
            <tbody>
            @forelse($procedures as $procedure)
                <tr>
                    <td>{{$procedure->name}}</td>
                    <td>{{ $procedure->revisions()->count()}}</td>
                    <td>{{$procedure->category->name}}</td>
                    <td>{{$procedure->publish ? $procedure->date_publish->format('d/m/Y') : "Não publicado"}}</td>
                    <td>{{empty($procedure->date_publish_finish) ? 'Sem limite' : $procedure->date_publish_finish->format('d/m/Y')}}</td>
                    <td>{{$procedure->step()}}</td>
                    <td>
                        <input type="hidden" class="id-procedure" value="{{$procedure->id}}">
                        <input type="hidden" class="url-procedure"
                               value="{{str_replace('/public/','/storage/',asset($procedure->file))}}">
                        @if(empty($procedure->file))
                            <a class="btn btn-success btn-xs" title="Editar Texto" target="_blank"
                               href="{{url('/procedures/text')."/".$procedure->id}}">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                        @endif
                        <button class="btn btn-primary btn-xs editar">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button class="btn btn-danger btn-xs excluir">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                        <button class="btn btn-success btn-xs view">
                            <span class="glyphicon glyphicon-eye-open" title="Revisões do procedimento"></span>
                        </button>
                        <button class="btn btn-warning btn-xs notification" title="Notificar usuários">
                            <span class="glyphicon glyphicon-file"></span>
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
        {{$procedures->links()}}
    </div>
    <!-- Modal New Category-->
    <div class="modal fade" id="newCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nova procedimento</h4>
                </div>
                <form id="newFormProcedure" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Data final publicação</label>
                                    <input type="text" class="form-control" name="date_publish_finish"
                                           id="date_publish_finish">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Categoria</label>
                                    <select type="text" class="form-control" name="category_id"
                                            id="category_id" required>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Arquivo</label>
                                    <input type="file" class="form-control" name="file" id="file">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <p class="text-warning">Deixe o campo file vazio para inserir um texto..</p>
                                    <p class="text-danger">Permita pop-up desse domínio</p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary insertProcedure">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Editar Category-->
    <div class="modal fade" id="editProcedure" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Editar procedimento</h4>
                </div>
                <form id="editFormProcedure" enctype="multipart/form-data">
                    <input type="hidden" id="idEdit" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" class="form-control" name="nameEdit" id="nameEdit" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Data final publicação</label>
                                    <input type="text" class="form-control" name="date_publish_finishEdit"
                                           id="date_publish_finishEdit">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Categoria</label>
                                    <select type="text" class="form-control" name="category_idEdit"
                                            id="category_idEdit" required>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Arquivo</label>
                                    <p class="text-warning">Caso faça o upload de um novo arquivo, será gerado uma nova
                                        versão.</p>
                                    <input type="file" class="form-control" name="file" id="file">
                                    <div class="checkbox">
                                        <p class="text-warning">A publicação só acontence após o procedimeto ser
                                            validado.</p>

                                        <label>
                                            <input type="checkbox" name="publishEdit" disabled> Publicar
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary updateProcedure">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--View Files--}}
    <div class="modal fade bs-example-modal-lg" id="view-files" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" style="height:100% !important; width:100% !important;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Revisão de procedimento</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idDetails">
                    <div class="">
                        <p class="space-line"><label>Publicado :</label> <span id="publishDetails"></span></p>
                        <p class="space-line"><label>Versão :</label> <span id="versionDetails"></span></p>
                        <p class="space-line"><label>Status:</label> <span class="label label-warning"><span
                                        id="stateDetails"></span></span></p>
                        <p class="space-line"><label>Categoria:</label> <span id="categoryDetails"></span></p>
                        <p class="space-line"><label>Data final publicação:</label> <span
                                    id="datePublishFinishDetails"></span></p>

                    </div>
                    <div class="elaborate">
                        <p class="space-line"><label>Elaborado por:</label> <span id="elaborateDetails"></span></p>
                        <p class="space-line"><label>Data Elaboração:</label> <span id="elaborateDateDetails"></span>
                        </p>
                    </div>
                    <div class="revision hide">
                        <p class="space-line"><label>Revisado por:</label> <span id="revisionDetails"></span></p>
                        <p class="space-line"><label>Data Revisão:</label> <span id="revisionDateDetails"></span></p>
                    </div>
                    <div class="approved hide">
                        <p style="line-height:0"><label>Aprovado por :</label> <span id="approvedDetails"></span></p>
                        <p style="line-height:0"><label>Data Aprovação:</label> <span id="approvedDateDetails"></span>
                        </p>
                    </div>

                    <button type="button " class="btn btn-default approvedButton stepButton hide">
                        Aprovar
                    </button>
                    <button type="button " class="btn btn-default revisionButton stepButton hide">
                        Revisar
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <a href="" class="media"></a>
                        <div class="content-procedure"
                             style="border: 1px ridge black;padding: 0px 0px 5px 5px;border-radius: 2px"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="http://cdn.jsdelivr.net/contenttools/1.3.1/content-tools.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#date_publish_finish').datepicker();
            $('#date_publish_finishEdit').datepicker();
            $('#newFormProcedure').submit(function (e) {
                $('.insertProcedure').prop('disabled', true);
                $('.insertProcedure').text('processando...');
                e.preventDefault();
                var formData = new FormData(this);
                formData.set('date_publish_finish', dateBrToUs(formData.get('date_publish_finish')));
                $.ajax({
                    url: 'procedures', // Url do lado server que vai receber o arquivo
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    statusCode: {
                        404: function () {
                            swal(
                                    'Oops...',
                                    'Endereço não encontrado!',
                                    'error'
                            )

                        },
                        403: function () {
                            swal(
                                    'Oops...',
                                    'Acesso não autorizado!',
                                    'error'
                            )
                        },
                        500: function () {
                            swal(
                                    'Oops...',
                                    'Erro interno do servidor!',
                                    'error'
                            )

                        },
                        422: function (response) {

                            var messagem = "";
                            $.each(response.responseJSON, function (index, item) {
                                messagem += item + "\n";
                            });
                            swal(
                                    'Oops...',
                                    messagem,
                                    'error'
                            )
                        }
                    },
                    success: function (data) {
                        if (data.file == null) {
                            console.log('nova aba')
                            window.open("/procedures/text/" + data.id);
                            location.reload();
                        }
                        else {
                            swal({
                                title: 'Inserido',
                                text: 'O procedimento foi inserido',
                                type: 'success'
                            }).then(function () {
                                location.reload();
                            });
                        }
                    }
                });
            });
            $('#editFormProcedure').submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.set('date_publish_finish', dateBrToUs(formData.get('date_publish_finishEdit')));
                $.ajax({
                    url: 'procedures/' + $('#idEdit').val(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    statusCode: {
                        404: function () {
                            swal(
                                    'Oops...',
                                    'Endereço não encontrado!',
                                    'error'
                            )

                        },
                        403: function () {
                            swal(
                                    'Oops...',
                                    'Acesso não autorizado!',
                                    'error'
                            )
                        },
                        500: function () {
                            swal(
                                    'Oops...',
                                    'Erro interno do servidor!',
                                    'error'
                            )

                        },
                        422: function (response) {

                            var messagem = "";
                            $.each(response.responseJSON, function (index, item) {
                                messagem += item + "\n";
                            });
                            swal(
                                    'Oops...',
                                    messagem,
                                    'error'
                            )
                        }
                    },
                    success: function (data) {
                        console.log(data);
                        swal({
                            title: 'Atualizado',
                            text: 'O procedimento foi atualizado',
                            type: 'success'
                        }).then(function () {
                            location.reload();
                        });
                    }
                });
            });


        });


        $(document).on('click', '.editar', function () {
            var id = $(this).parent().find('.id-procedure').val();
            var button = $(this);
            request('procedures/' + id + '/edit', 'get').done(function (response) {
                $('#nameEdit').val(response.name);
                $('#idEdit').val(response.id);
                $('#date_publish_finishEdit').val(dateUsToBr(response.date_publish_finish))
                $('#category_idEdit').val(response.categories_id);
                if (response.step == 'Aprovado') {
                    $('input[name=publishEdit]').removeAttr('disabled');
                } else {
                    $('input[name=publishEdit]').prop('disabled', true);
                }
                if (response.publish == '1') {
                    $('input[name=publishEdit]').prop("checked", true);
                } else {
                    $('input[name=publishEdit]').prop("checked", false);
                }
                $('#editProcedure').modal('show');
            });
        });
        $(document).on('click', '.view', function () {
            var url = $(this).parent().find('.url-procedure').val();
            var id = $(this).parent().find('.id-procedure').val();
            request('procedure/details/' + id, 'get').then(function (response) {
                $('#idDetails').val(response.procedure.id);
                $('#publishDetails').text(response.procedure.publish === '1' ? "Sim" : "Não");
                $('#versionDetails').text(response.lastRevision.lastVersion.version);
                $('#stateDetails').text(response.step);
                $('#categoryDetails').text(response.category);
                $('#datePublishFinishDetails').text(response.date_publish_finish != null ? response.date_publish_finish : "Sem data.");
                $('#elaborateDetails').text(response.lastRevision.users.elaborate.name);
                $('#elaborateDateDetails').text(response.lastRevision.lastVersion.elaborate_date);
                $('#revisionDetails').text(response.lastRevision.users.reviewed.name);
                $('#revisionDateDetails').text(response.lastRevision.lastVersion.reviewed_date);
                $('#approvedDetails').text(response.lastRevision.users.approved.name);
                $('#approvedDateDetails').text(response.lastRevision.lastVersion.approved_date);
                if (response.step == 'revisão pendente') {
                    $('.revisionButton').removeClass('hide');
                } else if (response.step == 'Aprovação pendente') {
                    $('.revision').removeClass('hide');
                    $('.approvedButton').removeClass('hide');
                } else {
                    $('.revision').removeClass('hide');
                    $('.approved').removeClass('hide');
                }
                if (response.procedure.file != null) {
                    $('.content-procedure').addClass('hide');
                    $('.media').removeClass('hide');
                    $('.media').attr('href', url);
                    $('.media').media({
                        width: 885,
                        height: 800,
                    });
                } else {
                    $('.media').addClass('hide');
                    $('.content-procedure').removeClass('hide');
                    $('.content-procedure').append(response.procedure.text);

                }

                $('#view-files').modal('show');
            })

        });
        $('#view-files').on('hidden.bs.modal', function (e) {
            $('.revision').addClass('hide');
            $('.approved').addClass('hide');
            $('.approvedButton').addClass('hide');
            $('.revisionButton').addClass('hide');
            $('.content-procedure').empty();
        });
        $(document).on('click', '.excluir', function () {
            var id = $(this).parent().find('.id-procedure').val();
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
                request('procedures/' + id, 'delete').done(function (response) {
                    swal({
                        title: 'Apagado!',
                        text: 'O procedimento foi deletado',
                        type: 'success'
                    }).then(function () {
                        location.reload();
                    })
                });
            }, function (dismiss) {
                if (dismiss === 'cancel') {
                    swal(
                            'Cancelado',
                            'Nenhum dado foi removido',
                            'error'
                    )
                }
            })


        });
        $(".stepButton").click(function () {
            var button = $(this);
            button.prop('disabled', true);
            button.text('processando...');
            var id = $('#idDetails').val();
            request('procedure/state/' + id, 'PUT').then(function (response) {
                swal({
                    title: 'Revisado',
                    text: 'O procedimento foi revisado',
                    type: 'success'
                }).then(function () {
                    location.reload();
                });
            });
        });
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

        });
        $(document).on('click', '.notification', function () {
            var id = $(this).parent().find('.id-procedure').val();
            swal({
                title: 'Notificar todos os usuários ?',
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
                request('/procedure/notification/' + id, 'GET').done(function (response) {
                    swal({
                        title: 'Feito!',
                        text: 'Todos os usuários serão notificados',
                        type: 'success'
                    });
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


        });
    </script>

@endsection