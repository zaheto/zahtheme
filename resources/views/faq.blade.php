{{--
  Template Name: Faq pages
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
      <div class="py-10 max-w-[980px] flex flex-col content-center items-start justify-center m-auto">
        @include('partials.page-header')


        <!-- @if ($add_faq)
        <section class="main-cats--homepage">
        <div class="container">
          <ul class="flex flex-col md:flex-row w-full gap-2 md:gap-8 mb-6">

            @foreach ($add_faq as $item)
            <li class="bg-white w-full md:w-1/4 relative">
            {{ $item['faq_title'] }}
            {{ $item['faq_description'] }}
            </li>
            @endforeach

          </ul>
        </div>
        </section>
        @endif -->


        <?php if( have_rows('add_faq') ): ?>

			<div id="accordion">

			<?php while( have_rows('add_faq') ): the_row();

				// vars
				$faq_title = get_sub_field('faq_title');
				$faq_description = get_sub_field('faq_description');

				?>

          <h3><?php echo $faq_title; ?></h3>
          <div>
          <p><?php echo $faq_description; ?></p>

          </div>
			<?php endwhile; ?>

			</div>



      <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
      <script>
     jQuery(function($) {
        $( "#accordion" ).accordion({
          heightStyle: "content"
        });
      } );
      </script>

	<?php endif; ?>


      </div>
  @endwhile
@endsection
