<?php
/**
 * @var \App\Models\Shop\Product $product
 */
?>
<div class="container">

    @if(empty($products) || empty($subTotal))
        <div class="text-center flex justify-center">{{ svg('heroicon-o-shopping-cart',['class'=>'w-20 h-20 text-gray-500']) }} </div>
        <div class="text-center mt-2">Bạn chưa có sản phẩm nào trong giỏ hàng.</div>
        <div class="text-center"><a href="{{route('front.shop')}}" class="th-btn wc-forward mt-3 btn-sm">Khám phá thêm</a></div>
    @else

        <div class="woocommerce-notices-wrapper">
            <div class="woocommerce-message">Thanh toán sớm để nhận thêm tích điểm ưu đãi</div>
        </div>

        <table class="cart_table">
            <thead>
            <tr>
                <th class="cart-col-image">Hình ảnh</th>
                <th class="cart-col-productname">Tên sản phẩm</th>
                <th class="cart-col-price">Giá bán</th>
                <th class="cart-col-quantity">Số lượng</th>
                <th class="cart-col-total">Thành tiền</th>
                <th class="cart-col-remove"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr class="cart_item">
                    <td data-title="Product"><a class="cart-productimage" target="_blank" title="Xem {{$product->name}}" href="{{$product->link()}}">
                            <img width="91" height="91" class="rounded" src="{{$product->getFirstImageThumb()}}" alt="Image"></a></td>
                    <td data-title="Name"><a class="cart-productname" href="{{$product->link()}}">{{$product->name}}</a></td>
                    <td data-title="Price"><span class="amount">{!! zi_format_currency($product->price) !!}</span></td>
                    <td data-title="Quantity">
                        <div class="quantity">
                            <button class="quantity-minus qty-btn" wire:click="decrement('{{ $product->id }}')"><i class="far fa-minus"></i></button>
                            <input type="number" class="qty-input" value="{{$product->buyQty}}" min="1" max="99">
                            <button class="quantity-plus qty-btn" wire:click="increment('{{ $product->id }}')"><i class="far fa-plus"></i></button>
                        </div>
                    </td>
                    <td data-title="Total"><span class="amount">{!! zi_format_currency($product->price*$product->buyQty) !!}</span></td>
                    <td data-title="Remove"><a href="javascript:void(0)" wire:confirm="Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?" wire:click="removeItem('{{ $product->id }}')" class="remove"><i class="fal fa-trash-alt"></i></a>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td colspan="6" class="actions">
                    {{--<div class="th-cart-coupon"><input type="text" class="form-control" placeholder="Coupon Code...">
                        <button type="submit" class="th-btn">Apply Coupon</button>
                    </div>--}}
                    {{-- <div class=" th-cart-coupon">
                         <a href="{{route('front.shop')}}" class="th-btn ">Tiếp tục mua hàng</a>
                     </div>--}}
                    <div class=" flex-col items-start">
                        <div class="font-bold">Tổng giá trị đơn hàng</div>
                        <div class="text-danger font-bold text-lg">{!! zi_format_currency($subTotal) !!}</div>
                    </div>

                </td>
            </tr>
            </tbody>
        </table>


        <form action="#" wire:submit="booking" class="woocommerce-checkoutx mt-40">
            <div class="row">
                <div class="col-lg-6"><h2 class="h4">Thông tin giao hàng</h2>
                    <div class="row">

                        <div class="col-md-6 form-group">
                            <input type="text" class="form-control" wire:model="name" placeholder="Họ và tên">
                            <div class="flex">
                                @error('name') <small class=" ms-2 error text-danger absolute">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <input type="tel" wire:model="phone" class="form-control" placeholder="Số điện thoại">
                            <div class="flex">
                                @error('phone') <small class=" ms-2 error text-danger absolute">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-12 form-group">
                            <input type="text" class="form-control" wire:model="address" placeholder="Địa chỉ">
                            <div class="flex">
                                @error('address') <small class=" ms-2 error text-danger absolute">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="email" class="form-control" wire:model="email" placeholder="Email liên hệ (nếu có)">
                            <div class="flex">
                                @error('email') <small class=" ms-2 error text-danger ">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        {{--<div class="col-12 form-group"><input type="checkbox" id="accountNewCreate"> <label for="accountNewCreate">Create An Account?</label></div>--}}
                    </div>
                </div>
                <div class="col-lg-6"><p id="ship-to-different-address">
                        <input id="ship-to-different-address-checkbox" type="checkbox" wire:model="isShippingOther" name="ship_to_different_address" value="1">
                        <label for="ship-to-different-address-checkbox">Vận chuyển đến địa chỉ khác? <span class="checkmark"></span></label></p>
                    <div class="shipping_address" style="@if(!$isShippingOther) display: none @endif">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <input type="text" wire:model="nameOther" class="form-control" placeholder="Họ và tên người nhận">
                                <div class="flex">
                                    @error('nameOther') <small class=" ms-2 error text-danger absolute">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6 form-group"><input type="tel" wire:model="phoneOther" class="form-control" placeholder="Số điện thoại người nhận">
                                <div class="flex">
                                    @error('phoneOther') <small class=" ms-2 error text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <input type="text" class="form-control" wire:model="addressOther" placeholder="Địa chỉ người nhận">
                                <div class="flex">
                                    @error('addressOther') <small class=" ms-2 error text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--<livewire:vn-location-select/>--}}
                    <div class="row">
                        <div class="col-12 form-group">
                            <select @change="$wire.handChangeProvince()" name="province_code" wire:model="province_code" class="form-control">
                                <option value=''>Chọn tỉnh, thành phố</option>
                                @foreach($lsProvince as $province)
                                    <option value="{{ $province->code }}">{{ $province->full_name }}</option>
                                @endforeach
                            </select>
                            <div class="flex">
                                @error('province_code') <small class=" ms-2 error text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-12 form-group">
                            <select @change="$wire.handChangeDistrict()" name="district_code" wire:model="district_code" class="form-control">
                                <option value=''>Quận/Huyện</option>
                                @if(!empty($lsDistrict) && $lsDistrict->isNotEmpty())
                                    @foreach($lsDistrict as $district)
                                        <option value="{{ $district->code }}">{{ $district->full_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="flex">
                                @error('district_code') <small class=" ms-2 error text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-12 form-group">
                            <select @change="$wire.handChangeWard()" name="ward_code" wire:model="ward_code" class="form-control">
                                <option value=''>Chọn Xã/ Phường</option>
                                @if(!empty($lsWard) && $lsWard->isNotEmpty())
                                    @foreach($lsWard as $ward)
                                        <option value="{{ $ward->code }}">{{ $ward->full_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="flex">
                                @error('ward_code') <small class=" ms-2 error text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                    </div>

                    <div class="col-12 form-group"><textarea wire:model="notes" cols="20" rows="5" class="form-control" placeholder="Ghi chú về đơn hàng (thời gian giao, yêu cầu thêm...)"></textarea></div>
                </div>
            </div>
            <div class="wc-proceed-to-checkout mb-30">
                <button type="button" wire:click="booking()" class="th-btn">Xác nhận đặt hàng</button>
            </div>
        </form>

    @endif
</div>
