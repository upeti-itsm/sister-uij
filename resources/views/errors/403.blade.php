@extends('errors::minimal')

@section('title', __('Forbiddenn'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
