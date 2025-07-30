@extends($activeTemplate.'layouts.main')
@section('content')
    <div class="container">
        <div class="row gy-4 mt-3">
            <div class="col-xl-12 col-sm-12">

                <img src="{{url('')}}/assets/assets2/images/slider/slider5.png" alt="image">

            </div>

            <div class="col-xl-12 col-sm-12">

                <div class="row p-4">
                    <div class="col-6">
                        <div class="dashboard-widget">
                            <img src="{{url('')}}/assets/assets2/images/shapes/widget-shape1.png" alt=""
                                 class="dashboard-widget__shape one">
                            <img src="{{url('')}}/assets/assets2/images/shapes/widget-shape2.png" alt=""
                                 class="dashboard-widget__shape two">
                            <span class="dashboard-widget__icon">
                                <img src="{{url('')}}/assets/assets2/images/icons/dashboard-widget-icon1.svg" alt="">
                            </span>
                            <div class="dashboard-widget__content flx-between gap-1 align-items-end">
                                <div>
                                    <h4 class="dashboard-widget__number mb-1 mt-3">{{$order_completed}}</h4>
                                    <span class="dashboard-widget__text font-14">Order Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="dashboard-widget">
                            <img src="{{url('')}}/assets/assets2/images/shapes/widget-shape1.png" alt=""
                                 class="dashboard-widget__shape one">
                            <img src="{{url('')}}/assets/assets2/images/shapes/widget-shape2.png" alt=""
                                 class="dashboard-widget__shape two">
                            <span class="dashboard-widget__icon">
                                <img src="{{url('')}}/assets/assets2/images/icons/dashboard-widget-icon3.svg" alt="">
                            </span>
                            <div class="dashboard-widget__content flx-between gap-1 align-items-end">
                                <div>
                                    <h4 class="dashboard-widget__number mb-1 mt-3 text-warning">{{$order_pending}}</h4>
                                    <span class="dashboard-widget__text font-14">Order Pending</span>
                                </div>
                            </div>
                        </div>


                        {{--                    <div class=" col-xl-12 col-md-12 my-3 col-sm-12 d-flex justify-content-end" >--}}
                        {{--                        <div class=" card border-0  p-4 ">--}}
                        {{--                            <form action="" class="search-input">--}}
                        {{--                                <span class="icon"><img src="{{ url('') }}/{{url('')}}/assets/assets2/assets2/images/icons/search-dark.svg" alt=""></span>--}}
                        {{--                                <input type="text" name="search" value="{{ request()->search }}" class="common-input common-input--md common-input--bg pill w-100"--}}
                        {{--                                       placeholder="Search by Trx">--}}
                        {{--                            </form>--}}

                        {{--                        </div>--}}


                        {{--                    </div>--}}


                    </div>


                    {{--                <div class="col-xl-12 col-sm-12 p-2">--}}
                    {{--                    <div class="dashboard-widget">--}}
                    {{--                        <h5 class="mt-4 mb-4">@lang('Latest Order History')</h5>--}}

                    {{--                        <div class="dashboard-body__item">--}}
                    {{--                            <div class="table-responsive">--}}
                    {{--                                <table class="table style-two">--}}
                    {{--                                    <thead>--}}
                    {{--                                    <tr>--}}
                    {{--                                        <th>Order ID</th>--}}
                    {{--                                        <th>Qty</th>--}}
                    {{--                                        <th>Amount(NGN)</th>--}}
                    {{--                                        <th>Date</th>--}}

                    {{--                                    </tr>--}}
                    {{--                                    @forelse($orders as $order)--}}
                    {{--                                        @php--}}
                    {{--                                            $qty = @$order->orderItems->count();--}}
                    {{--                                            $perUnitPrice = @$order->orderItems->first()->price;--}}
                    {{--                                        @endphp--}}
                    {{--                                        <tr class="text-small">--}}
                    {{--                                            <td class="text-dark">--}}

                    {{--                                                <a class="text-primary" href="{{ route('user.order.details', $order->id) }}">--}}
                    {{--                                                    <i class="fa fa-shopping-bag"></i>--}}

                    {{--                                                    {{ $order->id }}--}}
                    {{--                                                </a>--}}
                    {{--                                            </td>--}}
                    {{--                                            <td>--}}
                    {{--                                                <span>{{ @$order->orderItems->count() }}</span>--}}
                    {{--                                            </td>--}}
                    {{--                                            <td>--}}
                    {{--                                                {{showAmount($order->total_amount)}}--}}
                    {{--                                            </td>--}}
                    {{--                                            <td>--}}
                    {{--                                                {{ diffForHumans($order->created_at) }}--}}
                    {{--                                            </td>--}}





                    {{--                                            <td>--}}
                    {{--                                                <div class="col">--}}
                    {{--                                                    <div class="action-buttons">--}}
                    {{--                                                        <a class="btn btn-dark btn-sm"--}}
                    {{--                                                           href="{{ route('user.order.details', $order->id) }}">--}}
                    {{--                                                            <i class="fa fa-shopping-bag"></i>--}}
                    {{--                                                        </a>--}}
                    {{--                                                    </div>--}}
                    {{--                                                </div>--}}

                    {{--                                            </td>--}}


                    {{--                                        </tr>--}}
                    {{--                                    @empty--}}
                    {{--                                        <div class="card">--}}
                    {{--                                            <div class="card-body text-center p-4">--}}

                    {{--                                                <svg width="40" height="40" viewBox="0 0 25 25" fill="none"--}}
                    {{--                                                     xmlns="http://www.w3.org/2000/svg">--}}
                    {{--                                                    <path--}}
                    {{--                                                        d="M0.699126 22.1299L11.4851 0.936473C11.6065 0.697285 11.7856 0.49768 12.0036 0.358621C12.2215 0.219562 12.4703 0.146179 12.7237 0.146179C12.9772 0.146179 13.2259 0.219562 13.4439 0.358621C13.6618 0.49768 13.841 0.697285 13.9624 0.936473L24.7483 22.1299C24.8658 22.3607 24.9253 22.6205 24.9209 22.8835C24.9165 23.1466 24.8484 23.4039 24.7234 23.6301C24.5983 23.8562 24.4206 24.0434 24.2078 24.1732C23.995 24.303 23.7543 24.3708 23.5097 24.3701H1.93781C1.69314 24.3708 1.45252 24.303 1.23968 24.1732C1.02684 24.0434 0.849131 23.8562 0.724084 23.6301C0.599037 23.4039 0.530969 23.1466 0.526592 22.8835C0.522216 22.6205 0.581682 22.3607 0.699126 22.1299ZM14.2252 14.2749L14.9815 9.39487C15.0039 9.25037 14.9967 9.10237 14.9605 8.96116C14.9243 8.81995 14.8599 8.6889 14.7719 8.57713C14.6838 8.46536 14.5742 8.37554 14.4506 8.31391C14.327 8.25228 14.1925 8.22033 14.0563 8.22026H11.3912C11.255 8.22033 11.1204 8.25228 10.9969 8.31391C10.8733 8.37554 10.7637 8.46536 10.6756 8.57713C10.5876 8.6889 10.5232 8.81995 10.487 8.96116C10.4508 9.10237 10.4436 9.25037 10.466 9.39487L11.2223 14.2749H14.2252ZM14.7882 18.1096C14.7882 17.5208 14.5707 16.9561 14.1835 16.5398C13.7964 16.1234 13.2713 15.8895 12.7237 15.8895C12.1762 15.8895 11.6511 16.1234 11.2639 16.5398C10.8768 16.9561 10.6593 17.5208 10.6593 18.1096C10.6593 18.6984 10.8768 19.2631 11.2639 19.6794C11.6511 20.0957 12.1762 20.3296 12.7237 20.3296C13.2713 20.3296 13.7964 20.0957 14.1835 19.6794C14.5707 19.2631 14.7882 18.6984 14.7882 18.1096Z"--}}
                    {{--                                                        fill="#EA4335"/>--}}
                    {{--                                                </svg>--}}
                    {{--                                                <br><br>--}}

                    {{--                                                <h6>No data found</h6>--}}


                    {{--                                            </div>--}}
                    {{--                                        </div>--}}
                    {{--                                    @endforelse--}}

                    {{--                                    </thead>--}}

                    {{--                                    <tbody>--}}
                    {{--                                    </tbody>--}}
                    {{--                                </table>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}


                </div>

                {{--            <div class="col d-flex justify-content-center my-3">--}}
                {{--                {{ paginateLinks($orders) }}--}}
                {{--            </div>--}}

            </div>


            <div class="col-xl-6 col-sm-12 my-3">

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
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger mt-2">
                        {{ session()->get('error') }}
                    </div>
                @endif


                <div class="dashboard-widget">

                    <div class="card border-0 p-3">

                        <a href="#" data-toggle="modal"
                           data-target="#exampleModal" class="btn btn-dark btn-sm my-4">View Catalogue of Gift Items</a>



                        <h6 class="d-flex justify-content-start">

                            Send Gift Items to your loved ones around the globe

                        </h6>

                        <form action="/user/submit-giftorder" class="my-3" method="post">
                            @csrf

                            <label class="label my-1"> Select Item to send</label>
                            <select required class="form-control my-2" name="gift_item">
                                <option>Select Gift</option>
                                @foreach($gift_items as $data)
                                    <option value="{{$data->id}}">{{$data->title}}</option>
                                @endforeach

                            </select>

                            <label class="label my-1"> Enter Receiver full address</label>
                            <input type="text" class="form-control" name="address" required
                                   placeholder="Flat 100, HOCHRID ROAD UNITED STATE">

                            <label class="label my-1"> Enter Quantity</label>
                            <input type="number" class="form-control" name="qty" required placeholder="1">


                            <label class="label my-2"> Enter Note</label>
                            <textarea type="text" class="form-control" name="note"
                                      placeholder="Add a note to my order"></textarea>

                            <button type="submit"
                                    style="background: linear-gradient(90deg, #0F0673 0%, #B00BD9 100%); color:#ffffff;"
                                    class="btn btn-main btn-lg w-100 pill my-5">
                            <svg class="cart me-2" width="16" height="16" viewBox="0 0 18 18" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17.5158 2.01275C17.9478 0.81775 16.7898 -0.34025 15.5948 0.0927503L0.989804 5.37475C-0.209196 5.80875 -0.354196 7.44475 0.748804 8.08375L5.4108 10.7828L9.5738 6.61975C9.76241 6.43759 10.015 6.3368 10.2772 6.33908C10.5394 6.34135 10.7902 6.44652 10.9756 6.63193C11.161 6.81734 11.2662 7.06815 11.2685 7.33035C11.2708 7.59255 11.17 7.84515 10.9878 8.03375L6.8248 12.1968L9.5248 16.8587C10.1628 17.9617 11.7988 17.8158 12.2328 16.6178L17.5158 2.01275Z"
                                    fill="white"/>
                            </svg>

                            CONTINUE
                            </button>


                        </form>

                    </div>

                </div>
            </div>

            <div class="col-lg-6 col-sm-12 my-3">

                <div class="dashboard-widget">

                    <div class="card border-0 p-3">

                        <h6 class="d-flex justify-content-center">

                            Recent Orders

                        </h6>

                        <div class="dashboard-widget">
                            <h6 class="text-muted mt-4 mb-4">@lang('Latest Order History')</h6>

                            <div class="dashboard-body__item">
                                <div class="table-responsive">
                                    <table class="table style-two">
                                        <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Status</th>
                                            <th>Action</th>

                                        </tr>
                                        </thead>

                                            @forelse($orders as $order)
                                            <tr>
                                            <td>
                                                {{$order->id}}
                                            </td>
                                                <td>
                                                    @if($order->status == 0)
                                                        <a href="#" class="btn btn-warning">Pending</a>
                                                    @elseif($order->status == 1)
                                                        <a href="#" class="btn btn-primary">Paid</a>
                                                    @elseif($order->status == 2)
                                                        <a href="#" class="btn btn-primary">Shipped Out</a>
                                                    @elseif($order->status == 3)
                                                        <a href="#" class="btn btn-primary">In Transit</a>
                                                    @elseif($order->status == 4)
                                                        <a href="#" class="btn btn-success">Delivered</a>
                                                    @else
                                                        <a href="#" class="btn btn-success">Returned</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($order->status == 0)
                                                    <a class="btn btn-dark btn-sm" href="https://wa.me/+2349036138449?text=Hello+Chequest+PL+i+want+to+place+gift+orders+my+orderID+is+{{$order->id}}+my+username+is+{{Auth::user()->username}}" target="_blank"><i class="fa fa-money-bill"></i> Make Payment</a>
                                                    @elseif($order->status == 4)
                                                        <a class="btn btn-danger btn-sm" href="https://wa.me/+2349036138449?text=Hello+Chequest+PL+i+want+report+this+order+my+orderID+is+{{$order->id}}+my+username+is+{{Auth::user()->username}}"><i class="fa fa-contact-card"></i> Report Order</a>
                                                    @else
                                                        <a class="btn btn-dark btn-sm" href="/user/track-order?id={{$order->id}}"><i class="fa fa-location-arrow"></i> Track Order</a>
                                                    @endif

                                                </td>
                                            </tr>



                                            @empty
                                                <div class="card border-0">
                                                    <div class="card-body text-center p-4">

                                                        <svg width="40" height="40" viewBox="0 0 25 25" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M0.699126 22.1299L11.4851 0.936473C11.6065 0.697285 11.7856 0.49768 12.0036 0.358621C12.2215 0.219562 12.4703 0.146179 12.7237 0.146179C12.9772 0.146179 13.2259 0.219562 13.4439 0.358621C13.6618 0.49768 13.841 0.697285 13.9624 0.936473L24.7483 22.1299C24.8658 22.3607 24.9253 22.6205 24.9209 22.8835C24.9165 23.1466 24.8484 23.4039 24.7234 23.6301C24.5983 23.8562 24.4206 24.0434 24.2078 24.1732C23.995 24.303 23.7543 24.3708 23.5097 24.3701H1.93781C1.69314 24.3708 1.45252 24.303 1.23968 24.1732C1.02684 24.0434 0.849131 23.8562 0.724084 23.6301C0.599037 23.4039 0.530969 23.1466 0.526592 22.8835C0.522216 22.6205 0.581682 22.3607 0.699126 22.1299ZM14.2252 14.2749L14.9815 9.39487C15.0039 9.25037 14.9967 9.10237 14.9605 8.96116C14.9243 8.81995 14.8599 8.6889 14.7719 8.57713C14.6838 8.46536 14.5742 8.37554 14.4506 8.31391C14.327 8.25228 14.1925 8.22033 14.0563 8.22026H11.3912C11.255 8.22033 11.1204 8.25228 10.9969 8.31391C10.8733 8.37554 10.7637 8.46536 10.6756 8.57713C10.5876 8.6889 10.5232 8.81995 10.487 8.96116C10.4508 9.10237 10.4436 9.25037 10.466 9.39487L11.2223 14.2749H14.2252ZM14.7882 18.1096C14.7882 17.5208 14.5707 16.9561 14.1835 16.5398C13.7964 16.1234 13.2713 15.8895 12.7237 15.8895C12.1762 15.8895 11.6511 16.1234 11.2639 16.5398C10.8768 16.9561 10.6593 17.5208 10.6593 18.1096C10.6593 18.6984 10.8768 19.2631 11.2639 19.6794C11.6511 20.0957 12.1762 20.3296 12.7237 20.3296C13.2713 20.3296 13.7964 20.0957 14.1835 19.6794C14.5707 19.2631 14.7882 18.6984 14.7882 18.1096Z"
                                                                fill="#EA4335"/>
                                                        </svg>
                                                        <br><br>

                                                        <p>No order found</p>


                                                    </div>
                                                </div>
                                            @endforelse

                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>

    </div>

@endsection



    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1"
         role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
{{--                    <h5 class="modal-title" id="exampleModalLabel">Update Gift--}}
{{--                        Item</h5>--}}
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


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

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @forelse($gift_items as $data)
                                        <tr>
                                            <td>
                                                <img src="{{$data->image}}" alt="image" height="50" width="50">
                                            </td>
                                            <td>
                                                <p style="font-size: 10px" class="text-dark text-small">{{$data->title}}</p>
                                            </td>

                                        </tr>

                                    @empty

                                    @endforelse

                                    </tbody>



                                </table>
                            </div>
                        </div>
                    </div>





                </div>


            </div>
        </div>
    </div>



