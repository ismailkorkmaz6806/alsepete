@extends('errors.minimal')

@section('title', __('Çok Fazla İstek (429)'))
@section('code', '429')
@section('icon', 'fa-gauge-high')
@section('icon_color', 'bg-orange-50 text-orange-600 border-orange-200')
@section('headline', __('Çok Fazla İstek Gönderildi'))
@section('description', __('Kısa süre içinde çok fazla istek gönderdiniz. Güvenlik önlemleri sebebiyle lütfen birkaç saniye bekleyip tekrar deneyin.'))
