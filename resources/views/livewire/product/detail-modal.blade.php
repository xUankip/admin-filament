<?php
/**
 * @var \App\Models\Shop\Product $product
 */
?>
<div>
    <div class="container bg-white rounded-10 p-5 shadow-lg text-left">
        <div class="row gx-60">
            <div class="col-lg-6">
                <div class="product-big-img lg:min-h-[450px]">
                    <div class="img"><img id="productImage-{{$product->id}}" src="{{$product->getFirstImageMedium()}}" alt="{{$product->name}}"></div>
                </div>
                @if(!empty($product->images) && count($product->images)>1)
                    <div class="flex gap-4 py-4 justify-center overflow-x-auto">
                        @foreach($product->images as $image)
                            <img src="{{zi_image($image)}}"
                                 alt="{{$product->name}} {{$loop->index+1}}"
                                 class="size-16 sm:size-20 object-cover rounded-md cursor-pointer opacity-60 hover:opacity-100 transition duration-300"
                                 onclick="changeImageProduct(this.src,{{$product->id}})">
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-lg-6 ">

                <div class="product-about">
                    <p class="price">{!! zi_format_currency($product->price) !!}
                        @if(!empty($product->old_price))
                            <del>{!! zi_format_currency($product->old_price) !!}</del>
                        @endif
                    </p>
                    @if($product->price_is_demo)
                        <div class="checklist list-one-column mb-40">
                            <ul>
                                <li class="flex">Giá sản phẩm này chỉ mang tính tham khảo. Hãy liên hệ để được nhận giá tốt nhất theo từng ngày
                                </li>
                            </ul>
                        </div>
                    @endif
                    <h2 class="product-title">{{$product->name}}</h2>
                    <div class="product-rating">
                        @foreach(range(0, 4) as $i)
                            @php($class = $i < $product->rate_star ? 'text-orange-300' : 'text-gray-300')
                            {{ svg('heroicon-m-star', ['class' => 'w-5 ' . $class . ' h-5 me-1']) }}
                        @endforeach

                        <span class="count">{{($product->rate_count>0?$product->rate_count:1)}}</span> lượt đánh giá
                    </div>
                    <div class="text">
                        {!! $product->brief??$product->policy !!}
                    </div>
                    <div class="mt-2 link-inherit">
                        <p><strong class="text-title me-3">Tình trạng:</strong>
                            <span class="stock in-stock"><i class="far fa-check-square me-2 ms-1"></i>
                        @if($product->isCanNotBuy())
                                    Hết hàng
                                @else
                                    Còn hàng
                                @endif
                            </span>
                        </p>
                    </div>
                    <div class="actions">
                        @if($product->price_is_demo || $product->isCanNotBuy())
                            <div>
                                <a class="th-btn add-to-cart-btn" href="{{route('front.contact',['#contact-form','subject'=>'Liên hệ mua sản phẩm mã ['.$product->id.'] ['.$product->name.']'])}}">
                                    Liên hệ mua hàng
                                </a>
                            </div>
                        @else
                            @livewire('cart.add-to-cart', ['productId' => $product->id])
                        @endif
                        @livewire('favorite.add-to-favorite', ['productId' => $product->id])
                    </div>
                    <div class="checklist list-one-column mb-40">
                        <ul>
                            @if($product->free_ship)
                                <li>Sản phầm này miễn phí vận chuyển</li>
                            @endif
                            @if($product->price_is_has_vat)
                                <li>Giá bán đã bao gồm VAT</li>
                            @endif
                            @if($product->backorder)
                                <li>Sản phầm có thể đổi trả</li>
                            @endif
                            <li>Liên hệ hotline <a href="tel:{{$websiteConfig['website']['phone']??''}}">{{$websiteConfig['website']['phone']??''}}</a> để nhận tư vấn tốt nhất</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
