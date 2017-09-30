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
        <table class="table table-striped hover">
            <thead>
            <th>Nome</th>
            <th>Versão</th>
            <th>Categoria</th>
            <th>Final publicação</th>
            <th></th>
            </thead>
            <tbody>
            @forelse($procedures as $procedure)
                <tr>
                    <td>{{$procedure->name}}</td>
                    <td>{{ $procedure->revisions()->count()}}</td>
                    <td>{{$procedure->category->name}}</td>

                    <td>{{empty($procedure->date_publish_finish) ? 'Sem limite' : $procedure->date_publish_finish->format('d/m/Y')}}</td>
                    <td class="data-procedure">
                        <input type="hidden" class="id-procedure" value="{{$procedure->id}}">
                        <input type="hidden" class="url-procedure"
                               value="{{str_replace('/public/','/storage/',asset($procedure->file))}}">
                        <button class="btn btn-success btn-xs view" title="Visualizar procedimento">
                            <span class="glyphicon glyphicon-eye-open"></span>
                            Visualizar
                        </button>
                        <button class="btn btn-default btn-xs suggestion" title="Sugestão ">
                            <span class="glyphicon glyphicon glyphicon-comment"></span>
                            Sugestão
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

    {{--View Files--}}
    <div class="modal fade bs-example-modal-lg" id="view-files" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span id="procedureNameDetails"></span></h4>
                    <p class="space-line">
                        <label>Categoria:</label> <span id="categoryDetails"></span>
                        <label>Versão :</label> <span id="versionDetails"></span>
                    </p>
                    <p class="space-line"><label>Data final publicação:</label> <span
                                id="datePublishFinishDetails"></span></p>
                    <input type="hidden" id="idDetails">

                    <div class="elaborate">
                        <p class="space-line">
                            <label>Elaborado por:</label> <span id="elaborateDetails"></span>
                            <label>Data Elaboração:</label> <span id="elaborateDateDetails"></span>
                        </p>
                    </div>
                    <div class="revision hide">
                        <p class="space-line">
                            <label>Revisado por:</label> <span id="revisionDetails"></span>
                            <label>Data Revisão:</label> <span id="revisionDateDetails"></span>
                        </p>
                    </div>
                    <div class="approved hide">
                        <p class="space-line">
                            <label>Aprovado por :</label> <span id="approvedDetails"></span>
                            <label>Data Aprovação:</label> <span id="approvedDateDetails"></span>
                        </p>
                    </div>
                    <button class="btn btn-default" id="back">Anterior</button>
                    <button class="btn btn-default" id="next">Próximo</button>
                    <div class="">
                        <button class="btn btn-info btn-xs print" >
                            <span class="glyphicon glyphicon-print"> Imprimir</span>
                        </button>
                    </div>

                </div>

                <div class="modal-body">
                    <div class="row">
                        <a href="" class="media"></a>
                        <div class="content-procedure"
                             style="border: 1px ridge black;padding: 0px 0px 5px 5px;border-radius: 4px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="suggestion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Sugestão</h4>
                </div>
                <form id="newSuggestion">
                    <input type="hidden" name="procedure_id" id="procedure_id">
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Etapa</label>
                                    <input type="text" class="form-control" name="stage">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Sugestão</label>
                                    <textarea class="form-control" name="suggestion" required></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary" >Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
         var select = null;
        //var procedures = ;
        function dateBrToUs(date) {
            if (date !== '') {
                aux = date.split('/');
                return aux[2] + '-' + aux[1] + '-' + aux[0];


            }
            return date;
        }

        function dateUsToBr(date) {
            if (date !== null) {
                date = date.replace('00:00:00', '').trim();
                aux = date.split('-');
                return aux[2] + '/' + aux[1] + '/' + aux[0];
            }
            return date;
        }

        function request(url, method, data) {
            return $.ajax({
                url: url,
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                method: method,
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
                }
            })
        }

        function getIndex(list, position) {
            $(list).each(function (index,item) {
                if($(item).val()==position){
                    return index;
                }
            });
        }
        function next(list,id) {
            index = 0;
            /*if(position < list.length-1){
                index = position+1;
            }*/
            getIndex(list,id);
            //console.log(list);
            //console.log($(list[0]).val());
        }
        function show(id,url) {
            request('/procedure/details/' + id, 'get').then(function (response) {
                $('#procedureNameDetails').text(response.procedure.name);
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
                if (response.procedure.file != null) {
                    $('.print').addClass('hide');
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
                    $('.print').removeClass('hide');
                    $('.content-procedure').append(response.procedure.text);

                }

                $('#view-files').modal('show');
            });
        }
        $(document).ready(function () {
            var listProcedures = $('.table tbody tr .data-procedure .id-procedure');


            $('#next').click(function () {
                next(listProcedures,select);

            });
            $('#date_publish_finish').datepicker();
            $('#date_publish_finishEdit').datepicker();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


        });
        $(document).on('click','.print',function () {
            var id =  $('#idDetails').val();
            window.open('/procedure/detail/'+id,'_blank');
        });
        $(document).on('click', '.suggestion', function () {

            $('#procedure_id').val($(this).parent().find('.id-procedure').val());
            $('#suggestion').modal('show');
        });
        $('#newSuggestion').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('procedure_id',$('#procedure_id').val());
            request('/suggestions','post',formData).then(function (response) {
                $('#newSuggestion')[0].reset();
                swal(
                    'Sucesso...',
                    'Obrigado pela sua sugestão ou crítica.',
                    'success'
                )
                $('#suggestion').modal('hide');
            });

        });
        $(document).on('click', '.view', function () {
            var url = $(this).parent().find('.url-procedure').val();
            var id = $(this).parent().find('.id-procedure').val();
            select = id;
            show(id,url);

        });
        $('#view-files').on('hidden.bs.modal', function (e) {
            $('.revision').addClass('hide');
            $('.approved').addClass('hide');
            $('.approvedButton').addClass('hide');
            $('.revisionButton').addClass('hide');
            $('.content-procedure').empty();
        });
    </script>

@endsection