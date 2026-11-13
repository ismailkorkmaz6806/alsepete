@extends('errors.minimal')

@section('title', __('Sayfa Bulunamadı (404)'))
@section('code', '404')
@section('icon', 'fa-compass')
@section('icon_color', 'bg-indigo-50 text-indigo-600 border-indigo-200')
@section('headline', __('Aradığınız Sayfa Bulunamadı'))
@section('description', __('Ulaşmaya çalıştığınız sayfa kaldırılmış, adı değiştirilmiş veya geçici olarak kullanım dışı kalmış olabilir.'))
@section('custom_message', ($exception && $exception->getMessage() && $exception->getMessage() !== 'Not Found') ? $exception->getMessage() : null)
