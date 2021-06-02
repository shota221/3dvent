@extends('errors.layout')

@section('title', trans('validation.unauthenticated'))
@section('code', 401)
@section('message', $message)
