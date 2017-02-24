<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\User;
use App\Jobs\SendActivateMail;

class UpdateUserRequest extends Request
{
    public $allowed_fields = [
        'github_name', 'real_name', 'city', 'gender',
        'company', 'twitter_account', 'personal_website',
        'introduction', 'weibo_name', 'weibo_link', 'email','linkedin','signature'
    ];

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'github_id'       => 'unique:users',
            'github_name'     => 'string',
            'wechat_openid'   => 'string',
            'email'           => 'email|unique:users,email,' . $this->id,
            'github_url'      => 'url',
            'image_url'       => 'url',
            'wechat_unionid'  => 'string',
            'linkedin'        => 'url',
            'weibo_link'      => 'url',
            'payment_qrcode'  => 'image',
            'wechat_qrcode'  => 'image',
        ];
    }

    public function performUpdate(User $user)
    {
        $data = $this->only($this->allowed_fields);

        $old_email = $user->email;

        // A dirty fix for api client
        if (is_request_from_api() && $this->get('signature') && !$this->get('introduction')) {
            $data['introduction'] = $this->get('signature');
        }

        // 微信支付二维码
        if ($file = $this->file('payment_qrcode')) {
            $upload_status = app('Phphub\Handler\ImageUploadHandler')->uploadImage($file);
            $data['payment_qrcode'] = $upload_status['filename'];
        }

        // 微信二维码
        if ($file = $this->file('wechat_qrcode')) {
            $upload_status = app('Phphub\Handler\ImageUploadHandler')->uploadImage($file);
            $data['wechat_qrcode'] = $upload_status['filename'];
        }

        $user->update($data);

        if ($user->email && $user->email != $old_email) {
            dispatch(new SendActivateMail($user));
        }
        return $user;
    }
}
