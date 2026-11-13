@extends('errors.minimal')

@section('title', __('Giriş Yapmalısınız (401)'))
@section('code', '401')
@section('icon', 'fa-user-lock')
@section('icon_color', 'bg-indigo-50 text-indigo-600 border-indigo-200')
@section('headline', __('Giriş Yapmanız Gerekiyor'))
@section('description', __('Bu sayfayı görüntüleyebilmek için lütfen geçerli bir kullanıcı hesabıyla giriş yapın.'))
