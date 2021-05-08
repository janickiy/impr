@extends('cp.app')

@section('title', $title)

@section('css')

@endsection

@section('content')

    <div class="row-fluid">

        <div class="col">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-blueDark" data-widget-editbutton="false">

                <!-- widget div-->
                <div>

                    <div class="table-responsive">

                        {!! Form::open(['url' => URL::route('cp.users.status'), 'method' => 'put']) !!}

                        <table id="itemList" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th width="10px">
                                <span>
                                    <input type="checkbox"  title="Отметить все/Снять отметку у всех" id="checkAll">
                                </span>
                                </th>
                                <th>Фамилия</th>
                                <th>Имя</th>
                                <th>Никнейм</th>
                                <th>Email</th>
                                <th>Роль</th>
                                <th>Дата рождения</th>
                                <th>Дата регистрации</th>
                                <th>Удален</th>
                                <th>Заблокирован</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-sm-12 padding-bottom-10">
                                <div class="form-inline">
                                    <div class="control-group">

                                        {!! Form::select('action',[
                                        '0' => 'заблокировать',
                                        '1' => 'разблокировать',
                                        '2' => 'сделать автором'
                                        ],null,['class' => 'span3 form-control', 'id' => 'select_action','placeholder' => '--выберите действия--']) !!}

                                        <span class="help-inline">
                                           {!! Form::submit('применить', ['class' => 'btn btn-success', 'disabled' => "", 'id' => 'apply']) !!}
                                        </span>

                                    </div>
                                </div>
                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>

                </div>
                <!-- end widget content -->

            </div>
            <!-- end widget div -->

        </div>
        <!-- end widget -->

    </div>

@endsection

@section('js')

    <script>

        $(document).ready(function () {

            $("#apply").click(function (event) {
                var idSelect = $('#select_action').val();

                if (idSelect == '') {
                    event.preventDefault();
                    swal({
                        title: "Error",
                        text: "выберите действия",
                        type: "error",
                        showCancelButton: false,
                        cancelButtonText: "отменить",
                        confirmButtonColor: "#DD6B55",
                        closeOnConfirm: false
                    });
                } else {
                    if (isConfirm) form.submit();
                }
            })

            $("#checkAll").click(function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
                countChecked();
            });

            $("#checkAll").on('change',function () {
                countChecked();
            });

            $("#itemList").on('change', 'input.check', function () {
                countChecked();
            });

            pageSetUp();

            /* // DOM Position key index //

            l - Length changing (dropdown)
            f - Filtering input (search)
            t - The Table! (datatable)
            i - Information (records)
            p - Pagination (paging)
            r - pRocessing
            < and > - div elements
            <"#id" and > - div with an id
            <"class" and > - div with a class
            <"#id.class" and > - div with an id and class

            Also see: http://legacy.datatables.net/usage/features
            */

            /* BASIC ;*/
            var responsiveHelper_dt_basic = undefined;

            var breakpointDefinition = {
                tablet: 1024,
            };

            $('#itemList').dataTable({
                "sDom": "flrtip",
                "autoWidth": true,
                "oLanguage": {
                    "sLengthMenu": "Отображено _MENU_ записей на страницу",
                    "sZeroRecords": "Ничего не найдено - извините",
                    "sInfo": "Показано с _START_ по _END_ из _TOTAL_ записей",
                    "sInfoEmpty": "Показано с 0 по 0 из 0 записей",
                    "sInfoFiltered": "(отфильтровано  _MAX_ всего записей)",
                    "oPaginate": {
                        "sFirst": "Первая",
                        "sLast": "Посл.",
                        "sNext": "След.",
                        "sPrevious": "Пред.",
                    },
                    "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
                },
                "preDrawCallback": function () {
                    // Initialize the responsive datatables helper once.
                    if (!responsiveHelper_dt_basic) {
                        responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#itemList'), breakpointDefinition);
                    }
                },
                "rowCallback": function (nRow) {
                    responsiveHelper_dt_basic.createExpandIcon(nRow);
                },
                "drawCallback": function (oSettings) {
                    responsiveHelper_dt_basic.respond();
                },
                'createdRow': function (row, data, dataIndex) {
                    $(row).attr('id', 'rowid_' + data['id']);
                },
                aaSorting: [[1, 'asc']],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ URL::route('cp.datatable.users') }}'
                },
                columns: [
                    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {data: 'lastname', name: 'lastname'},
                    {data: 'firstname', name: 'firstname'},
                    {data: 'nickname', name: 'nickname'},
                    {data: 'email', name: 'email'},
                    {data: 'role', name: 'role'},
                    {data: 'birthday', name: 'birthday'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'deleted_at', name: 'deleted_at'},
                    {data: 'banned_at', name: 'banned_at'},
                ],
            });
        })

        function countChecked()
        {
            if ($('.check').is(':checked'))
                $('#apply').attr('disabled',false);
            else
                $('#apply').attr('disabled',true);
        }

    </script>

@endsection
