@extends('errors.minimal')

@section('title', __('Yetkisiz Erişim (403)'))
@section('code', '403')
@section('icon', 'fa-shield-halved')
@section('icon_color', 'bg-amber-50 text-amber-500 border-amber-200')
@section('headline', __('Erişim Yetkiniz Bulunmuyor'))
@section('description', __('Bu sayfaya veya işleme erişim izniniz yok. Satıcı veya yönetici yetkisi gerekebilir ya da başka bir mağazaya ait içeriğe erişmeye çalışıyor olabilirsiniz.'))
@section('custom_message', ($exception && $exception->getMessage() && !str_contains(strtolower($exception->getMessage()), 'this action is unauthorized')) ? $exception->getMessage() : null)
