@extends('layouts.app')
@section('styles')
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
@endsection
@section('content')
    <div class="container">
        <div class="row">

            <div class="panel panel-default col-md-10 col-md-offset-1">
                <div class="panel-body">
                    <div class="col-md-2">
                        <button class="btn btn-default save">Salvar</button>
                    </div>
                    <div class="col-md-3">
                        <span><label>Procedimento:</label>{{$procedure->name}}</span>
                    </div>
                    <div class="col-md-3">
                        <span><label>Categoria:</label>{{$procedure->category->name}}</span>
                    </div>


                </div>
            </div>


            <div class="col-md-10 col-md-offset-1"
                 style="border: 2px black ridge;border-radius: 5px;">
                <div class="editable" style="padding: 5px 5px 400px 5px">
                    {!!  $procedure->text !!}
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scripts')

    <script src="{{ asset('js/all.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.js"></script>

    <script src="{{asset('js/handlebars/dist/handlebars.runtime.min.js')}}"></script>
    <script src="{{asset('js/jquery-sortable/source/js/jquery-sortable-min.js')}}"></script>

    <script src="{{asset('js/editor.js')}}"></script>


    <script>
        function request(url, method, data) {
            return $.ajax({
                url: url,
                data: data,
                dataType: 'json',
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

        var editor = new MediumEditor('.editable', {
            buttonLabels: 'fontawesome',
            imageDragging: true,
            placeholder: {
                /* This example includes the default options for placeholder,
                 if nothing is passed this is what it used */
                text: 'Digite seu texto',
                hideOnClick: true
            },
            extensions: {
                table: new MediumEditorTable()
            },
            toolbar: {
                buttons: [
                    'bold', 'italic', 'underline', 'anchor', 'h2', 'h3', 'quote',
                    'unorderedlist', 'orderedlist', 'table', 'justifyLeft', 'justifyCenter',
                    'justifyRight', 'justifyFull', 'html', 'removeFormat'
                ],
                static: true,
                sticky: true,
                autoLink: true
            }
        });
        $(function () {
            $('.editable').mediumInsert({
                editor: editor,
                addons: {
                    images: {
                        uploadScript: null,
                        deleteScript: null,
                        captionPlaceholder: 'Type caption for image',
                        styles: {
                            slideshow: {
                                label: '<span class="fa fa-play"></span>',
                            }
                        },
                        actions: null,
                        fileUploadOptions: { // (object) File upload configuration. See https://github.com/blueimp/jQuery-File-Upload/wiki/Options
                            url: 'teste/lalala/', // (string) A relative path to an upload script
                            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i // (regexp) Regexp of accepted file types
                        },
                    }
                }
            });
        });

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.save').click(function () {
                button = $(this);
                button.prop('disabled', true);
                button.text('processando...');
                request(window.location.href,'POST',{text: editor.getContent()})
                    .done(function(response){
                    swal({
                        title: 'Salvo!',
                        text: 'O procedimento foi salvo.',
                        type: 'success'
                    });
                    button.prop('disabled', false);
                    button.text('Salvar');
                }).fail(function(){
                    button.prop('disabled', false);
                    button.text('Salvar');
                });
            });
        });
    </script>


@endsection