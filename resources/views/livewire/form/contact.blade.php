<form action="" wire:submit="submit" method="POST" class="contact-form input-smoke ajax-contact">
    <div class="row">
        <div class="form-group col-md-4">
            <input type="text" class="form-control" wire:model.defer="name" name="name" id="name" placeholder="Họ và tên"> <i class="fal fa-user"></i>
        </div>
        <div class="form-group col-md-4">
            <input type="email" class="form-control" wire:model.defer="email" name="email" id="email" placeholder="Email"> <i class="fal fa-envelope"></i>
        </div>
        <div class="form-group col-md-4">
            <input type="tel" class="form-control" wire:model.defer="phone" name="number" id="number" placeholder="Điện thoại liên hệ"> <i class="fal fa-phone"></i>
        </div>
        <div class="form-group col-12">
            <textarea name="message" id="message" wire:model.defer="message" cols="30" rows="3" class="form-control" placeholder="Nội dung tin nhắn"></textarea> <i class="fal fa-pencil"></i>
        </div>
        <div class="form-btn col-12">
            <button class="th-btn btn-fw">Gửi tin nhắn<i class="fas fa-chevrons-right ms-2"></i></button>
        </div>
    </div>
    <p class="form-messages mb-0 mt-3"></p>
</form>
