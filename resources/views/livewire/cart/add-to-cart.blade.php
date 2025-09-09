<div>
    <div class="quantity">
        <input type="number" class="qty-input" step="1" min="1" max="100" value="{{ $quantity }}" readonly title="Số lượng">
        <button class="quantity-plus qty-btn" wire:click="increment"><i class="far fa-chevron-up"></i></button>
        <button class="quantity-minus qty-btn" wire:click="decrement"><i class="far fa-chevron-down"></i></button>
    </div>
    <button class="th-btn add-to-cart-btn" onclick="addToCart(this)" wire:click="addToCart" data-product-id="{{$productId}}">
        @if($agent->isMobile())
            Giỏ hàng
            @else Thêm vào giỏ hàng
        @endif

    </button>
</div>
{{--<button class="th-btn style4">Đặt mua</button>--}}

