<?php
/**
 *
 * @var \App\Models\Shop\Product $product
 */
?>
<div class="sidemenu-wrapper side-menu-favorite" id="favoriteQuickViewContainer"  onclick="hideSidebarRight('favoriteQuickViewContainer')">
    <div class="sidemenu-content w-full lg:w-[450px]">
        <button class="closeButton sideMenuCls"  onclick="hideSidebarRight('favoriteQuickViewContainer')"><i class="far fa-times"></i></button>
        <div class="widget woocommerce widget_shopping_cart "><h3 class="widget_title">Sản phẩm yêu thích</h3>
            <div class="widget_shopping_cart_content">

                @if(empty($products) || $products->isEmpty())
                    <div>Chưa có sản phẩm nào trong danh sách yêu thích của bạn của bạn</div>
                    <a href="{{route('front.shop')}}" class="th-btn wc-forward mt-3 btn-sm">Khám phá thêm</a>
                @else

                    <ul class="woocommerce-mini-cart cart_list product_list_widget">
                        @foreach($products as $product)
                            <li class="woocommerce-mini-cart-item mini_cart_item">
                                <a href="#" wire:click="removeItem('{{ $product->id }}')" class="remove remove_from_cart_button"><i class="far fa-times"></i></a>
                                <a href="{{$product->link()}}"><img class="rounded" src="{{$product->getFirstImageThumb()}}" alt="{{$product->name}}">
                                    {{$product->name}}</a>
                                <span class="quantity font-bold">
                            <span class="woocommerce-Price-amount amount font-bold">{!! zi_format_currency($product->price) !!}</span>
                        </span>
                            </li>
                        @endforeach

                    </ul>

            </div>
            @endif
        </div>
    </div>
</div>
