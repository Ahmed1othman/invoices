@extends('layouts.master')
@section('title')
قايمة الفواتير
@stop
@section('css')
    <link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">{{__('messages.Invoices List')}}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{__('messages.Invoices')}}</span>
						</div>
					</div>

				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
                <div class="col-xl-12">
                    <div class="card mg-b-20">
                        <div class="card-header pb-0">
                            <div class="col-sm-4 col-md-2">
                                <a class="btn btn-primary-gradient btn-block" href="{{route('invoices.create')}}"> اضافة فاتورة <i class="fas fa-plus-circle"></i></a>
                            </div>



                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table key-buttons text-xl-nowrap">
                                    <thead class="">
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">{{__('messages.Invoice Number')}}</th>
                                        <th class="border-bottom-0">{{__('messages.Product')}}</th>
                                        <th class="border-bottom-0">{{__('messages.Section')}}</th>
                                    <!--<th class="border-bottom-0">{{__('messages.Discount')}}</th>-->
                                    <!--<th class="border-bottom-0">{{__('messages.Tax Rate')}}</th>-->
                                    <!--<th class="border-bottom-0">{{__('messages.Tax Value')}}</th>-->
                                        <th class="border-bottom-0">{{__('messages.Total')}}</th>
                                        <th class="border-bottom-0">{{__('messages.Status')}}</th>
                                        <th class="border-bottom-0">{{__('messages.Invoice Date')}}</th>
                                        <th class="border-bottom-0">{{__('messages.Due Date')}}</th>
                                    <!--<th class="border-bottom-0">{{__('messages.Notes')}}</th>-->
                                        <th class="border-bottom-0">{{__('messages.created_by')}}</th>
                                        <th class="border-bottom-0">{{__('messages.Operations')}}</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                    $i = 0;
                                    @endphp

                                        @foreach($invoices as $invoice)
                                            @php
                                                $i = $i+1;
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>

                                                <td><a href="{{route('invoices.getDetails',$invoice->id)}}">{{$invoice->invoice_number }}</a></td>
                                                <td>{{$invoice->product->product_name}}</td>
                                                <td>{{$invoice->section->section_name}}</td>
                                            <!--<td>{{$invoice->discount}}</td>-->
                                            <!--<td>{{$invoice->rate_vat}}</td>-->
                                            <!--<td>{{$invoice->value_vat}}</td>-->
                                                <td>{{$invoice->total}}</td>
                                                    <td>
                                                        @if($invoice->status == "unpaid")
                                                            <span class="badge bg-danger text-white">{{__('messages.'.$invoice->status.'')}}</span>
                                                        @elseif($invoice->status == "paid")
                                                            <span class="badge bg-success text-white">{{__('messages.'.$invoice->status.'')}}</span>
                                                        @else
                                                            <span class="badge bg-warning text-white">{{__('messages.'.$invoice->status.'')}}</span>
                                                        @endif
                                                    </td>

                                                <td>{{$invoice->invoice_date}}</td>
                                                <td>{{$invoice->due_date}}</td>
                                            <!-- <td>{{$invoice->note}}</td> -->
                                                <td>{{$invoice->createdBy->name}}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button aria-expanded="false" aria-haspopup="true"
                                                                class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                                type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                        <div class="dropdown-menu tx-13">
                                                                <a class="dropdown-item"
                                                                   href=" {{route('invoices.edit',$invoice->id)}}">تعديل
                                                                    الفاتورة</a>
                                                                <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                                   data-toggle="modal" data-target="#delete_invoice"><i
                                                                        class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                                    الفاتورة</a>

                                                                <a class="dropdown-item"
                                                                   href="{{route('invoices.editStatus',$invoice->id)}}"><i
                                                                        class=" text-success fas
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    fa-money-bill"></i>&nbsp;&nbsp;تغير
                                                                    حالة
                                                                    الدفع</a>
                                                                <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                                   data-toggle="modal" data-target="#Transfer_invoice"><i
                                                                        class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الي
                                                                    الارشيف</a>
                                                                <a class="dropdown-item" href="{{route('invoices.printInvoices',$invoice->id)}}"><i
                                                                        class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                                    الفاتورة
                                                                </a>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach()


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- حذف الفاتورة -->
                    <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <form action="{{route('invoices.forceDeleted')}}" method="post">
                                    @csrf
                                </div>
                                <div class="modal-body">
                                    هل انت متاكد من عملية الحذف ؟
                                    <input type="hidden" name="invoice_id" id="invoice_id" value="">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                    <button type="submit" class="btn btn-danger">تاكيد</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ارشيف الفاتورة -->
                    <div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">ارشفة الفاتورة</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <form action="{{route('invoices.softDelete')}}" method="post">
                                    @csrf
                                </div>
                                <div class="modal-body">
                                    هل انت متاكد من عملية الارشفة ؟
                                    <input type="hidden" name="invoice_id" id="invoice_id" value="">
                                    <input type="hidden" name="id_page" id="id_page" value="2">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                    <button type="submit" class="btn btn-success">تاكيد</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>


<script>
    $('#delete_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
    })
</script>

<script>
    $('#Transfer_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
    })
</script>
@endsection
