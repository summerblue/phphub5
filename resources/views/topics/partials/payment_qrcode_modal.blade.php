

<div class="modal fade" id="payment-qrcode-modal" tabindex="-1" role="" aria-labelledby="exampleModalLabel" >
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="exampleModalLabel">
            <img src="{{ $topic->user->present()->gravatar }}" style="width:65px; height:65px;" class="img-thumbnail avatar" />
        </h4>
      </div>

        <div class="modal-body text-center">
            <p class="text-md">
                如果觉得我的文章对您有用，请随意打赏。你的支持将鼓励我继续创作！
            </p>
            <img class="payment-qrcode" src="{{ $topic->user->payment_qrcode }}" alt="" style="width:320px;height:320px"/>
            <hr>
            <p style="color: #898989;">
                请使用微信扫描二维码。 <a href="https://laravel-china.org/topics/2487" target="_blank" style="color: #898989;text-decoration: underline;">如何开启打赏？</a>
            </p>
        </div>

    </div>
  </div>
</div>

