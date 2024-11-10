{{--
  Template Name: Static pages
--}}

@extends('layouts.app')

@section('content')
  <div class="py-8 md:py-12">
    <div class="container mx-auto px-4">
      <div class="max-w-3xl mx-auto">
        <x-fence-calculator :model="'atlas'" :showTabs="true" />
      </div>
    </div>
  </div>
@endsection
