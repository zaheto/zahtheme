{{--
  Template Name: Page Build
--}}

@extends('layouts.app')


@while(have_posts()) @php the_post() @endphp
    @include('partials.content-page-build')
@endwhile

