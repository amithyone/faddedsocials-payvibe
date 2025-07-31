@extends($activeTemplate . 'layouts.main2')
@section('content')

    <div class="pc-container">
        <div class="pc-content">
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


            <div class="dashboard-body__content">
                <div class="dashboard-body__item-wrapper">


                    <div>


                        <div class="carousel my-3 mb-4">
                            <div class="carousel-images">
                                <a href="/products"><img
                                        src="https://fadded.tomitechltd.com/assets/assets/images/slider/Banner1.jpg"
                                        alt="Banner 1"></a>
                                <a href="https://t.me/faddedsocialsgroup"><img
                                        src="https://fadded.tomitechltd.com/assets/assets/images/slider/Banner2.jpg"
                                        alt="Banner 2"></a>
                                <a href="https://linktr.ee/faddedsocials"><img
                                        src="https://fadded.tomitechltd.com/assets/assets/images/slider/Banner3.jpg"
                                        alt="Banner 3"></a>
                                <a href="https://chat.whatsapp.com/CuSck16moiWFH8njS7pE7C"><img
                                        src="https://fadded.tomitechltd.com/assets/assets/images/slider/Banner4.jpg"
                                        alt="Banner 4"></a>
                                <a href="/products"><img
                                        src="https://fadded.tomitechltd.com/assets/assets/images/slider/Banner5.jpg"
                                        alt="Banner 5"></a>
                            </div>
                            <div class="carousel-buttons">
                                <button id="prevBtn">&#10094;</button>
                                <button id="nextBtn">&#10095;</button>
                            </div>
                        </div>

                        <script>
                            const carouselImages = document.querySelector('.carousel-images');
                            const images = document.querySelectorAll('.carousel-images a'); // Updated selector to include anchor tags
                            const totalImages = images.length;
                            let currentIndex = 0;

                            function showNextImage() {
                                currentIndex = (currentIndex + 1) % totalImages;
                                updateCarousel();
                            }

                            function showPreviousImage() {
                                currentIndex = (currentIndex - 1 + totalImages) % totalImages;
                                updateCarousel();
                            }

                            function updateCarousel() {
                                const translateX = -currentIndex * 100;
                                carouselImages.style.transform = `translateX(${translateX}%)`;
                            }

                            document.getElementById('nextBtn').addEventListener('click', showNextImage);
                            document.getElementById('prevBtn').addEventListener('click', showPreviousImage);

                            setInterval(showNextImage, 3000);
                        </script>


                    </div>

                    <div class="">


                        <div class="col d-flex justify-content-start mt-5">
                            <select id="urlSelect" onchange="redirectToUrl()" class="btn btn-sm btn-dark">
                                <option value="">Shop by Categories</option>
                                @foreach($categories as $data)
                                    <option
                                        value="{{url('')}}/category-products/{{$data->name}}/{{$data->id}}">{{$data->name}}
                                    </option>
                                @endforeach
                            </select>

                            <script>
                                function redirectToUrl() {
                                    var selectElement = document.getElementById("urlSelect");
                                    var selectedUrl = selectElement.options[selectElement.selectedIndex].value;
                                    if (selectedUrl !== "") {
                                        window.location.href = selectedUrl;
                                    }
                                }
                            </script>

                        </div>

                        <div>
                            <h5 class="d-flex justify-content-start mt-5">Our Recent Products 👌</h5>
                        </div>
                        <div class="col-12">
                            @forelse($categories as $category)
                                @php
                                    $products = $category->products;
                                @endphp
                                <div class="catalog-item-wrapper mb-2">
                                    <div class="d-grid gap-2 mb-2">
                                        <strong>
                                            <p style="font-size: 11px; background: linear-gradient(90deg, #20ccb4 0%, #3f8a7f 100%); border-radius:10px; color: white"
                                               class="p-2">{{ __($category->name) }}</p>
                                        </strong>
                                    </div>

                                </div>




                                <div class="col-12">
                                    @php
                                        $productCount = 0;
                                        $adPositions = [3, 4, 5]; // Random positions for ads
                                        $currentAdPosition = $adPositions[array_rand($adPositions)];
                                    @endphp
                                    @foreach ($products->take(5) as $product)
                                        @include($activeTemplate . 'partials/products')
                                        @php $productCount++; @endphp
                                        
                                        {{-- Show ad after random position (3, 4, or 5 products) --}}
                                        @if($productCount == $currentAdPosition)
                                            @include($activeTemplate . 'partials/ads')
                                        @endif
                                    @endforeach
                                </div>


                                <div class="col-12 d-flex justify-content-end mb-4">
                                    <a style="background: linear-gradient(90deg, #000000 0%, #000000 100%); border-radius:10px; color: white"
                                       href="{{ route('category.products', ['search' => request()->search, 'slug' => slug($category->name), 'id' => $category->id]) }}"
                                       class="btn btn-main btn-lg w-100 pill">
                                        @lang('View All')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor"
                                             class="bi bi-arrow-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                  d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                        </svg>
                                    </a>
                                </div>

                            @empty
                                <div class="empty-data text-center">
                                    <div class="thumb">
                                        <img src="{{ asset($activeTemplateTrue . 'images/not-found.png') }}">
                                    </div>

                                    <h4 class="title">@lang('No result found for "' . request()->search . '"')</h4>
                                </div>
                            @endforelse
                            {{ paginateLinks($categories) }}


                                <div class="col-12 my-4">
                                    @auth

                                        <div class="card-title mt-3 text-center">
                                            <h6 style="background: #565656; padding: 10px; border-radius: 10px; color: white"
                                                class="text-left">RECENT ORDER</h6>
                                        </div>


                                        <div style="height:400px; width:100%; overflow-y: scroll;" class="card">
                                            <div class="card-body">


                                                <div class="dashboard-body__item">
                                                    <div class="table-responsive">
                                                        <table class="table style-two">
                                                            <thead>
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Time</th>

                                                            </tr>

                                                            @if($bought_qty == 0)
                                                            @else
                                                                @foreach($bought as $data)

                                                                    <tr>
                                                                        <td>{{\Illuminate\Support\Str::limit($data->user_name,4, '.')}}, <span style="color: #038f8b">just bought</span><br/> {{\Illuminate\Support\Str::limit($data->item,
                                    16, '...')}}<span style="color: #000000">₦{{number_format($data->amount)}}</span></td>
                                                                        <td>{{ diffForHumans($data->created_at) }}</td>
                                                                    </tr>

                                                                @endforeach
                                                            @endif



                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>





                                            </div>
                                        </div>
                                    @else

                                    @endauth

                                </div>

                        </div>

@endsection

