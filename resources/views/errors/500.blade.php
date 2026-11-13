@extends('errors.minimal')

@section('title', __('Sunucu Hatası (500)'))
@section('code', '500')
@section('icon', 'fa-triangle-exclamation')
@section('icon_color', 'bg-rose-50 text-rose-600 border-rose-200')
@section('headline', __('Beklenmedik Bir Sunucu Hatası Oluştu'))
@section('description', __('İşleminizi gerçekleştirirken sistemimizde beklenmedik bir aksaklık yaşandı. Teknik ekibimiz durumdan haberdar edilmiştir.'))
@section('custom_message', (app()->isLocal() && $exception && $exception->getMessage()) ? $exception->getMessage() : null)
