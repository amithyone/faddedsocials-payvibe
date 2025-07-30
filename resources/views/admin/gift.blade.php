@extends('admin.layouts.app')
@section('panel')

    <div class="row mb-none-30">


        @if ($errors->any())
            <div class="alert alert-danger my-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session()->has('message'))
            <div class="alert alert-success my-3">
                {{ session()->get('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger mt-2">
                {{ session()->get('error') }}
            </div>
        @endif


        <div class="col-lg-6 col-md-9 mb-30">
            <div class="card">
                <div class="card-body">

                    <div class="row card-title">

                        <h6>List of Gift Items</h6>

                    </div>


                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>

                            @forelse($gift_items as $data)
                                <tr>

                                    <td>
                                        <img src="{{$data->image}}" alt="image">

                                    </td>
                                    <td>
                                        {{$data->title}}
                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-sm" href="/admin/delete-gift?id={{$data->id}}">Delete</a>
                                        <a class="btn btn-dark btn-sm" data-toggle="modal"
                                           data-target="#exampleModal{{$data->id}}"
                                           href="/admin/update-gift?id={{$data->id}}">Update</a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{$data->id}}" tabindex="-1"
                                             role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Update Gift
                                                            Item</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <form action="{{ route('admin.update.gift') }}"
                                                              method="POST" enctype="multipart/form-data">
                                                            @csrf

                                                            <label>Gift Name</label>
                                                            <input type="text" class="form-control" name="title"
                                                                   required value="{{$data->title}}">


                                                            <label>Gift Image</label>
                                                            <input type="file" class="form-control" name="image"
                                                                   value="{{$data->image}}">

                                                            <input type="text" class="form-control" name="id"
                                                                   value="{{$data->id}}" hidden>


                                                            <button type="submit" class="btn btn-primary my-3">Save
                                                                changes
                                                            </button>

                                                        </form>


                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                    </td>


                                </tr>

                            @empty

                            @endforelse

                            </tbody>

                            {{ paginateLinks($gift_items) }}


                        </table>
                    </div>
                </div>
            </div>


        </div>
        <div class="col-lg-6 col-md-9 mb-30">
            <div class="card">
                <div class="card-body">

                    <div class="row card-title">

                        <h6>New Gift Item</h6>

                    </div>

                    <form action="{{ route('admin.new.gift') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <label>Gift Name</label>
                        <input type="text" class="form-control" name="title" required>


                        <label class="my-2">Gift Image</label>
                        <input type="file" class="form-control" name="image">


                        <button type="submit" class="my-4 btn btn--primary w-100 btn-lg h-45">@lang('Continue')</button>
                    </form>
                </div>
            </div>
        </div>
        <hr>

        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">

                    <div class="row card-title">

                        <h6>Gift Orders</h6>

                    </div>


                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Gift Items</th>
                                <th>QTY</th>
                                <th>Address</th>
                                <th>Note</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($gift_orders as $data)
                                <tr>

                                    <td>
                                        {{$data->user->username}}
                                    </td>
                                    <td>
                                        {{$data->gift_item}}
                                    </td>
                                    <td>
                                        {{$data->qty}}
                                    </td>
                                    <td>
                                        {{$data->address}}
                                    </td>
                                    <td>
                                        {{$data->note}}
                                    </td>

                                    <td>
                                        @if($data->status == 0)
                                            <a href="#" class="btn btn-warning">Pending</a>
                                        @elseif($data->status == 1)
                                            <a href="#" class="btn btn-primary">Paid</a>
                                        @elseif($data->status == 2)
                                            <a href="#" class="btn btn-primary">Shipped Out</a>
                                        @elseif($data->status == 3)
                                            <a href="#" class="btn btn-primary">In Transit</a>
                                        @elseif($data->status == 4)
                                            <a href="#" class="btn btn-success">Delivered</a>
                                        @else
                                            <a href="#" class="btn btn-success">Returned</a>
                                        @endif
                                    </td>

                                    <td>
                                        <a class="btn btn-danger btn-sm"
                                           href="/admin/delete-gift-order?id={{$data->id}}">Delete</a>
                                        <a class="btn btn-dark btn-sm" data-toggle="modal"
                                           data-target="#exampleModal{{$data->id}}"
                                           href="/admin/update-gift?id={{$data->id}}">Update</a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{$data->id}}" tabindex="-1"
                                             role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Update Gift
                                                            Item</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <form action="{{ route('admin.update.order.gift') }}"
                                                              method="POST" enctype="multipart/form-data">
                                                            @csrf

                                                            <label>Select Status</label>
                                                            <select class="form-control" name="status">
                                                                <option value="0">Pending</option>
                                                                <option value="1">Paid</option>
                                                                <option value="2">Shipped</option>
                                                                <option value="3">In-Transit</option>
                                                                <option value="4">Delivered</option>
                                                                <option value="5">Returned</option>
                                                            </select>
                                                            <input name="id" value="{{$data->id}}" hidden>


                                                            <button type="submit" class="btn btn-primary my-3">Save
                                                                changes
                                                            </button>

                                                        </form>


                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                    </td>


                                </tr>

                            @empty

                            @endforelse

                            </tbody>

                            {{ paginateLinks($gift_orders) }}


                        </table>
                    </div>
                </div>
            </div>


        </div>

    </div>

@endsection

{{--@push('breadcrumb-plugins')--}}
{{--    <a href="{{route('admin.profile')}}" class="btn btn-sm btn-outline--primary"><i--}}
{{--            class="las la-user"></i>@lang('Profile Setting')</a>--}}
{{--@endpush--}}
