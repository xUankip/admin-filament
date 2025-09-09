<div class="row">
    <div class="col-12">
        <select @change="$wire.handChangeProvince()" name="province_code" wire:model="province_code" class="form-control">
            <option value=''>Chọn tỉnh, thành phố</option>
            @foreach($lsProvince as $province)
                <option value="{{ $province->code }}">{{ $province->full_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <select @change="$wire.handChangeDistrict()" name="district_code" wire:model="district_code" class="form-control">
            <option value=''>Quận/Huyện</option>
            @if(!empty($lsDistrict) && $lsDistrict->isNotEmpty())
                @foreach($lsDistrict as $district)
                    <option value="{{ $district->code }}">{{ $district->full_name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-12 form-control">
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
