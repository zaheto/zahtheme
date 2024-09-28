{{--
  Template Name: Static pages
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    <div class="py-10 max-w-[980px] flex flex-col content-center items-start justify-center m-auto">
    @include('partials.page-header')
    @include('partials.content-page')
    </div>
  @endwhile
@endsection
