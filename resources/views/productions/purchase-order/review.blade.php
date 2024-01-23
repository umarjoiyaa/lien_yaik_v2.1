@extends('layouts.app')
@push('title')
    <title>PURCHASE ORDER REVIEW</title>
@endpush
@section('index')
    PURCHASE ORDER
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row px-2">
                <h5>PURCHASE ORDER REVIEW</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="" class="form-label">Customer Name</label>
                        <input type="text" readonly class="form-control" name="customer_name"
                            value="{{ $review->customer }}">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="" class="form-label">Part Name</label>
                        <input type="text" readonly class="form-control" value="{{ $review->product->name }}">
                    </div>
                </div>
                <div class="col-sm-4">
                    <label for="" class="form-label">Material</label>
                    <input type="text" readonly class="form-control" value="{{ $review->item->name }}">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="">Purchase Order No</label>
                        <input readonly type="text" name="order_no" class="form-control" value="{{ $review->order_no }}">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="">Order date</label>
                        <input readonly type="date" name="order_date" class="form-control"
                            value="{{ $review->order_date }}">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="" class="form-label">Customer Request Date</label>
                        <input readonly type="date" name="request_date" class="form-control"
                            value="{{ $review->req_date }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="" class="form-label">Ordered Unit</label>
                        <input readonly type="number" name="order_unit" class="form-control"
                            value="{{ $review->order_unit }}">
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-3 mt-5">
                    <h6>CAVITIES WEIGHT @ PER MOLD :</h6>
                </div>
                <div class="col-sm-3">
                    <label for="" class="form-abel">Cavities</label>
                    <input readonly type="number" name="cavities" class="form-control" id="input1"
                        value="{{ $review->cavities }}">
                </div>
                <div class="col-sm-3">
                    <label for="" class="form-label">Unit KG</label>
                    <input readonly type="number" name="unit_kg" id="input2" class="form-control"
                        value="{{ $review->unit_kg }}">
                </div>
                <div class="col-sm-3">
                    <label for="" class="form-label">Weight</label>
                    <input readonly type="text" name="weight" id="result" class="form-control"
                        value="{{ $review->per_mold }}">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-3 mt-2">
                    <h6>PURCHASE ORDER ISSUED BY :</h6>
                </div>
                <div class="col-sm-3">
                    <input readonly type="text" class="form-control" readonly name="issued_by"
                        value="{{ $review->user->name }}" />
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-3 mt-2">
                    <h6>PURCHASE ORDER APPROVED BY :</h6>
                </div>
                <div class="col-sm-3">
                    @php
                        $item = json_decode($review->approved);
                    @endphp
                    <select name="approved_by[]" disabled multiple class="form-select">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, $item) ? 'selected' : '' }}>
                                {{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <br>
            <div class="row px-3 d-flex justify-content-end">

                <div class="mt-2"><a class="btn btn-info" data-toggle="modal" data-target="#remarksModal">Proceed</a>
                </div>

            </div>
        </div>
    </div>
    {{-- MODAL --}}
    <div class="container">
        <div class="modal fade" id="remarksModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <h2 class="remarksHeading">{{ $review->order_no }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="closeremarks" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="">Comments</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <textarea name="remarks" id="orderRemarks" class="form-control" placeholder="Optional"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="accept-button btn btn-info" type="button" data-dismiss="modal">Accept</button>
                        <button class="reject-button btn btn-info" type="button" data-dismiss="modal">Reject</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL --}}
@endsection
@push('custom-scripts')
    <script>
        let accept = "{{ route('purchase.accept', $review->id) }}";
        let reject = "{{ route('purchase.reject', $review->id) }}";
        $('.accept-button').on('click', function() {
            $('.btn').attr('disabled', 'disabled');
            $remarks = $('#orderRemarks').val();
            $.ajax({
                url: accept,
                method: 'GET',
                data: {
                    remarks: $remarks
                },
                success: function(response) {
                    window.location.href = "{{ route('purchase.index') }}";
                },
                error: function(error) {}
            });
        });
        $('.reject-button').on('click', function() {
            $('.btn').attr('disabled', 'disabled');
            $remarks = $('#orderRemarks').val();
            $.ajax({
                url: reject,
                method: 'GET',
                data: {
                    remarks: $remarks
                },
                success: function(response) {
                    window.location.href = "{{ route('purchase.index') }}";
                },
                error: function(error) {}
            });
        });
    </script>
@endpush
