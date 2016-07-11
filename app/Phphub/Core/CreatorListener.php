<?php namespace Phphub\Core;

interface CreatorListener
{
    public function creatorFailed($errors);
    public function creatorSucceed($model);
}
